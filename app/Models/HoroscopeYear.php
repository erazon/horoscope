<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoroscopeYear extends Model
{
    protected $table = 'horoscope_year';
    protected $fillable = ['generated_year', 'best_zodiac_id'];
}
