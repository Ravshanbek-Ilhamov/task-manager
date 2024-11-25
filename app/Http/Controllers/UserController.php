<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Jobs\SendEmailJob;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
        $users = User::paginate(10);
        return view('user.user',['users'=>$users]);
    }


    public function store(UserStoreRequest $request)
    {    
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);
    
        return redirect()->back()->with('success', 'User created successfully!');
    }

    public function edit($id){
        $user = User::findOrFail($id);
        return view('user.user_edit', compact('user'));
    }

    public function update(UserUpdateRequest $request, $id)
    {
    
        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);
    
        return redirect()->route('user.index')->with('success', 'User updated successfully!');
    }

    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->route('user.index')->with('success', 'User deleted successfully.');
    }

    public function logout() {
        FacadesAuth::logout();
    
        session()->invalidate();
        session()->regenerateToken();
    
        return redirect()->route('login.index')->with('success', 'Logged out successfully.');
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);
    
        $code = rand(100000, 999999);
    
        $user = User::where('email', $request->email)->first();
    
        if ($user) {
            $user->update([
                'password' => Hash::make($code), 
            ]);
    
            $details = [
                'email' => $request->email,
                'code' => $code,
            ];
    
            SendEmailJob::dispatch($details);
    
            return redirect('/')->with('success', 'Message has been sent to your email.');
        } else {
            return redirect('/')->with('error', 'Error occurred while trying to send the code.');
        }
    }

    public function resetting_page(){
        return view('auth.forgot-password');
    }
}
