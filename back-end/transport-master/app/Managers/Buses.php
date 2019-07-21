<?php

namespace App\Managers;

use App\Models\Bus;

class Buses
{
    /**
     * Считает статусы рейса по критериям для автобуса на маршруте
     *
     * @param Bus $bus
     * @return array
     */
    public static function calculateState(Bus $bus)
    {
        $logs = $bus->logs;
        $data = [];
        $rounds_count = $bus->logs->max('round');
        $target = $bus->route->round_plan;
        $target_min = $target * 0.85;
        $target_max = $target * 1.15;
        foreach (range(1, $rounds_count) as $i) {
            $round_passengers = $logs->where('round', $i)->sum('passengers_count');
            $state = $round_passengers < $target_min ? 2 : ($round_passengers > $target_max ? 0 : 1);
            $data[$i] = [
                'round' => $i,
                'passengers_count' => $round_passengers,
                'state' => $state,
            ];
        }

        return $data;
    }
}