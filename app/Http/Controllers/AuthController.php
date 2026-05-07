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
      
    // Validate input
    $validated = $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:3',
    ]);

    $user = User::where('email', $request->email)->first();
    
    if ($user && Hash::check($request->password, $user->password)) {
        Auth::login($user);
        
        // Check if it's an AJAX request
        if ($request->wantsJson()) {
            $token = $user->createToken('auth_token')->plainTextToken;
            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => $user,
                    'token' => $token
                ]
            ]);
        }
        
        return redirect()->intended('dashboard');
     }
     
     // Check if it's an AJAX request
     if ($request->wantsJson()) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid email or password',
            'errors' => [
                'general' => ['Invalid email or password']
            ]
        ], 401);
     }
     
     return redirect()->route('dashboard')->with('error', 'Invalid email or password');
    }
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users')->with('success', 'User deleted successfully.');
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|string|in:admin,manager,conseiller',
        ]);

        $name = $request->first_name . ' ' . $request->last_name;

        $updateData = [
            'name' => $name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('users')->with('success', 'User updated successfully.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login.page');
    }
}