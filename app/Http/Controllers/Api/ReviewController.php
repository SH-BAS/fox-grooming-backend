<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        try {
            $booking = Booking::find($request->booking_id);

            if (!$booking) {
                return $this->error('Booking not found', 404);
            }

            if ($booking->user_id !== $request->user()->id) {
                return $this->error('Unauthorized', 403);
            }

            if ($booking->status !== 'completed') {
                return $this->error('You can only review completed bookings', 400);
            }

            if ($booking->is_reviewed) {
                return $this->error('This booking has already been reviewed', 400);
            }

            DB::beginTransaction();

            $review = Review::create([
                'booking_id' => $booking->id,
                'user_id' => $request->user()->id,
                'rating' => $request->rating,
                'staff_id' => $booking->staff_id,
                'comment' => $request->comment,
            ]);

            $booking->update(['is_reviewed' => true]);

            DB::commit();

            return $this->success($review, 'Review submitted successfully', 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), 500);
        }
    }

    public function getBookingReview($id)
    {
        $booking = Booking::find($id);
        if (!$booking) {
            return $this->error('Booking not found', 404);
        }
        $review = $booking->review;
        if (!$review) {
            return $this->error('Review not found', 404);
        }
        return $this->success($review, 'Review retrieved');
    }
    public function index()
    {
        $reviews = Review::with('user')->where('is_publish', 1)->orderBy('rating', 'desc')->get();
        if (!$reviews) {
            return $this->success([], 'Review not found', 200);
        }
        return $this->success($reviews, 'Review retrieved');
    }
}
