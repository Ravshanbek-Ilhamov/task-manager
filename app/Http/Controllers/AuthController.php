<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
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

    public function login(LoginRequest $request){
    
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


    public function register(StoreUserRequest $request)
    {

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

    public function reset_page(){
        return view('user.reset_user');
    }

    public function user_update(UpdateUserRequest $request, $id){

        $user = User::findOrFail($id);
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->save();

        return redirect()->route('reset.page')->with('success', 'User updated successfully!');
    }

}
