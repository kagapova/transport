<?php

namespace App\Models;

use App\Managers\Routes;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    protected $fillable = ['title', 'description', 'round_plan'];
    protected $appends = ['state', 'type'];

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
    }

    public function getTypeAttribute()
    {
        return 'Автобус';
    }
}