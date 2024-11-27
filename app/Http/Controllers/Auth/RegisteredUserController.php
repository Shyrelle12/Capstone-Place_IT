<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view for Space Owners.
     */
    public function create(): View
    {
        return view('auth.space-register');
    }

    public function createSpaceOwner(): View
    {
        return view('auth.space-register');
    }

    /**
     * Display the registration view for Business Owners.
     */
    public function createBusinessOwner(): View
    {
        return view('auth.business-register');
    }

    /**
     * Handle an incoming registration request for Space Owners.
     */
    public function storeSpaceOwner(Request $request): RedirectResponse
    {
        $this->validateRegistration($request);

        $user = User::create(array_merge(
            $request->only('firstName', 'lastName', 'email', 'mobileNumber'),
            ['role' => 'space_owner', 'password' => Hash::make($request->password), 'isVerified' => false]
        ));

        event(new Registered($user));
        Auth::login($user);

        // Set session flag for showing welcome modal
        session(['show_welcome_modal' => true]);


        return redirect()->route('otp.verify');
    }

    /**
     * Handle an incoming registration request for Business Owners.
     */
    public function storeBusinessOwner(Request $request): RedirectResponse
    {
        $this->validateRegistration($request);

        $user = User::create(array_merge(
            $request->only('firstName', 'lastName', 'email', 'mobileNumber'),
            ['role' => 'business_owner', 'password' => Hash::make($request->password), 'isVerified' => false]
        ));

        event(new Registered($user));
        Auth::login($user);

        // Set session flag for showing welcome modal
        session(['show_welcome_modal' => true]);

        return redirect()->route('otp.verify');
    }

    /**
     * Validate the registration request.
     */
    protected function validateRegistration(Request $request): void
    {
        $request->validate([
            'firstName' => ['required', 'string', 'max:255'],
            'lastName' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'mobileNumber' => [
                'required',
                'string',
                'max:20', // Maximum length can remain
                'regex:/^0[0-9]{10}$/', // Starts with 0, followed by 10 digits (total 11 digits)
                'unique:users'
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
    }


}
