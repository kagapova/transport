<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->unsignedInteger('station_id');
            $table->unsignedInteger('pair')->nullable();
            $table->unsignedTinyInteger('direction')->default(0);
            $table->float('lon');
            $table->float('lat');
            $table->timestamps();

            $table->index('station_id');
        });

        Schema::create('routes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('description');
            $table->string('type')->default('Автобус');
            $table->unsignedInteger('start_station_id')->nullable();
            $table->unsignedInteger('end_station_id')->nullable();
            $table->unsignedInteger('round_plan');
            $table->unsignedInteger('route_id');
            $table->float('length');
            $table->timestamps();
        });

        Schema::create('buses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('number');
            $table->string('description');
            $table->unsignedInteger('route_id')->nullable();
            $table->unsignedSmallInteger('passenger_max');
            $table->timestamps();

            $table->index('route_id');
        });

        Schema::create('route_station', function (Blueprint $table) {
            $table->unsignedInteger('route_id');
            $table->unsignedInteger('station_id');

            $table->index('route_id');
            $table->index('station_id');
        });

        Schema::create('route_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('station_id');
            $table->unsignedInteger('route_id');
            $table->unsignedInteger('bus_id');
            $table->boolean('direction');
            $table->unsignedTinyInteger('round');
            $table->unsignedInteger('passengers_count')->default(0);
            $table->timestamp('created_at');

            $table->index('route_id');
            $table->index('station_id');
            $table->index('bus_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stations');
    }
}
