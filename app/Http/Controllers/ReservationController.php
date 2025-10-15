<?php

namespace App\Http\Controllers;

use App\Models\Court;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReservationController extends Controller
{
    public function index()
    {
        $courts = Court::where('is_active', true)->get();
        return view('reservations.index', compact('courts'));
    }

    public function getReservations(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));
        $userId = auth()->id();

        $reservations = Reservation::with(['court', 'user'])
            ->whereDate('start_time', $date)
            ->get()
            ->map(function ($reservation) use ($userId) {
                return [
                    'id' => $reservation->id,
                    'title' => $reservation->user_id == $userId ? 'Your Reservation' : 'Booked',
                    'start' => $reservation->start_time->format('Y-m-d H:i:s'),
                    'end' => $reservation->end_time->format('Y-m-d H:i:s'),

                    'resourceId' => $reservation->court_id,

                    'extendedProps' => [
                        'courtName' => $reservation->court->name,
                        'status' => $reservation->status,
                        'isOwn' => $reservation->user_id == $userId,
                    ],

                    'backgroundColor' => $reservation->user_id == $userId ? '#10b981' : '#ef4444',
                    'borderColor' => $reservation->user_id == $userId ? '#059669' : '#dc2626',
                ];
            });

        return response()->json($reservations);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'court_id' => 'required|exists:courts,id',
            'start_time' => 'required|date',
            'duration' => 'required|numeric|min:0.5|max:8',
        ]);

        $court = Court::findOrFail($validated['court_id']);
        $startTime = Carbon::parse($validated['start_time']);
        $endTime = $startTime->copy()->addHours($validated['duration']);

        // Check for conflicts
        $conflict = Reservation::where('court_id', $validated['court_id'])
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                    });
            })
            ->exists();

        if ($conflict) {
            return response()->json(['error' => 'Time slot already booked'], 422);
        }

        $totalPrice = $court->price_per_hour * $validated['duration'];

        $reservation = Reservation::create([
            'user_id' => auth()->id(),
            'court_id' => $validated['court_id'],
            'start_time' => $startTime,
            'end_time' => $endTime,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'reservation' => $reservation,
            'redirect' => route('payment.show', $reservation->id),
        ]);
    }
}