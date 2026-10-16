<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.index', ['tab' => 'login']);
    }

    public function showRegister(): View
    {
        return view('auth.index', ['tab' => 'register']);
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withErrors(['email' => __('Invalid email or password.')])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        $user = Auth::user();
        return redirect()->intended($this->redirectBasedOnRole($user->role));
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'username' => Str::slug($validated['name']) . '-' . rand(1000, 9999),
            'email'    => $validated['email'],
            'no_hp'    => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role'     => 'member',
        ]);

        Auth::login($user);

        return redirect()->route('menu.index');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('menu.index');
    }

    protected function redirectBasedOnRole(?string $role): string
    {
        $cleanRole = strtolower(trim($role ?? ''));

        $roleRoutes = [
            'admin' => 'admin.dashboard',
            'owner' => 'owner.dashboard',
            'kasir' => 'kasir.dashboard',
            'dapur' => 'dapur.dashboard',
        ];

        if (isset($roleRoutes[$cleanRole]) && \Illuminate\Support\Facades\Route::has($roleRoutes[$cleanRole])) {
            return route($roleRoutes[$cleanRole]);
        }

        return route('menu.index');
    }
}