<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BroadcastNotification extends Model
{
    protected $fillable = [
        'admin_id',
        'title',
        'body',
        'type',
        'target_type',
        'user_id',
        'user_ids',
        'audience_count',
        'sent_count',
        'failed_count',
    ];

    protected $casts = [
        'audience_count' => 'integer',
        'sent_count' => 'integer',
        'failed_count' => 'integer',
        'user_ids' => 'array',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'broadcast_id');
    }
}
