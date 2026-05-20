<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sultaf Restaurant - Vintage Arabic Dining</title>
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Aref+Ruqaa:wght@400;700&family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --crimson: #8B0000;
            --gold: #D4AF37;
            --dark-wood: #3D2B1F;
            --sand: #F4EBD0;
            --parchment: #FCF5E5;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--parchment);
            color: var(--dark-wood);
            line-height: 1.6;
        }

        h1, h2, h3 {
            font-family: 'Aref Ruqaa', serif;
            color: var(--crimson);
        }

        header {
            background-color: var(--dark-wood);
            color: var(--sand);
            padding: 1rem 0;
            border-bottom: 4px solid var(--gold);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        nav {
            display: flex;
            justify-content: space-around;
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .logo {
            font-size: 2rem;
            font-family: 'Aref Ruqaa', serif;
            color: var(--gold);
            text-decoration: none;
        }

        nav ul {
            display: flex;
            list-style: none;
        }

        nav ul li {
            margin: 0 1.5rem;
        }

        nav ul li a {
            color: var(--sand);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        nav ul li a:hover {
            color: var(--gold);
        }

        .hero {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('/restutant.jpg');
            background-size: cover;
            background-position: center;
            height: 80vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: var(--sand);
            border-bottom: 10px double var(--gold);
        }

        .hero h1 {
            font-size: 4rem;
            color: var(--gold);
            margin-bottom: 1rem;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
        }

        .hero p {
            font-size: 1.5rem;
            max-width: 600px;
            font-family: 'Amiri', serif;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 4rem 1rem;
        }

        .section-title {
            text-align: center;
            font-size: 3rem;
            margin-bottom: 3rem;
            position: relative;
        }

        .section-title::after {
            content: "❧";
            display: block;
            font-size: 1.5rem;
            color: var(--gold);
            margin-top: 0.5rem;
        }

        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .menu-item {
            background: white;
            border: 1px solid var(--gold);
            padding: 1.5rem;
            border-radius: 8px;
            box-shadow: 5px 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s;
            position: relative;
            overflow: hidden;
        }

        .menu-item::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: var(--gold);
        }

        .menu-item:hover {
            transform: translateY(-10px);
        }

        .menu-item h3 {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
        }

        .menu-item .price {
            color: var(--crimson);
            font-weight: bold;
            font-size: 1.2rem;
            margin-bottom: 1rem;
            display: block;
        }

        .menu-item p {
            font-family: 'Amiri', serif;
            font-size: 1.1rem;
        }

        footer {
            background-color: var(--dark-wood);
            color: var(--sand);
            text-align: center;
            padding: 3rem 1rem;
            margin-top: 4rem;
            border-top: 4px solid var(--gold);
        }

        .arabic-pattern {
            height: 50px;
            background-image: radial-gradient(var(--gold) 2px, transparent 2px);
            background-size: 20px 20px;
            opacity: 0.2;
        }

        @media (max-width: 768px) {
            .hero h1 { font-size: 2.5rem; }
            nav { flex-direction: column; }
            nav ul { margin-top: 1rem; }
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="#" class="logo">Sultaf Restaurant</a>
            <ul>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('home') }}#menu">Menu</a></li>
                <li><a href="{{ route('cart.index') }}" style="color: var(--gold);">Cart ({{ count(session('cart', [])) }})</a></li>
                <li><a href="{{ route('home') }}#about">About</a></li>
                <li><a href="{{ route('home') }}#contact">Contact</a></li>
            </ul>
        </nav>
    </header>

    @yield('content')

    <footer>
        <p>&copy; 2026 Sultaf Restaurant. Experience the Essence of Arabia.</p>
        <p style="margin-top: 1rem; font-family: 'Amiri', serif;">Authentic Flavors | Vintage Ambience | Traditional Hospitality</p>
    </footer>
</body>
</html>