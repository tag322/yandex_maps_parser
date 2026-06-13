<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class YandexPlaceSettings extends Model
{
    protected $table = 'yandex_place_settings';

    protected $fillable = ['url'];
}
