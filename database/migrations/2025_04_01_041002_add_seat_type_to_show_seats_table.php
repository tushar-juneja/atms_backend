<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeatTypeToShowSeatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('show_seats', function (Blueprint $table) {
            $table->enum('seat_type', ['ordinary', 'balcony'])->default('ordinary')->after('seat_id'); //place after seat_id
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('show_seats', function (Blueprint $table) {
            $table->dropColumn('seat_type');
        });
    }
}