<?php
namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Equipment;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with('equipment')
                                   ->orderBy('created_at', 'desc')
                                   ->get();
        return view('reservations.index', compact('reservations'));
    }

    public function create()
    {
        $equipment = Equipment::where('quantity_available', '>', 0)->get();
        return view('reservations.create', compact('equipment'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'equipment_id' => 'required|exists:equipment,id',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string'
        ]);

        $equipment = Equipment::findOrFail($request->equipment_id);
        
        // Check if enough quantity is available
        if ($request->quantity > $equipment->quantity_available) {
            return back()->withErrors(['quantity' => 'Not enough equipment available. Only ' . $equipment->quantity_available . ' left.'])
                         ->withInput();
        }

        // Check for date conflicts (simple availability check)
        $conflictingReservations = Reservation::where('equipment_id', $request->equipment_id)
            ->where('status', '!=', 'cancelled')
            ->where(function($query) use ($request) {
                $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                      ->orWhereBetween('end_date', [$request->start_date, $request->end_date])
                      ->orWhere(function($q) use ($request) {
                          $q->where('start_date', '<=', $request->start_date)
                            ->where('end_date', '>=', $request->end_date);
                      });
            })
            ->get();

        $totalRequestedQuantity = $conflictingReservations->sum('quantity') + $request->quantity;
        
        if ($totalRequestedQuantity > $equipment->quantity_available) {
            return back()->withErrors(['quantity' => 'Not enough equipment available for the selected dates.'])
                         ->withInput();
        }

        // Calculate total price
        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $days = $start->diffInDays($end) + 1;
        $totalPrice = $equipment->daily_rate * $request->quantity * $days;

        // Create reservation
        $reservation = Reservation::create([
            'equipment_id' => $request->equipment_id,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'quantity' => $request->quantity,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'notes' => $request->notes
        ]);

        return redirect()->route('reservations.show', $reservation)
                         ->with('success', 'Reservation created successfully!');
    }

    public function show(Reservation $reservation)
    {
        $reservation->load('equipment');
        return view('reservations.show', compact('reservation'));
    }

    public function edit(Reservation $reservation)
    {
        // Only allow editing pending reservations
        if ($reservation->status !== 'pending') {
            return redirect()->route('reservations.index')
                           ->with('error', 'Only pending reservations can be edited.');
        }

        $equipment = Equipment::all();
        return view('reservations.edit', compact('reservation', 'equipment'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        // Only allow updating pending reservations
        if ($reservation->status !== 'pending') {
            return redirect()->route('reservations.index')
                           ->with('error', 'Only pending reservations can be updated.');
        }

        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'notes' => 'nullable|string'
        ]);

        // Recalculate total price
        $equipment = $reservation->equipment;
        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $days = $start->diffInDays($end) + 1;
        $totalPrice = $equipment->daily_rate * $reservation->quantity * $days;

        $reservation->update([
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_price' => $totalPrice,
            'notes' => $request->notes
        ]);

        return redirect()->route('reservations.show', $reservation)
                         ->with('success', 'Reservation updated successfully!');
    }

    public function destroy(Reservation $reservation)
    {
        // Only allow deleting pending reservations
        if ($reservation->status !== 'pending') {
            return redirect()->route('reservations.index')
                           ->with('error', 'Only pending reservations can be deleted.');
        }

        $reservation->delete();
        return redirect()->route('reservations.index')
                         ->with('success', 'Reservation deleted successfully!');
    }

    // Additional methods for status management
    public function confirm(Reservation $reservation)
    {
        if ($reservation->status !== 'pending') {
            return back()->with('error', 'Reservation cannot be confirmed.');
        }

        $reservation->update(['status' => 'confirmed']);
        return back()->with('success', 'Reservation confirmed successfully!');
    }

    public function complete(Reservation $reservation)
    {
        if ($reservation->status !== 'confirmed') {
            return back()->with('error', 'Only confirmed reservations can be completed.');
        }

        $reservation->update(['status' => 'completed']);
        return back()->with('success', 'Reservation marked as completed!');
    }

    public function cancel(Reservation $reservation)
    {
        if (!in_array($reservation->status, ['pending', 'confirmed'])) {
            return back()->with('error', 'This reservation cannot be cancelled.');
        }

        $reservation->update(['status' => 'cancelled']);
        return back()->with('success', 'Reservation cancelled successfully!');
    }
}