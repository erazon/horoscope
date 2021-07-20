<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoroscopeData extends Model
{
    protected $table = 'horoscope_data';
    protected $fillable = ['zodiac_id', 'score', 'calc_day'];
}
