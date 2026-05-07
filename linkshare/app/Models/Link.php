<?php

namespace App\Models;

use Database\Factories\LinkFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Link extends Model
{
    /** @use HasFactory<LinkFactory> */
    use HasFactory;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'is_favorite' => 'boolean',
        ];
    }

    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    // Pour remonter à l'user via la collection
    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, Collection::class, 'id', 'id', 'collection_id', 'user_id');
    }

    public function shares(): MorphMany
    {
        return $this->morphMany(Share::class, 'shareable');
    }
}
