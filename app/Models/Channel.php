<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Image\Manipulations;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Channel extends BaseModel implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param Media|null $media
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->fit(Manipulations::FIT_CROP, 100, 100)
            ->sharpen(10);
    }

    public function image()
    {
        $image = $this->getMedia('images')->first();
        if ($image) {
            return $image->getUrl('thumb');
        }

        return null;
    }

    public function editable()
    {
        if (!auth()->check()) return false;
        return $this->user_id === auth()->user()->id;
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function videos()
    {
        return $this->hasMany(Video::class);
    }
}
