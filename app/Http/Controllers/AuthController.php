<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function Showlogin()
    {
        return view('login');
    }

    public function login(Request $request)
    {
      
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);
     $user = User::where('email', $request->email)->first();
    
     if ($user && hash::check($request->password, $user->password)) {
        Auth::login($user);
        return redirect()->intended('users');
    
    
    return view('dashboard');
    }
}
}