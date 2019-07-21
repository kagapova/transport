<?php

namespace App\Console\Commands;

use App\Models\Bus;
use App\Models\Route;
use App\Models\RouteLog;
use App\Models\Station;
use Carbon\Carbon;
use Illuminate\Console\Command;

class LoadData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'load:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data for example';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $routes = [
            [
                'id' => 14569,
                'title' => 'Автобус А-2',
                'description' => 'Микрорайон Верхние Печеры - Автовокзал «Щербинки»',
            ],
            [
                'id' => 14567,
                'title' => 'Автобус А-1',
                'description' => 'Площадь Минина и Пожарского - Автовокзал «Щербинки»',
            ],
            [
                'id' => 14570,
                'title' => 'Автобус А-3',
                'description' => 'Красное Сормово - Площадь Свободы',
            ],
            [
                'id' => 14571,
                'title' => 'Автобус А-4',
                'description' => 'Деревня Афонино - Улица Долгополова',
            ],
            [
                'id' => 14514,
                'title' => 'Автобус Т-4',
                'description' => 'Площадь Минина и Пожарского - Автовокзал «Щербинки»',
            ],

        ];
        foreach ($routes as $item) {
            /**
             * @var Route
             */
            $item['round_plan'] = mt_rand(3, 6) * 50;
            $route = Route::query()->create($item);

            $url = "http://www.maxikarta.ru/nn/transport/query/stations?route_id={$item['id']}";
            $data = json_decode(file_get_contents($url), true);
            $stations = [];
            foreach ($data['stations'] as $station) {
                $stations[] = Station::query()->firstOrCreate(['station_id' => $station['station_id']], $station);
            }
            $route->stations()->sync(collect($stations)->pluck('id'));

            $bus_count = mt_rand(3, 7);
            foreach (range(1, $bus_count) as $i) {
                $bus = Bus::query()->create([
                    'number' => 'A11' . $bus_count . 'BT152',
                    'description' => 'Bus',
                    'passenger_max' => mt_rand(4, 8) * 10,
                ]);
                $route->buses()->save($bus);
            }
        }

        // fill passengers logs
        $starts = Carbon::yesterday()->startOfDay()->addHours(6);
        $ends = Carbon::yesterday()->startOfDay()->addHours(23);
        /**
         * @var Route[]
         */
        $routes = Route::all();
        $rounds = [];
        foreach ($routes as $route) {
            $route_time = (clone $starts);
            while ($route_time->lt($ends)) {
                foreach ($route->buses as $bus) {
                    if (empty($rounds[$bus->id])) {
                        $rounds[$bus->id] = 1;
                    } else {
                        $rounds[$bus->id]++;
                    }
                    /**
                     * @var $bus Bus
                     */
                    $station_time = clone $route_time;
                    $prev_direction = null;
                    foreach ($route->stations as $station) {
                        srand($route->id + $station->id + $bus->id + $rounds[$bus->id]);
                        /**
                         * @var $station Station
                         */
                        if (!is_null($prev_direction) && $prev_direction != $station->direction && !empty($log)) {
                            $log->update(['passengers_count' => 0]);
                        }
                        $minutes = rand(1, 3);
                        $log = RouteLog::query()->create([
                            'station_id' => $station->id,
                            'route_id' => $route->id,
                            'bus_id' => $bus->id,
                            'created_at' => $station_time,
                            'direction' => $station->direction,
                            'round' => $rounds[$bus->id],
                            'passengers_count' => rand(1, 14) + $this->timeCorrection($station_time),
                        ]);
                        $station_time->addMinutes($minutes);
                        $prev_direction = $station->direction;
                    }
                    $log->update(['passengers_count' => 0]);
                    $route_time->addMinutes(15);
                }
            }
        }
    }

    public function timeCorrection(Carbon $time)
    {
        $add = 0;
        $h = $time->format('h');
        $min = (int)$time->format('i');
        if ($h > 6 && $h < 10) {
            $add = rand(4, 8);
            if ($min > 30) {
                $add += rand(1, 4);
            } elseif ($min > 40) {
                $add += rand(2, 5);
            } elseif ($min > 50) {
                $add += rand(3, 6);
            }
        }
        if ($h > 16 && $h < 20) {
            $add = rand(2, 6);
            if ($min < 10) {
                $add += rand(3, 6);
            } elseif ($min < 20) {
                $add += rand(2, 5);
            } elseif ($min < 30) {
                $add += rand(1, 3);
            }
        }

        return $add;
    }
}
