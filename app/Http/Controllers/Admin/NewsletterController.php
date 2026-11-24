<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use App\Mail\GeneralNewsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class NewsletterController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'active');

        $activeCount = Newsletter::where('status', true)->count();
        $inactiveCount = Newsletter::where('status', false)->count();

        $query = Newsletter::query();

        if ($status === 'inactive') {
            $query->where('status', false);
        } else {
            $status = 'active';
            $query->where('status', true);
        }

        $subscribers = $query->latest()->paginate(8)->appends(['status' => $status]);

        return view('admin.newsletters.index', compact(
            'subscribers',
            'status',
            'activeCount',
            'inactiveCount'
        ));
    }

    public function updateStatus(Request $request, Newsletter $newsletter)
    {
        $newsletter->status = !$newsletter->status;
        $newsletter->save();

        $label = $newsletter->status ? 'active' : 'unsubscribed';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => (bool) $newsletter->status,
                'message' => "Subscriber marked as {$label}.",
            ]);
        }

        return redirect()
            ->route('admin.newsletters.index', ['status' => $newsletter->status ? 'active' : 'inactive'])
            ->with('success', "Subscriber marked as {$label}.");
    }

    public function destroy(Newsletter $newsletter)
    {
        $newsletter->delete();
        return back()->with('success', 'Subscriber deleted successfully.');
    }

    public function create()
    {
        $activeCount = Newsletter::where('status', true)->count();
        return view('admin.newsletters.create', compact('activeCount'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
        ]);

        $subscribers = Newsletter::where('status', 1)->get();

        if ($subscribers->isEmpty()) {
            return back()->with('error', 'No active subscribers found.');
        }

        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)->queue(new GeneralNewsletter($request->title, $request->content));
        }

        return redirect()->route('admin.newsletters.index')->with('success', 'Newsletter is being sent to ' . $subscribers->count() . ' subscribers.');
    }
}
