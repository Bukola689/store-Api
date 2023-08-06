<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function updateProfile(Request $request)
    {

        $data = $request->validate([

           'first_name' => 'required',
           'last_name' => 'required',
           'image' => 'required',
           'phone_number' => 'required|int|max:11',
           'country' => 'required',
           'state' => 'required',
           'city' => 'required',

        ]);

      // $this->profile->updateProfile($request, $data);

       cache()->forget('user:all');

        return response()->json([
            'message' => 'profile updated Successfully',
        ]);
    }

    public function changePassword(Request $request)
    {
       $data = $request->validate([
        "old_password" => "required",
        "password" => "required",
        "confirm_password" => "required"
       ]);

     // $profile = $this->profile->changePassword($request, $data);

      cache()->forget('profile:all');

    //   if($profile)
    //   {
        return response()->json([
            'message'=> 'Password Updated Successfully',
           ], 200);
    //   } else 
    //   {
    //     return response()->json([
    //         'message'=> 'Password does not match',
    //        ], 401);
    //   }
       

    }
}
