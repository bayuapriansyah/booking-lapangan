@extends('layouts.app')

@section('title', 'Payment')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <a href="{{ route('reservations.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-6">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Reservations
        </a>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Header -->
            <div class="gradient-header px-8 py-6">
                <h1 class="text-3xl font-bold text-white">Complete Your Payment</h1>
                <p class="text-emerald-50 mt-1">Secure and fast payment processing</p>
            </div>

            <div class="p-8">
                <div class="grid md:grid-cols-2 gap-8">
                    <!-- Reservation Details -->
                    <div class="space-y-6">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 mb-4">Reservation Details</h2>
                            
                            <div class="bg-gray-50 rounded-xl p-6 space-y-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <p class="text-sm text-gray-500">Court</p>
                                        <p class="text-lg font-semibold text-gray-900">{{ $reservation->court->name }}</p>
                                    </div>
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-medium rounded-full">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </div>

                                <div class="border-t border-gray-200 pt-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-500">Date</p>
                                            <p class="font-semibold text-gray-900">
                                                {{ $reservation->start_time->format('M d, Y') }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-500">Time</p>
                                            <p class="font-semibold text-gray-900">
                                                {{ $reservation->start_time->format('H:i') }} - {{ $reservation->end_time->format('H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="border-t border-gray-200 pt-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-500">Duration</span>
                                        <span class="font-semibold text-gray-900">
                                            {{ $reservation->start_time->diffInMinutes($reservation->end_time) / 60 }} hours
                                        </span>
                                    </div>
                                    <div class="flex justify-between items-center mt-2">
                                        <span class="text-sm text-gray-500">Price per hour</span>
                                        <span class="font-semibold text-gray-900">
                                            ${{ number_format($reservation->court->price_per_hour, 2) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="border-t border-gray-200 pt-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-lg font-bold text-gray-900">Total Amount</span>
                                        <span class="text-3xl font-bold text-emerald-600">
                                            ${{ number_format($reservation->total_price, 2) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods -->
                    <div class="space-y-6">
                        <div>
                            <h2 class="text-xl font-bold text-gray-900 mb-4">Payment Method</h2>
                            
                            <form method="POST" action="{{ route('payment.process', $reservation->id) }}" id="paymentForm">
                                @csrf
                                
                                <div class="space-y-3">
                                    <!-- Card Payment -->
                                    <label class="payment-method-card block cursor-pointer">
                                        <input type="radio" name="payment_method" value="card" class="hidden peer" required>
                                        <div class="border-2 border-gray-200 rounded-xl p-6 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition hover:border-gray-300">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-4">
                                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-gray-900">Credit/Debit Card</p>
                                                        <p class="text-sm text-gray-500">Visa, Mastercard, Amex</p>
                                                    </div>
                                                </div>
                                                <div class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-emerald-500 peer-checked:bg-emerald-500 flex items-center justify-center">
                                                    <div class="w-2 h-2 bg-white rounded-full hidden peer-checked:block"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- Wallet Payment -->
                                    <label class="payment-method-card block cursor-pointer">
                                        <input type="radio" name="payment_method" value="wallet" class="hidden peer">
                                        <div class="border-2 border-gray-200 rounded-xl p-6 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition hover:border-gray-300">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-4">
                                                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-gray-900">Digital Wallet</p>
                                                        <p class="text-sm text-gray-500">Apple Pay, Google Pay</p>
                                                    </div>
                                                </div>
                                                <div class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-emerald-500 peer-checked:bg-emerald-500"></div>
                                            </div>
                                        </div>
                                    </label>

                                    <!-- PayPal Payment -->
                                    <label class="payment-method-card block cursor-pointer">
                                        <input type="radio" name="payment_method" value="paypal" class="hidden peer">
                                        <div class="border-2 border-gray-200 rounded-xl p-6 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition hover:border-gray-300">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-4">
                                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-400 rounded-lg flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                            <path d="M8.32 21.97a.546.546 0 01-.26-.32c-.03-.15-.01-.3.06-.44l2.13-5.39H7.99c-.58 0-.99-.55-.81-1.09l2.13-6.59c.07-.22.27-.37.5-.37h4.99c.58 0 .99.55.81 1.09l-1.76 5.42h2.18c.65 0 1.04.71.68 1.24l-6.89 8.13c-.2.23-.53.32-.84.23z"/>
                                                        </svg>
                                                    </div>
                                                    <div>
                                                        <p class="font-semibold text-gray-900">PayPal</p>
                                                        <p class="text-sm text-gray-500">Pay with PayPal account</p>
                                                    </div>
                                                </div>
                                                <div class="w-5 h-5 border-2 border-gray-300 rounded-full peer-checked:border-emerald-500 peer-checked:bg-emerald-500"></div>
                                            </div>
                                        </div>
                                    </label>
                                </div>

                                <button type="submit" id="payButton" 
                                        class="w-full mt-6 bg-emerald-500 text-white py-4 rounded-xl font-semibold hover:bg-emerald-600 transition shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                                    Complete Payment
                                </button>
                            </form>
                        </div>

                        <!-- Security Info -->
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                            <div class="flex items-start space-x-3">
                                <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <div>
                                    <p class="text-sm font-semibold text-blue-900">Secure Payment</p>
                                    <p class="text-xs text-blue-700 mt-1">Your payment information is encrypted and secure. We never store your card details.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('paymentForm').addEventListener('submit', function(e) {
    const button = document.getElementById('payButton');
    button.disabled = true;
    button.textContent = 'Processing Payment...';
});
</script>
@endpush