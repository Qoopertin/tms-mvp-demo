<?php

namespace App\Http\Controllers;

use App\Models\DriverLocation;
use App\Models\User;
use Illuminate\Http\Request;

class DispatchController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Admin|Dispatcher']);
    }

    public function map()
    {
        return view('dispatch.map');
    }

    public function getDriverLocations()
    {
        $locations = DriverLocation::with('driver:id,name')
            ->whereHas('driver', function($q) {
                $q->role('Driver');
            })
            ->where('captured_at', '>=', now()->subHours(4))
            ->get()
            ->map(function($location) {
                return [
                    'id' => $location->driver->id,
                    'name' => $location->driver->name,
                    'lat' => (float) $location->lat,
                    'lng' => (float) $location->lng,
                    'last_update' => $location->captured_at->diffForHumans(),
                    'timestamp' => $location->captured_at->toIso8601String(),
                ];
            });

        return response()->json($locations);
    }
}
