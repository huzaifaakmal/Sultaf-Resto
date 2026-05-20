@extends('layouts.app')

@section('content')
<section class="hero" id="home">
    <h1>Welcome to Sultaf</h1>
    <p>A journey through time and taste. Discover the rich culinary heritage of the Arab world in a vintage, welcoming atmosphere.</p>
</section>

<div class="arabic-pattern"></div>

<section class="container" id="menu">
    <h2 class="section-title">Our Featured Menu</h2>
    <div class="menu-grid">
        @foreach($menuItems as $item)
            <div class="menu-item" style="display: flex; flex-direction: column; justify-content: space-between; padding: 0;">
                @if($item->image)
                    <img src="{{ $item->image }}" alt="{{ $item->name }}" style="width: 100%; height: 200px; object-fit: cover; border-bottom: 2px solid var(--gold);">
                @endif
                <div style="padding: 1.5rem;">
                    <h3>{{ $item->name }}</h3>
                    <span class="price">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                    <p>{{ $item->description }}</p>
                    <div style="margin-top: 1rem; font-style: italic; color: var(--gold); margin-bottom: 1rem;">Category: {{ $item->category }}</div>
                
                    <form action="{{ route('cart.add', $item->id) }}" method="POST" style="margin-top: auto;">
                        @csrf
                        <div style="display: flex; gap: 0.5rem; align-items: center;">
                            <input type="number" name="quantity" value="1" min="1" style="width: 60px; padding: 0.5rem; border: 1px solid var(--gold); border-radius: 4px; background: transparent; color: var(--crimson); font-weight: bold;">
                            <button type="submit" style="flex: 1; background-color: var(--crimson); color: white; border: none; padding: 0.6rem; border-radius: 4px; cursor: pointer; font-family: 'Aref Ruqaa', serif; font-size: 1.1rem; transition: background 0.3s;">
                                Add to Cart
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</section>

<section class="container" id="about" style="background-color: white; border-top: 1px solid var(--gold); border-bottom: 1px solid var(--gold);">
    <div style="display: flex; align-items: center; gap: 4rem; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 300px;">
            <img src="/logo.jpg" alt="Sultaf Logo" style="width: 100%; border-radius: 8px; border: 4px double var(--gold);">
        </div>
        <div style="flex: 2; min-width: 300px;">
            <h2 style="font-size: 2.5rem; margin-bottom: 1.5rem;">The Sultaf Legacy</h2>
            <p style="margin-bottom: 1rem;">Founded on the principles of traditional hospitality, Sultaf Restaurant brings the authentic flavors of the Middle East to your table. Our recipes have been passed down through generations, ensuring every bite is a piece of history.</p>
            <p>From the smokey aroma of our Mandi to the delicate sweetness of our Baklava, we use only the finest ingredients and traditional cooking methods to provide an unforgettable dining experience.</p>
        </div>
    </div>
</section>

<section class="container" id="contact" style="text-align: center;">
    <h2 class="section-title">Visit Us</h2>
    <p style="font-size: 1.2rem; margin-bottom: 2rem;">Experience the magic in person. We are open daily from 11:00 AM to 11:00 PM.</p>
    <div style="display: inline-block; padding: 2rem; border: 2px solid var(--crimson); border-radius: 50%; font-family: 'Aref Ruqaa', serif; color: var(--crimson);">
        <p>Jalan Malioboro No. 123</p>
        <p>Kotagede, Yogyakarta</p>
        <p>Phone: +62 812 3456 7890</p>
    </div>
</section>
@endsection