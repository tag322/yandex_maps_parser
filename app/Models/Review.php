<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    public function place()
    {
        return $this->belongsTo(Place::class, 'yandex_oid', 'yandex_oid');
    }

    protected $fillable = [
        'yandex_oid',
        'author',
        'rating',
        'text',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];
}
