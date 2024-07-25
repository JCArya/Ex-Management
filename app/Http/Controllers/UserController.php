<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validate request
        try {
            $validatedData = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|confirmed',
                'phone' => 'required',
                'picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'gender' => 'required',
                'age' => 'required|integer'
            ]);

            // Handle file upload
            if ($request->hasFile('picture')) {
                $image = $request->file('picture');
                $imageName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('images'), $imageName);

                // Create new user
                $user = User::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'phone' => $request->phone,
                    'picture' => 'images/' . $imageName,
                    'gender' => $request->gender,
                    'age' => $request->age,
                ]);

                Auth::login($user);

                return redirect()->route('login')->with('success', 'Registration successful');
            } else {
                return back()->with('error', 'Picture upload failed');
            }
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {

            return back()->with('error', 'Internal Server Error')->withInput();
        }
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validate request
        try {
            $validatedData = $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if (Auth::attempt($request->only('email', 'password'))) {
                return redirect()->route('home')->with('success', 'Login successful');
            }

            return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {

            return back()->with('error', 'Internal Server Error')->withInput();
        }
    }

    public function logout(Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    
    return redirect('/')->with('message', 'Logged out successfully');
}
}
