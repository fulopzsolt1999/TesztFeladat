<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (DB::connection('mysql_carservice')->table('clients')->count() == 0) {
            $clientsJson = File::get(database_path('seeders/data/clients.json'));
            $clients = json_decode($clientsJson, true);
            // idcard to card_number
            $clients = array_map(function ($client) {
                $client['card_number'] = $client['idcard'];
                unset($client['idcard']);
                return $client;
            }, $clients);
            DB::connection('mysql_carservice')->table('clients')->insert($clients);
        }

        if (DB::connection('mysql_carservice')->table('cars')->count() == 0) {
            $carsJson = File::get(database_path('seeders/data/cars.json'));
            $cars = json_decode($carsJson, true);
            // accident to accidents
            $cars = array_map(function ($car) {
                $car['accidents'] = $car['accident'];
                unset($car['accident']);
                return $car;
            }, $cars);
            DB::connection('mysql_carservice')->table('cars')->insert($cars);
        }

        if (DB::connection('mysql_carservice')->table('services')->count() == 0) {
            $servicesJson = File::get(database_path('seeders/data/services.json'));
            $services = json_decode($servicesJson, true);
            // eventtime to event_time & lognumber to log_number
            $services = array_map(function ($service) {
                $service['event_time'] = $service['eventtime'];
                $service['log_number'] = $service['lognumber'];
                unset($service['eventtime'], $service['lognumber']);
                return $service;
            }, $services);
            DB::connection('mysql_carservice')->table('services')->insert($services);
        }
    }
}
