<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YandexPlace extends Model
{
    protected $casts = [
        'reviews' => 'array',
    ];

    protected $fillable = [
        'yandex_oid',
        'title',
        'rating',
        'reviews_count',
        'ratings_count',
        'reviews',
        'expires_at',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class, 'yandex_oid', 'yandex_oid');
    }
}
