<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitionSubmission extends Model
{
    use HasFactory;

    protected $table = 'competition_submission';

    public $incrementing = false;
    protected $primaryKey = ['team_id', 'competition_id'];

    protected $fillable = [
        'team_id',
        'competition_id',
        'submission_object',
    ];

    protected $casts = [
        'submission_object' => 'array',
    ];

    /**
     * Get the team associated with the submission.
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id', 'id');
    }

    /**
     * Get the event (competition) associated with the submission.
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class, 'competition_id', 'id');
    }
}
