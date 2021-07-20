<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoroscopeZodiacSign extends Model
{
    protected $table = 'horoscope_zodiac_sign';
    protected $fillable = ['zodiac_sign'];
}
