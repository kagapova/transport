<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Station extends Model {
    protected $fillable = ['name', 'lon', 'lat',
    'station_id','pair', 'direction'
    ];

    public function logs() {
        return $this->hasMany(RouteLog::class);
    }
}