<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVietnamAddress extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       DB::unprepared(file_get_contents(base_path().'/database/migrations/dia_chi_viet_nam.sql'));
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('devvn_xaphuongthitran');
		Schema::dropIfExists('devvn_tinhthanhpho');
		Schema::dropIfExists('devvn_quanhuyen');
    }
}
