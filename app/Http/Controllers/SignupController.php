<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SignupController extends Controller
{
    public function create(){
        return view('auth.signup');
    }

    public function postSignUp(Request $request){
        $validated =  $request->validate([
            'name' => ['required', 'string','max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6', 'confirmed'],
            'phone' => ['required', 'string'],
            'google_id' => ['nullable', 'string'],
            'facebook_id' => ['nullable', 'string'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'google_id' => $validated['google_id'] ?? null,
            'facebook_id' => $validated['facebook_id'] ?? null,
        ]);

        Auth::login($user);

        return redirect()
        ->route('login')
        ->with('success', 'Account created successfully. Please login.');
    }
}
