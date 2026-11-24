<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BroadcastNotification;
use App\Models\User;
use App\Services\FcmService;
use Illuminate\Http\Request;

class BroadcastNotificationController extends Controller
{
    public function index()
    {
        $broadcasts = BroadcastNotification::with(['admin', 'user'])
            ->latest('id')
            ->paginate(12);

        return view('admin.broadcast-notifications.index', compact('broadcasts'));
    }

    public function create()
    {
        $users = User::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'email', 'fcm_token']);

        $tokenUserCount = User::query()
            ->whereNotNull('fcm_token')
            ->where('fcm_token', '!=', '')
            ->count();

        return view('admin.broadcast-notifications.create', compact('users', 'tokenUserCount'));
    }

    public function store(Request $request, FcmService $fcmService)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:2000',
            'target_type' => 'required|in:all,specific',
            'user_ids' => 'nullable|required_if:target_type,specific|array|min:1',
            'user_ids.*' => 'integer|exists:users,id',
        ]);

        $selectedUserIds = collect($request->input('user_ids', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values()
            ->all();

        if ($request->target_type === 'all') {
            $recipients = User::query()
                ->whereNotNull('fcm_token')
                ->where('fcm_token', '!=', '')
                ->get(['id', 'fcm_token', 'name', 'email']);
        } else {
            $recipients = User::query()
                ->whereIn('id', $selectedUserIds)
                ->get(['id', 'fcm_token', 'name', 'email']);
        }

        if ($recipients->isEmpty()) {
            return redirect()->back()
                ->withInput()
                ->with('error', $request->target_type === 'all'
                    ? 'No users with an FCM token were found.'
                    : 'Please select at least one valid user.');
        }

        $broadcast = BroadcastNotification::create([
            'admin_id' => auth()->guard('admin')->id(),
            'title' => $request->title,
            'body' => $request->body,
            'type' => 'broadcast',
            'target_type' => $request->target_type,
            'user_id' => $request->target_type === 'specific' && count($selectedUserIds) === 1
                ? $selectedUserIds[0]
                : null,
            'user_ids' => $request->target_type === 'specific' ? $selectedUserIds : null,
            'audience_count' => $recipients->count(),
            'sent_count' => 0,
            'failed_count' => 0,
        ]);

        $sent = 0;
        $failed = 0;

        foreach ($recipients as $user) {
            $ok = $fcmService->send(
                $user->fcm_token,
                $broadcast->title,
                $broadcast->body,
                [
                    'type' => 'broadcast',
                    'broadcast_id' => (string) $broadcast->id,
                ],
                $user->id
            );

            if ($ok) {
                $sent++;
            } else {
                $failed++;
            }
        }

        $broadcast->update([
            'sent_count' => $sent,
            'failed_count' => $failed,
        ]);

        return redirect()
            ->route('admin.broadcast-notifications.show', $broadcast->id)
            ->with('success', "Broadcast sent. Delivered: {$sent}, Failed: {$failed}.");
    }

    public function show(BroadcastNotification $broadcast_notification)
    {
        $broadcast = $broadcast_notification->load(['admin', 'user']);

        $targetUsers = collect();
        if ($broadcast->target_type === 'specific' && !empty($broadcast->user_ids)) {
            $targetUsers = User::withTrashed()
                ->whereIn('id', $broadcast->user_ids)
                ->orderBy('name')
                ->get(['id', 'name', 'email']);
        } elseif ($broadcast->target_type === 'specific' && $broadcast->user_id) {
            $targetUsers = User::withTrashed()
                ->where('id', $broadcast->user_id)
                ->get(['id', 'name', 'email']);
        }

        $audience = $broadcast->notifications()
            ->with('user')
            ->latest('id')
            ->paginate(20);

        return view('admin.broadcast-notifications.show', compact('broadcast', 'audience', 'targetUsers'));
    }
}
