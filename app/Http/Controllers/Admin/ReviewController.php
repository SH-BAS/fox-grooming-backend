<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->get('status', 'published');

        $publishedCount = Review::where('is_publish', true)->count();
        $unpublishedCount = Review::where('is_publish', false)->count();

        $query = Review::with(['booking', 'staff', 'user']);

        if ($status === 'unpublished') {
            $query->where('is_publish', false);
        } else {
            $status = 'published';
            $query->where('is_publish', true);
        }

        $reviews = $query->latest()->paginate(8)->appends(['status' => $status]);

        return view('admin.reviews.index', compact(
            'reviews',
            'status',
            'publishedCount',
            'unpublishedCount'
        ));
    }

    public function show(Review $review)
    {
        $review->load([
            'booking.user',
            'booking.pet',
            'booking.staff',
            'booking.bookedServices.service',
            'user',
            'staff'
        ]);

        return view('admin.reviews.show', compact('review'));
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'Review deleted successfully.');
    }

    public function update(Request $request, Review $review)
    {
        $review->is_publish = !$review->is_publish;
        $review->save();

        $label = $review->is_publish ? 'published' : 'unpublished';

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_publish' => (bool) $review->is_publish,
                'message' => "Review marked as {$label}.",
            ]);
        }

        return redirect()
            ->route('admin.reviews.index', ['status' => $review->is_publish ? 'published' : 'unpublished'])
            ->with('success', "Review marked as {$label}.");
    }
}
