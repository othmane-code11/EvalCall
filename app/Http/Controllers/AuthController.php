<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function Showlogin()
    {
        return view('login');
    }
    public function dashboard()
    {
        return view('dashboard');
    }
    public function users()
    {
        $users = User::all();

        return view('users', compact('users'));
    }
    public function evaluations()
    {
        return view('evaluations');
    }
    public function createUser(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,manager,conseiller',
        ]);

        $name = $request->first_name . ' ' . $request->last_name;

        User::create([
            'name' => $name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users')->with('success', 'User created successfully.');
    }


    public function login(Request $request)
    {
      
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);
     $user = User::where('email', $request->email)->first();
    
     if ($user && Hash::check($request->password, $user->password)) {
        Auth::login($user);
        return redirect()->intended('users');
     }
     
     return redirect()->route('login')->with('error', 'Invalid email or password');
    }
}