<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $zodiac_signs = [
            ['id' => 1, 'zodiac_sign' => 'Aquarius'],
            ['id' => 2, 'zodiac_sign' => 'Pisces'],
            ['id' => 3, 'zodiac_sign' => 'Aries'],
            ['id' => 4, 'zodiac_sign' => 'Taurus'],
            ['id' => 5, 'zodiac_sign' => 'Gemini'],
            ['id' => 6, 'zodiac_sign' => 'Cancer'],
            ['id' => 7, 'zodiac_sign' => 'Leo'],
            ['id' => 8, 'zodiac_sign' => 'Virgo'],
            ['id' => 9, 'zodiac_sign' => 'Libra'],
            ['id' => 10, 'zodiac_sign' => 'Scorpio'],
            ['id' => 11, 'zodiac_sign' => 'Sagittarius'],
            ['id' => 12, 'zodiac_sign' => 'Capricorn']
        ];

        DB::table('horoscope_zodiac_sign')->insert($zodiac_signs);
    }
}
