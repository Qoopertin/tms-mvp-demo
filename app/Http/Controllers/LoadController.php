<?php

namespace App\Http\Controllers;

use App\Models\Load;
use App\Models\User;
use App\Http\Requests\StoreLoadRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoadController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Admin|Dispatcher'])->except(['show']);
    }

    public function index(Request $request)
    {
        $query = Load::with('driver');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by driver
        if ($request->filled('driver_id')) {
            $query->where('assigned_driver_id', $request->driver_id);
        }

        $loads = $query->latest()->paginate(15);
        $drivers = User::role('Driver')->get();

        return view('loads.index', compact('loads', 'drivers'));
    }

    public function create()
    {
        return view('loads.create');
    }

    public function store(StoreLoadRequest $request)
    {
        $load = Load::create($request->validated());

        return redirect()->route('loads.show', $load)
            ->with('success', 'Load created successfully!');
    }

    public function show(Load $load)
    {
        $load->load(['driver', 'documents.uploader', 'breadcrumbs' => function($q) {
            $q->orderBy('captured_at', 'desc')->limit(100);
        }]);

        $drivers = User::role('Driver')->get();

        return view('loads.show', compact('load', 'drivers'));
    }

    public function assign(Request $request, Load $load)
    {
        $request->validate([
            'driver_id' => 'required|exists:users,id',
        ]);

        $driver = User::findOrFail($request->driver_id);
        
        if (!$driver->hasRole('Driver')) {
            return back()->with('error', 'Selected user is not a driver.');
        }

        $load->update([
            'assigned_driver_id' => $driver->id,
            'status' => Load::STATUS_ASSIGNED,
        ]);

        return back()->with('success', 'Driver assigned successfully!');
    }

    public function updateStatus(Request $request, Load $load)
    {
        $request->validate([
            'status' => 'required|in:created,assigned,in_transit,delivered',
        ]);

        $load->update(['status' => $request->status]);

        return back()->with('success', 'Load status updated successfully!');
    }
}
