<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        return view("auth.login");
    }

    public function register()
    {
        return view("auth.register");
    }

    public function logout() {
    	Auth::logout();
	    return redirect('/');
    }

    public function doLogin(Request $request)
    {
        Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')]);
	    return redirect('/');
    }

    public function doRegister(Request $request)
    {
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();
        Auth::loginUsingId($user->id);
	    return redirect('/');
    }
}
