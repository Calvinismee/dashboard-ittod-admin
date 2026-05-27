<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventParticipant extends Model
{
    use HasFactory;

    protected $table = 'event_participant';

    public $incrementing = false;
    protected $primaryKey = ['user_id', 'event_id'];

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'event_id',
        'date_added',
        'payment_proof',
        'payment_verification',
    ];

    protected $casts = [
        'date_added' => 'datetime',
    ];

    /**
     * Get the user profile associated with this registration.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the event associated with this registration.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'event_id', 'id');
    }
}
