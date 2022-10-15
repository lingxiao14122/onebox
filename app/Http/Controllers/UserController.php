<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index()
    {
        return view('users.index', ['users' => User::all()]);
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $formFields = $request->validate([
            "name" => "required|min:3",
            "email" => "required|email|unique:users",
            "password" => ["required", "confirmed", Password::min(8)->letters()->numbers()],
            "role" => "required|in:admin,user"
        ]);

        $formFields['password'] = bcrypt($formFields['password']);
        $formFields['is_admin'] = $formFields['role'] == 'admin' ? true : false;

        User::create($formFields);

        return redirect('/user');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(User $user, Request $request)
    {
        $formFields = $request->validate([
            "name" => "required|min:3",
            "email" => "required|email",
            "password" => "nullable|confirmed|min:6",
            "role" => "required|in:admin,user"
        ]);

        $formFields['password'] = bcrypt($formFields['password']);
        $formFields['is_admin'] = $formFields['role'] == 'admin' ? true : false;

        $user->update($formFields);

        return redirect('/user');
    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('messsage', 'You have been logged out');
    }

    public function login()
    {
        return view('users.login');
    }

    public function authenticate(Request $request)
    {
        $formFields = $request->validate([
            "email" => "required|email",
            "password" => "required",
        ]);

        if (auth()->attempt($formFields)) {
            $request->session()->regenerate();

            return redirect('/')->with('message', 'You are now logged in');
        }

        return back()->withErrors(['email' => 'Wrong email or password'])->onlyInput('email');
    }
}
