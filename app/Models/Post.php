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

    protected $with = [
        'user',
        'category'
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function category() {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

}
