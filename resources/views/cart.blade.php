@extends('layouts.app')

@section('content')
<section class="container" style="padding-top: 8rem;">
    <h2 class="section-title">Your Shopping Cart</h2>
    
    @if(count($cart) > 0)
    <div style="background: white; padding: 2rem; border-radius: 8px; border: 1px solid var(--gold);">
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 2rem;">
            <thead>
                <tr style="border-bottom: 2px solid var(--gold); text-align: left;">
                    <th style="padding: 1rem;">Item</th>
                    <th style="padding: 1rem;">Price</th>
                    <th style="padding: 1rem;">Quantity</th>
                    <th style="padding: 1rem;">Subtotal</th>
                    <th style="padding: 1rem;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart as $id => $details)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 1rem;">{{ $details['name'] }}</td>
                    <td style="padding: 1rem;">Rp {{ number_format($details['price'], 0, ',', '.') }}</td>
                    <td style="padding: 1rem;">{{ $details['quantity'] }}</td>
                    <td style="padding: 1rem;">Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</td>
                    <td style="padding: 1rem;">
                        <form action="{{ route('cart.remove', $id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: none; border: none; color: var(--crimson); cursor: pointer; text-decoration: underline;">Remove</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="text-align: right;">
            <h3 style="font-size: 1.5rem; margin-bottom: 1rem;">Total: Rp {{ number_format($total, 0, ',', '.') }}</h3>
            <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                <a href="{{ route('home') }}" style="padding: 0.8rem 1.5rem; border: 1px solid var(--gold); border-radius: 4px; color: var(--crimson); text-decoration: none;">Continue Shopping</a>
                <a href="{{ route('checkout.index') }}" style="padding: 0.8rem 2rem; background: var(--crimson); color: white; border-radius: 4px; text-decoration: none; font-weight: bold;">Checkout</a>
            </div>
        </div>
    </div>
    @else
    <div style="text-align: center; padding: 4rem;">
        <p style="font-size: 1.2rem; margin-bottom: 2rem;">Your cart is empty.</p>
        <a href="{{ route('home') }}" style="padding: 1rem 2rem; background: var(--crimson); color: white; border-radius: 4px; text-decoration: none;">Go to Menu</a>
    </div>
    @endif
</section>
@endsection
