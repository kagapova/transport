<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStats extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('passenger_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('uid', 32);
            $table->string('trackable_type'); // Route|Station
            $table->unsignedInteger('trackable_id'); // bus_id or station_id
            $table->boolean('presence')->default(true); // true=in false=out
            $table->timestamp('found_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('peoples');
    }
}
