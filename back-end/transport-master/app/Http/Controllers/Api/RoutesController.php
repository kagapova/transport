<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Route;
use App\Models\Station;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RoutesController extends Controller
{

    public function index()
    {
        $routes = Route::query()->withCount(['buses'])->get();

        return $routes;
    }

    public function show(Route $route)
    {
        $route->load(['stations', 'buses']);
        return $route;
    }

    public function stats(Route $route, Request $request)
    {
        $direction = $request->input('direction', 0);

        $logs = $route->logs()->where('direction', $direction)->get();
        $series = [];
        foreach ($logs as $log) {
            $key = $log->bus_id . ':' . $log->round;
            if (empty($series[$key])) {
                $series[$key] = [
                    'name' => $log->bus->number . " [Рейс {$log->round}]",
                    'data' => [],
                ];
                $prev_count = 0;
            } else {
                $prev_count = end($series[$key]['data']);
            }
            $series[$key]['data'][] = $log->passengers_count + $prev_count;
        }
        $stations = $route->stations()->where('direction', $direction)->get();
        $data = [
            'title' => $route->title,
            'description' => $route->description,
            'x' => ['data' => $stations->pluck('name')->toArray()],
            'series' => array_values($series),
            'median' => $route->round_plan,
        ];

        return collect($data);
    }
}