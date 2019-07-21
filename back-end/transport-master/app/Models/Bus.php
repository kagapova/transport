<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    protected $fillable = ['number', 'description', 'passenger_max'];
    protected $appends = ['rounds_count', 'passengers_count'];

    public function logs()
    {
        return $this->hasMany(RouteLog::class);
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function getRoundsCountAttribute()
    {
        return $this->logs()->max('round');
    }

    public function getPassengersCountAttribute()
    {
        return (int)$this->logs()->sum('passengers_count');
    }

}