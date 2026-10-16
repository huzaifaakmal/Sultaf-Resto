@extends('layouts.app')

@section('content')
<section class="container" style="padding-top: 10rem; padding-bottom: 5rem;">
    <div style="max-width: 400px; margin: 0 auto; background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: 1px solid var(--gold);">
        <h2 style="text-align: center; color: var(--crimson); margin-bottom: 2rem;">Admin Login</h2>

        @if($errors->any())
            <div style="background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 4px; margin-bottom: 1rem;">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; margin-bottom: 0.5rem;">Username</label>
                <input type="text" name="username" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <div style="margin-bottom: 2rem;">
                <label style="display: block; margin-bottom: 0.5rem;">Password</label>
                <input type="password" name="password" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 4px;">
            </div>
            <button type="submit" style="width: 100%; padding: 1rem; background: var(--crimson); color: white; border: none; border-radius: 4px; font-weight: bold; cursor: pointer;">
                Login
            </button>
        </form>
    </div>
</section>
@endsection
