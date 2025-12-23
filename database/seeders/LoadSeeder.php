<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Load;
use App\Models\User;

class LoadSeeder extends Seeder
{
    public function run(): void
    {
        $driver = User::where('email', 'driver@example.com')->first();

        Load::create([
            'reference_no' => 'LOAD-001',
            'pickup_address' => '123 Warehouse Ave, Chicago, IL 60601',
            'pickup_lat' => 41.8781,
            'pickup_lng' => -87.6298,
            'delivery_address' => '456 Distribution Blvd, Los Angeles, CA 90001',
            'delivery_lat' => 34.0522,
            'delivery_lng' => -118.2437,
            'status' => Load::STATUS_ASSIGNED,
            'assigned_driver_id' => $driver->id,
        ]);

        Load::create([
            'reference_no' => 'LOAD-002',
            'pickup_address' => '789 Storage St, Houston, TX 77001',
            'pickup_lat' => 29.7604,
            'pickup_lng' => -95.3698,
            'delivery_address' => '321 Logistics Ln, Phoenix, AZ 85001',
            'delivery_lat' => 33.4484,
            'delivery_lng' => -112.0740,
            'status' => Load::STATUS_CREATED,
            'assigned_driver_id' => null,
        ]);
    }
}
