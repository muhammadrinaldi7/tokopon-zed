<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleCallbackController extends Controller
{
    /**
     * Redirect user to Google OAuth consent screen.
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle callback from Google OAuth.
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Gagal login dengan Google: ' . $e->getMessage());
        }

        // Check if user already exists by email
        $existingUser = User::where('email', $googleUser->getEmail())->first();

        if ($existingUser) {
            // User exists — link Google account if not already linked
            if (!$existingUser->provider) {
                $existingUser->update([
                    'provider' => 'google',
                    'provider_id' => $googleUser->getId(),
                    'avatar' => $googleUser->getAvatar(),
                ]);
            }

            Auth::login($existingUser, remember: true);
        } else {
            // Create new user with Google data
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'provider' => 'google',
                'provider_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => bcrypt(Str::random(24)),
            ]);

            // Assign 'user' role to new Google registrations
            $user->assignRole('user');

            Auth::login($user, remember: true);
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Redirect based on role
        if ($user->roles->count() > 0 && !$user->hasRole('user')) {
            return redirect()->intended('/admin/dashboard');
        }

        return redirect()->intended('/');
    }
}
