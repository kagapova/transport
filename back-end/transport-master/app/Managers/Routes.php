<?php

namespace App\Managers;

use App\Models\Route;

class Routes
{
    /**
     * Считает статусы рейсов по критериям для маршрута
     *
     * @param Route $route
     * @return array
     */
    public static function calculateState(Route $route)
    {
        $data = [];
        foreach ($route->buses()->with('logs')->get() as $bus) {
            $data[$bus->id] = Buses::calculateState($bus);
        }

        return $data;
    }
}