<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class AdminReservationController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'staff') {
            abort(403);
        }
        $reservations = Reservation::with(['user', 'machine'])->orderBy('reservation_date', 'desc')->orderBy('start_time', 'desc')->get();
        return view('admin.reservations.index', compact('reservations'));
    }

    public function update(Request $request, Reservation $reservation)
    {
        if (auth()->user()->role !== 'staff') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled'
        ]);

        $reservation->update(['status' => $request->status]);

        return back()->with('success', 'Estado de la reserva actualizado.');
    }
}
