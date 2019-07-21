<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Station;
use Carbon\Carbon;

class StationsController extends Controller
{

    public function index()
    {
        return Station::all();
    }

    public function show(Station $station)
    {
        return $station;
    }
}