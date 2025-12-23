<?php

namespace App\Http\Controllers;

use App\Models\Load;
use App\Models\DriverLocation;
use App\Models\DriverBreadcrumb;
use App\Http\Requests\StoreLocationRequest;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Driver']);
    }

    public function dashboard()
    {
        $activeLoad = auth()->user()
            ->assignedLoads()
            ->whereIn('status', [Load::STATUS_ASSIGNED, Load::STATUS_IN_TRANSIT])
            ->with('documents')
            ->first();

        return view('driver.dashboard', compact('activeLoad'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'load_id' => 'required|exists:loads,id',
            'status' => 'required|in:in_transit,delivered',
        ]);

        $load = Load::findOrFail($request->load_id);

        // Verify this driver is assigned to the load
        if ($load->assigned_driver_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $load->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'status' => $load->status,
        ]);
    }

    public function storeLocation(StoreLocationRequest $request)
    {
        $userId = auth()->id();
        $data = [
            'user_id' => $userId,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'captured_at' => now(),
        ];

        // Update or create latest location
        DriverLocation::updateOrCreate(
            ['user_id' => $userId],
            $data
        );

        // If tracking a load, also save to breadcrumbs
        if ($request->filled('load_id')) {
            $load = Load::find($request->load_id);
            if ($load && $load->assigned_driver_id === $userId) {
                DriverBreadcrumb::create([
                    'load_id' => $request->load_id,
                    'user_id' => $userId,
                    'lat' => $request->lat,
                    'lng' => $request->lng,
                    'captured_at' => now(),
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Location updated',
        ]);
    }
}
