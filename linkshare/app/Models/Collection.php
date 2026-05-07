<?php

namespace App\Models;

use Database\Factories\CollectionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;

class Collection extends Model
{
    /** @use HasFactory<CollectionFactory> */
    use HasFactory;

    protected $guarded = [];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute(): ?string
    {
        return $this->image_path ? Storage::disk('imagekit')->url($this->image_path) : null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function links(): HasMany
    {
        return $this->hasMany(Link::class);
    }

    public function shares(): MorphMany
    {
        return $this->morphMany(Share::class, 'shareable');
    }
}
