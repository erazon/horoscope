<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHoroscopeDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horoscope_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zodiac_id')->constrained('horoscope_zodiac_sign');
            $table->tinyInteger('score');
            $table->date('calc_day');
            $table->timestamps();
            $table->unique(['zodiac_id', 'calc_day']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('horoscope_data');
    }
}
