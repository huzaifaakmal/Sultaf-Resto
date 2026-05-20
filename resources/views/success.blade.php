@extends('layouts.app')

@section('content')
<section class="container" style="padding-top: 10rem; text-align: center;">
    <div style="background: white; padding: 4rem; border-radius: 50%; display: inline-block; border: 4px double var(--gold); margin-bottom: 2rem;">
        <h1 style="color: #28a745; font-size: 3rem; margin-bottom: 1rem;">Success!</h1>
        <p style="font-size: 1.2rem; color: #666;">Order #{{ $order->order_number }}</p>
    </div>
    
    <h2 class="section-title">Thank You, {{ $order->customer_name }}!</h2>
    <p style="font-size: 1.5rem; margin-bottom: 3rem;">Your payment of <strong style="color: var(--crimson);">Rp {{ number_format($order->total_price, 0, ',', '.') }}</strong> has been received.</p>
    
    <div style="max-width: 600px; margin: 0 auto; background: #fdfdfd; padding: 2rem; border-radius: 8px; border: 1px solid var(--gold);">
        <p style="margin-bottom: 1rem;">We are now preparing your delicious meal. We will contact you at <strong>{{ $order->customer_phone }}</strong> when it's ready.</p>
        <a href="{{ route('home') }}" style="display: inline-block; margin-top: 1rem; padding: 1rem 2rem; background: var(--crimson); color: white; border-radius: 4px; text-decoration: none; font-weight: bold;">Return to Home</a>
    </div>
</section>
@endsection
