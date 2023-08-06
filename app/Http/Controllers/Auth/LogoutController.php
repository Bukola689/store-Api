<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function logout(Request $request, User $user) 
    {
        $user->tokens()->delete();

        return response()->json('Successfully logged out');
    }
}
