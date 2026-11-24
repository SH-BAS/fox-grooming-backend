<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Paginated notifications for the authenticated user.
     * Returns a flat data array (no nested resource objects).
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 15);
        $perPage = max(1, min($perPage, 50));

        $paginator = Notification::query()
            ->where('user_id', $request->user()->id)
            ->latest('id')
            ->paginate($perPage);

        $items = collect($paginator->items())
            ->map(fn (Notification $notification) => $notification->toApiArray())
            ->values()
            ->all();

        return response()->json([
            'status' => true,
            'message' => 'Notifications retrieved successfully',
            'data' => $items,
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
            'unread_count' => Notification::where('user_id', $request->user()->id)
                ->where('is_read', false)
                ->count(),
        ]);
    }

    /**
     * Notification detail — marks as read.
     */
    public function show(Request $request, $id)
    {
        $notification = Notification::query()
            ->where('user_id', $request->user()->id)
            ->find($id);

        if (!$notification) {
            return $this->error('Notification not found', 404);
        }

        $notification->markAsRead();
        $notification->refresh();

        return $this->success(
            $notification->toApiArray(),
            'Notification retrieved successfully'
        );
    }
}
