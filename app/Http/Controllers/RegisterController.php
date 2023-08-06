<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\RegisterNotification;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(Request $request)
    {

        //$users = User::first();

        $user = $request->validate([
            'firstname' => 'required',
            'lastname' => 'required',
            'gender' => 'required',
            'email' => 'required',
            'password' => 'required' 
        ]);

        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'gender' => $request->gender,
            'active' => true,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $when = Carbon::now()->addSeconds(10);

        //Notification::sendNow($users, new RegisterNotification($request->first_name));

        //$user->verify((new RegisterNotification($user))->delay($when));

        event(new Registered($user));

        $token  = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user'=>$user,
            'token'=>$token,
        ];

        return response($response, 201);
    }
}
