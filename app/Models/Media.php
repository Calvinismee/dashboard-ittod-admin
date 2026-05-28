<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Media extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'media';

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'uploader_id',
        'name',
        'grouping',
        'type',
        'url',
    ];

    /**
     * Get the user who uploaded the media.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploader_id', 'id');
    }
}
