<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Machine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index()
    {
        if (auth()->user()->role !== 'student') {
            abort(403);
        }
        $reservations = Auth::user()->reservations()->with('machine')->orderBy('reservation_date', 'desc')->orderBy('start_time', 'desc')->get();
        return view('reservations.index', compact('reservations'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role !== 'student') {
            abort(403);
        }

        $request->validate([
            'machine_id' => 'required|exists:machines,id',
            'reservation_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'garments_quantity' => 'required|integer|min:1|max:50',
        ]);

        $user = Auth::user();

        // RN02: Límite de reservas activas
        $activeReservations = $user->reservations()->whereIn('status', ['pending', 'in_progress'])->count();
        if ($activeReservations >= 3) {
            return back()->withErrors(['error' => 'No puedes tener más de 3 reservas activas.']);
        }

        // RN01: Reserva única por máquina y horario
        $conflict = Reservation::where('machine_id', $request->machine_id)
            ->where('reservation_date', $request->reservation_date)
            ->whereIn('status', ['pending', 'in_progress'])
            ->where(function ($query) use ($request) {
                // Check for overlapping time segments
                $query->where(function($q) use ($request) {
                    $q->where('start_time', '<', $request->end_time)
                      ->where('end_time', '>', $request->start_time);
                });
            })->exists();

        if ($conflict) {
            return back()->withErrors(['error' => 'La máquina seleccionada ya está reservada en ese horario.']);
        }

        Reservation::create([
            'user_id' => $user->id,
            'machine_id' => $request->machine_id,
            'reservation_date' => $request->reservation_date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'garments_quantity' => $request->garments_quantity,
            'status' => 'pending',
        ]);

        return redirect()->route('reservations.index')->with('success', 'Reserva creada exitosamente.');
    }

    public function destroy(Reservation $reservation)
    {
        if (auth()->user()->role !== 'student' || $reservation->user_id !== auth()->id()) {
            abort(403);
        }

        // RN03: Cancelación
        if ($reservation->status !== 'pending') {
            return back()->withErrors(['error' => 'Solo puedes cancelar reservas pendientes.']);
        }

        $now = Carbon::now();
        $reservationStart = Carbon::parse($reservation->reservation_date . ' ' . $reservation->start_time);

        if ($now->greaterThanOrEqualTo($reservationStart)) {
            return back()->withErrors(['error' => 'No puedes cancelar una reserva cuyo horario ya ha comenzado.']);
        }

        $reservation->update(['status' => 'cancelled']);

        return back()->with('success', 'Reserva cancelada exitosamente.');
    }
}
