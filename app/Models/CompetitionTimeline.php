<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetitionTimeline extends Model
{
    // Ubah dari competition_timeline menjadi event_timeline sesuai database
    protected $table = 'event_timeline';

    protected $fillable = ['title', 'date', 'description'];
}