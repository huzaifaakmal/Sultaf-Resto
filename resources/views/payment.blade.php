@extends('layouts.app')

@section('content')
<section class="container" style="padding-top: 8rem; text-align: center;">
    <h2 class="section-title">Payment for Order #{{ $order->order_number }}</h2>
    
    <div style="max-width: 500px; margin: 0 auto; background: white; padding: 3rem; border-radius: 8px; border: 4px double var(--gold);">
        <h3 style="margin-bottom: 1rem;">Total Amount: <span style="color: var(--crimson);">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span></h3>
        <p style="margin-bottom: 2rem; color: #666;">Scan the QR code below to complete your payment.</p>
        
        <div style="background: #fdfdfd; padding: 2rem; border-radius: 8px; display: inline-block; border: 1px solid #eee; margin-bottom: 2rem;">
            <img src="{{ $qrUrl }}" alt="Payment QR Code" style="max-width: 100%; height: auto;">
        </div>

        <p style="font-style: italic; color: var(--gold); margin-bottom: 2rem;">Demo Version: Scan this with any QR reader to see order details.</p>

        <form action="{{ route('payment.simulate', $order->order_number) }}" method="POST">
            @csrf
            <button type="submit" style="padding: 1rem 2rem; background: #28a745; color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
                Simulate Payment Success (Demo Only)
            </button>
        </form>
    </div>
</section>
@endsection
