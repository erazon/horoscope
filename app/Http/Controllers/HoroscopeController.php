<?php

namespace App\Http\Controllers;

use App\Models\HoroscopeData;
use App\Models\HoroscopeYear;
use App\Models\HoroscopeZodiacSign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HoroscopeController extends Controller
{
    public function bestMonth($year, $zodiac_sign_id)
    {
        // validate if the year is properly formatted
        $input = ['year' => $year, 'zodiac_sign_id' => $zodiac_sign_id];
        $rules = [
            'year' => 'required|digits:4|integer|min:1900|max:9999',
            'zodiac_sign_id' => 'required|digits:1|integer|min:1|max:12'
        ];
        if(!Validator::make($input, $rules)->passes()){
            return response()->json(['msg' => 'Invalid data']);
        }

        // if the year data is already generated return
        if (!DB::table('horoscope_year')->where('generated_year', $year)->exists()) {
            return response()->json(['msg' => 'No data found']);
        }
        
        // query the data for a given year and zodiac sign
        $result = DB::select(DB::raw("SELECT MONTH(calc_day) AS max_month, avg(score) AS max_score
            FROM horoscope.horoscope_data
            WHERE YEAR(calc_day)=:year AND zodiac_id=:zodiac_id
            GROUP BY MONTH(calc_day) ORDER BY AVG(score) DESC LIMIT 1;"), array(
            'year' => $year,
            'zodiac_id' => $zodiac_sign_id
        ));

        $dateObj = \DateTime::createFromFormat('!m', $result[0]->max_month);
        $monthName = $dateObj->format('F');
        
        return response()->json(['best_month'=>$monthName, 'best_avg_score'=>$result[0]->max_score]);
    }

    public function bestYear($year)
    {
        // validate if the year is properly formatted
        $input = ['year' => $year];
        $rules = ['year' => 'required|digits:4|integer|min:1900|max:9999'];
        if(!Validator::make($input, $rules)->passes()){
            return response()->json(['msg' => 'Invalid data']);
        }

        // if the year data is already generated return
        if (!DB::table('horoscope_year')->where('generated_year', $year)->exists()) {
            return response()->json(['msg' => 'No data found']);
        }
        
        // get the already calculated data
        $best_year = HoroscopeYear::where(['generated_year'=>$year])->first();

        // get the zodiac name
        $zodiac = HoroscopeZodiacSign::find($best_year->best_zodiac_id);
        
        return response()->json(['zodiac_sign'=>$zodiac->zodiac_sign]);
    }

    public function calendar($year, $zodiac_sign_id)
    {
        // validate if the year is properly formatted
        $input = ['year' => $year, 'zodiac_sign_id' => $zodiac_sign_id];
        $rules = [
            'year' => 'required|digits:4|integer|min:1900|max:9999',
            'zodiac_sign_id' => 'required|digits:1|integer|min:1|max:12'
        ];
        if(!Validator::make($input, $rules)->passes()){
            return response()->json(['msg' => 'Invalid data']);
        }

        // if the year data does not exist then return
        if (!DB::table('horoscope_year')->where('generated_year', $year)->exists()) {
            return response()->json(['msg' => 'No data found']);
        }

        // get the zodiac name
        $zodiac = HoroscopeZodiacSign::find($zodiac_sign_id);
        
        // query the data for a given year and zodiac sign
        $data = DB::table('horoscope_data')
            ->whereZodiacId($zodiac_sign_id)
            ->whereYear('calc_day', $year)
            ->get();

        $response = array();
        foreach($data as $row) {
            // calculate red & green based on score
            $red = sprintf('%02X', abs(round(($row->score-10) * (255/9))));
            $green = sprintf('%02X', (round(($row->score-1) * (255/9))));

            array_push($response,
                [
                    'day' => $row->calc_day,
                    'score' => $row->score,
                    'day_color' => '#'.$red.$green.'00'
                ]);
        }
        
        return response()->json(['zodiac_sign'=>$zodiac->zodiac_sign, 'calendar'=>$response]);
    }

    public function generate($year)
    {
        // validate if the year is properly formatted
        $input = ['year' => $year];
        $rules = ['year' => 'required|digits:4|integer|min:1900|max:9999'];
        if(!Validator::make($input, $rules)->passes()){
            return response()->json(['msg' => 'Invalid year format']);
        }

        // if the year data is already generated return
        if (DB::table('horoscope_year')->where('generated_year', $year)->exists()) {
            return response()->json(['msg' => 'Already generated']);
        }
        
        DB::beginTransaction();
        try {
            // date initialize to first day of the given year
            $day = new \DateTime($year.'-01-01');

            // initializing zodiac sign array
            for($zodiac_id=1; $zodiac_id<=12; $zodiac_id++) {
                $zodiac_sign[$zodiac_id] = 0;
            }

            // loop through until the next year
            while ($day->format('Y') == $year) {
                $horoscope_data = array();
                for($zodiac_id=1; $zodiac_id<=12; $zodiac_id++) {
                    $score = rand(1, 10); // generating the random number for zodiac
                    $zodiac_sign[$zodiac_id] += $score;
                    array_push($horoscope_data,
                    [
                        'zodiac_id' => $zodiac_id,
                        'score' => $score,
                        'calc_day' => $day->format('Y-m-d'),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                }
                DB::table('horoscope_data')->insert($horoscope_data);

                $day->add(new \DateInterval('P1D'));
            }
            
            $maxs = array_keys($zodiac_sign, max($zodiac_sign));
            
            // inserting into horoscope_year
            DB::table('horoscope_year')->insert([
                'generated_year' => $year,
                'best_zodiac_id' => $maxs[0],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);

            DB::commit();
            // all good
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['msg' => $e->getMessage()]);
        }
        
        return response()->json(['msg' => 'Successfully Generated']);
    }
}
