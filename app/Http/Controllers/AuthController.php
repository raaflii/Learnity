<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login'); 
    }

    public function showRegisterForm()
    {
        return view('auth.signup'); 
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'password.required' => 'Password is required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account is inactive.'])->withInput($request->except('password'));
            }

            $request->session()->regenerate();

            $role = $user->role->name;

            return match ($role) {
                'admin' => redirect()->route('admin.dashboard'),
                'teacher' => redirect()->route('pengajar.dashboard'),
                'student' => redirect()->route('dashboard'),
                default => redirect('/'),
            };
        }

        return back()->withErrors([
            'email' => 'Invalid email or password. Please try again',
        ])->withInput($request->except('password'));
    }

    public function register(Request $request)
{
    $validated = $request->validate([
        'first_name' => 'required|max:100',
        'last_name' => 'required|max:100',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
    ], [
        'first_name.required' => 'First name is required.',
        'first_name.max' => 'First name must not exceed 100 characters.',
        'last_name.required' => 'Last name is required.',
        'last_name.max' => 'Last name must not exceed 100 characters.',
        'email.required' => 'Email is required.',
        'email.email' => 'Please enter a valid email address.',
        'email.unique' => 'This email is already registered.',
        'password.required' => 'Password is required.',
        'password.min' => 'Password must be at least 8 characters.',
        'password.confirmed' => 'Password confirmation does not match.',
    ]);

    try {
        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => Role::where('name', 'student')->value('id'),
            'is_active' => true,
        ]);

        Auth::login($user);

        return redirect()->route('dashboard');
    } catch (\Exception $e) {
        return back()->withErrors(['general' => 'Registration failed. Please try again.'])->withInput();
    }
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing-page');
    }
}
