<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login_page(){
        return view('auth.login');
    }

    public function register_page(){
        return view('auth.register');
    }

    public function login(Request $request){

        // dd($request->all())

        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
    
        if (FacadesAuth::attempt(['email' => $request->email, 'password' => $request->password])) {
            if(FacadesAuth::user()->role == 'admin'){
                return redirect()->route('user.index')->with('status', 'Login successful!');
            }else{
                return redirect('/user-tasks')->with('status', 'Login successful!');
            }
        }
        
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }


    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed', // This will check password_confirmation
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
    
        FacadesAuth::login($user);

        if(FacadesAuth::user()->role == 'admin'){
            return redirect()->route('user.index')->with('status', 'Registration successful!');
        }else{
            return redirect()->route('userpage.index')->with('status', 'Registration successful!!');
        }
    }

    public function regenerate(){
        return view('regenerate_password');
    }

}
