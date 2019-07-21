<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouteLog extends Model
{
    protected $fillable = ['station_id', 'route_id', 'bus_id', 'created_at', 'direction', 'round', 'passengers_count'];

    protected $dates = ['created_at'];

    public $timestamps = false;

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }
}