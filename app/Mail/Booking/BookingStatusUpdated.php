<?php

namespace App\Mail\Booking;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingStatusUpdated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $booking;
    public $statusMessage;

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking, $statusMessage = null)
    {
        $this->booking = $booking->load(['user', 'pet.category', 'pet.type', 'pet.size', 'franchise', 'bookedServices.service', 'staff']);
        $this->statusMessage = $statusMessage ?? $this->getDefaultMessage();
    }

    /**
     * Get the default message based on booking status.
     */
    protected function getDefaultMessage()
    {
        switch ($this->booking->status) {
            case 'confirmed':
                return 'Your booking has been confirmed. We look forward to seeing you!';
            case 'ontheway':
                return 'Our groomer is on the way to your location!';
            case 'arrived':
                return 'Our groomer has arrived and is ready to start!';
            case 'started':
                return 'Your pet is now being groomed. We\'ll let you know when they\'re ready.';
            case 'completed':
                return 'Hooray! The grooming session is complete. We hope you and your pet loved it!';
            case 'cancelled':
                return 'Your booking has been cancelled. If you have any questions, please contact us.';
            default:
                return 'The status of your booking has been updated to: ' . ucfirst(str_replace('_', ' ', $this->booking->status));
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking Status Update - ' . ucfirst(str_replace('_', ' ', $this->booking->status)) . ' - ' . config('app.name'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.bookings.status-updated',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
