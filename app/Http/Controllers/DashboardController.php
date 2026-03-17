<?php
namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Reservation;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Equipment
        $totalEquipment = Equipment::count();
        
        // Available Equipment (quantity_available > 0)
        $availableEquipment = Equipment::where('quantity_available', '>', 0)->count();
        
        // Active Reservations (pending and confirmed)
        $activeReservations = Reservation::whereIn('status', ['pending', 'confirmed'])->count();
        
        // Monthly Revenue
        $monthlyRevenue = Reservation::where('status', 'completed')
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('total_price');
        
        // Recent Reservations
        $recentReservations = Reservation::with('equipment')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Popular Equipment (most reserved)
        $popularEquipment = Equipment::withCount('reservations')
            ->orderBy('reservations_count', 'desc')
            ->limit(5)
            ->get();
        
        return view('dashboard', compact(
            'totalEquipment',
            'availableEquipment',
            'activeReservations',
            'monthlyRevenue',
            'recentReservations',
            'popularEquipment'
        ));
    }
}