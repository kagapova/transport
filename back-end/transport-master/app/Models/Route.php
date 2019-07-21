<?php

namespace App\Models;

use App\Managers\Routes;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    const TYPE_BUS = 'Автобус',
        TYPE_MT = 'Маршрутное такси',
        TYPE_TRAM = 'Трамвай',
        TYPE_TROLLEY = 'Троллейбус';

    protected $fillable = ['title', 'description', 'round_plan', 'type', 'length', 'route_id', 'start_station_id',
        'end_station_id'];
    protected $appends = ['state'];

    public function stations()
    {
        return $this->belongsToMany(Station::class);
    }

    public function tracks()
    {
        return $this->morphMany(PassengerLog::class, 'trackable');
    }

    public function buses()
    {
        return $this->hasMany(Bus::class);
    }

    public function logs()
    {
        return $this->hasMany(RouteLog::class);
    }

    public function getStateAttribute()
    {
//        return $this->id % 3;

        return \Cache::remember("states:" . $this->id . ':plan:' . $this->round_plan, mt_rand(300, 900) * 60, function () {
            $states = Routes::calculateState($this);
            $result = [];
            foreach ($states as $busState) {
                foreach ($busState as $state) {
                    if (empty($result[$state['state']])) {
                        $result[$state['state']] = 0;
                    }
                    $result[$state['state']]++;
                }
            }
            $max = max($result);
            $route_state = array_search($max, $result);

            return $route_state;
        });

    }

}