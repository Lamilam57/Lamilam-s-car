<?php
 

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class FacebookAuthController extends Controller
{
    public function redirect()
    {
        // DO NOT request email explicitly
        return Socialite::driver('facebook')->redirect();
    }

    public function callback()
{
    $facebookUser = Socialite::driver('facebook')->user();

    $email = $facebookUser->getEmail(); // may be null

    // Find user by facebook_id first
    $user = User::where('facebook_id', $facebookUser->getId())
        ->when($email, function ($query, $email) {
            return $query->orWhere('email', $email);
        })
        ->first();

    if (!$user) {
        // Create new user, email may be null
        $user = User::create([
            'name' => $facebookUser->getName() ?? 'Facebook User',
            'email' => $email, // can be null
            'facebook_id' => $facebookUser->getId(),
            'password' => bcrypt(Str::random(32)), // random password
        ]);
    } elseif (!$user->facebook_id) {
        $user->update([
            'facebook_id' => $facebookUser->getId(),
        ]);
    }

    Auth::login($user, true);

    return redirect()->intended('car');
}

} 


// namespace App\Http\Controllers\Auth;

// use App\Http\Controllers\Controller;
// use App\Models\User;
// use Illuminate\Support\Facades\Auth;
// use Laravel\Socialite\Facades\Socialite;

// class FacebookAuthController extends Controller
// {
//     // Redirect to Facebook
//     public function redirectToFacebook()
//     {
//         return Socialite::driver('facebook')->redirect();
//     }

//     // Handle callback from Facebook
//     public function handleFacebookCallback()
//     {
//         try {
//             $facebookUser = Socialite::driver('facebook')->user();

//             // Check if user exists
//             $user = User::where('email', $facebookUser->getEmail())->first();

//             if (!$user) {
//                 // Create user
//                 $user = User::create([
//                     'name' => $facebookUser->getName(),
//                     'email' => $facebookUser->getEmail(),
//                     'password' => bcrypt(uniqid()), // random password
//                 ]);
//             }

//             // Login user
//             Auth::login($user);

//             return redirect()->intended('car'); // redirect after login
//         } catch (\Exception $e) {
//             return redirect('/login')->with('error', 'Failed to login with Facebook.');
//         }
//     }
// }
