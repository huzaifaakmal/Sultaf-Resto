@extends('layouts.app')

@section('content')
<section class="container" style="padding-top: 8rem;">
    <h2 class="section-title">Checkout</h2>
    
    <div style="display: flex; gap: 2rem; flex-wrap: wrap;">
        <!-- Checkout Form -->
        <div style="flex: 2; min-width: 300px; background: white; padding: 2rem; border-radius: 8px; border: 1px solid var(--gold);">
            <h3 style="margin-bottom: 1.5rem;">Customer Information</h3>
            <form action="{{ route('checkout.process') }}" method="POST">
                @csrf
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Full Name</label>
                    <input type="text" name="customer_name" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                <div style="margin-bottom: 2rem;">
                    <label style="display: block; margin-bottom: 0.5rem;">Phone Number (WhatsApp)</label>
                    <input type="text" name="customer_phone" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 4px;">
                </div>
                
                <button type="submit" style="width: 100%; padding: 1rem; background: var(--crimson); color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
                    Place Order & Pay with QR
                </button>
            </form>
        </div>

        <!-- Order Summary -->
        <div style="flex: 1; min-width: 250px; background: #f9f9f9; padding: 2rem; border-radius: 8px; border: 1px solid var(--gold); height: fit-content;">
            <h3 style="margin-bottom: 1.5rem;">Order Summary</h3>
            @foreach($cart as $id => $details)
            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem;">
                <span>{{ $details['name'] }} x {{ $details['quantity'] }}</span>
                <span>Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</span>
            </div>
            @endforeach
            <hr style="margin: 1rem 0; border: none; border-top: 1px solid var(--gold);">
            <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 1.2rem;">
                <span>Total</span>
                <span style="color: var(--crimson);">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>
</section>
@endsection
