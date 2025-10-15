<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function show($reservationId)
    {
        $reservation = Reservation::with('court')->findOrFail($reservationId);

        // Check if user owns this reservation
        if ($reservation->user_id !== auth()->id()) {
            abort(403);
        }

        return view('payments.show', compact('reservation'));
    }

    public function process(Request $request, $reservationId)
    {
        $validated = $request->validate([
            'payment_method' => 'required|in:card,wallet,paypal',
        ]);

        $reservation = Reservation::findOrFail($reservationId);

        if ($reservation->user_id !== auth()->id()) {
            abort(403);
        }

        // Simulate payment processing
        $payment = Payment::create([
            'reservation_id' => $reservation->id,
            'user_id' => auth()->id(),
            'amount' => $reservation->total_price,
            'method' => $validated['payment_method'],
            'status' => 'completed',
            'transaction_id' => 'TXN-' . strtoupper(Str::random(12)),
        ]);

        // Update reservation status
        $reservation->update(['status' => 'confirmed']);

        return redirect()->route('reservations.index')
            ->with('success', 'Payment successful! Your reservation is confirmed.');
    }
}