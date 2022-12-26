<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Post extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'title',
        'user_id',
        'category_id',
        'status'
    ];

    protected $hidden = [
        'media'
    ];

    const STATUS_ACTIVE = 'active';
    const STATUS_WAITING = 'waiting';

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->user_id = Auth::id();
            $model->status = self::STATUS_WAITING;
        });
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')->useDisk('images');
    }

}
