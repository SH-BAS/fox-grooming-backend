<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    protected $fillable = [
        'user_id',
        'admin_id',
        'title',
        'body',
        'type',
        'booking_id',
        'broadcast_id',
        'data',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'booking_id' => 'integer',
        'broadcast_id' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function broadcast(): BelongsTo
    {
        return $this->belongsTo(BroadcastNotification::class, 'broadcast_id');
    }

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function markAsRead(): void
    {
        if ($this->is_read) {
            return;
        }

        $this->forceFill([
            'is_read' => true,
            'read_at' => now(),
        ])->save();
    }

    /**
     * Notify relevant admins about a new booking (DB only).
     */
    public static function notifyAdminsForBooking(Booking $booking): void
    {
        $booking->loadMissing(['user', 'franchise']);

        $title = 'New Booking Created';
        $body = 'Booking #' . $booking->id . ' was created by '
            . ($booking->user?->name ?? 'a customer') . '.';

        $adminQuery = Admin::query()
            ->where('is_active', true)
            ->whereHas('role', function ($q) {
                $q->where('slug', 'super_admin');
            });

        $admins = $adminQuery->get();

        if ($booking->franchise_id) {
            $franchiseAdmins = Admin::query()
                ->where('is_active', true)
                ->where('franchise_id', $booking->franchise_id)
                ->get();

            $admins = $admins->merge($franchiseAdmins)->unique('id');
        }

        foreach ($admins as $admin) {
            static::create([
                'admin_id' => $admin->id,
                'user_id' => null,
                'title' => $title,
                'body' => $body,
                'type' => 'booking',
                'booking_id' => $booking->id,
                'data' => [
                    'type' => 'booking',
                    'booking_id' => (string) $booking->id,
                ],
                'is_read' => false,
            ]);
        }
    }

    /**
     * Flat array for API responses (no nested objects).
     */
    public function toApiArray(): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'admin_id' => $this->admin_id,
            'title' => $this->title,
            'body' => $this->body,
            'type' => $this->type,
            'booking_id' => $this->booking_id,
            'broadcast_id' => $this->broadcast_id,
            'is_read' => (bool) $this->is_read,
            'read_at' => optional($this->read_at)?->toDateTimeString(),
            'created_at' => optional($this->created_at)?->toDateTimeString(),
            'updated_at' => optional($this->updated_at)?->toDateTimeString(),
        ];
    }
}
