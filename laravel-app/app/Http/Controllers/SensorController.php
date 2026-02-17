<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class SensorController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function list()
    {
        try {
            // Query data dari PostgreSQL
            $data = DB::connection('pgsql')
                ->table('energy_consumption')
                ->orderBy('id')
                ->get();
            
            // Hitung statistik
            $stats = [
                'total_records' => $data->count(),
                'avg_power' => round($data->avg('power_watt'), 2),
                'max_energy' => $data->max('energy_kwh'),
                'peak_consumption' => $data->where('is_peak_hours', true)->count(),
                'total_energy' => round($data->sum('energy_kwh'), 2)
            ];
            
            return view('sensors.list', [
                'data' => $data,
                'stats' => $stats,
                'status' => 'success'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function listJson()
    {
        try {
            $data = DB::connection('pgsql')
                ->table('energy_consumption')
                ->orderBy('id')
                ->get();
            
            $stats = [
                'total_records' => $data->count(),
                'avg_power' => round($data->avg('power_watt'), 2),
                'max_energy' => $data->max('energy_kwh'),
                'peak_consumption' => $data->where('is_peak_hours', true)->count(),
                'total_energy' => round($data->sum('energy_kwh'), 2)
            ];
            
            return response()->json([
                'status' => 'success',
                'stats' => $stats,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
