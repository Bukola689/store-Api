<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AdminUserController extends Controller
{
    public function index()
    {

        if(! $users = User::with('roles')->get())
        {
            throw new NotFoundHttpException('User Not Found');
        }

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|min:3|max:15',
            'lastname' => 'required|string|min:3|max:15',
            'gender' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json([
                'error message' => 'invalid credentials'
            ]);

        }

            $user = new User;
            $user->firstname = $request->input('firstname');
            $user->lastname = $request->input('lastname');
            $user->gender = $request->input('gender');
            $user->email = $request->input('email');
            $user->active = true;
            $user->password = Hash::make($request->password);
            $user->save();
      

          return response()->json([
            'success' => 'User Created Successfully',
            'user' => $user
          ]);
    }

    public function show($id)
    {
        $user = User::find($id);

        if(! $user) {

           return response()->json('User not found');
         }

        return response()->json($user);
    }

    public function update(Request $request, $id)
     {
        $user = User::find($id);

        if(! $user) {
            throw new NotFoundHttpException('User Not found');
         }
 
         $validator = Validator::make($request->all(), [
             'firstname' => 'required|string|min:3|max:15',
             'lastname' => 'required|string|min:3|max:15',
             'gender' => 'required|string',
         ]);
 
         if($validator->fails()) {
             return response()->json([
                 'error message' => 'invalid credentials'
             ]);

         }
 
             $user->firstname = $request->firstname;
             $user->lastname = $request->lastname;
             $user->gender = $request->gender;
             $user->update();
       
 
           return response()->json([
             'success' => 'User Created Successfully'
           ]);
     }

     public function destroy($id)
     {
        $user = User::find($id);

        if(! $user) {
            throw new NotFoundHttpException('user not found');
         }

         $user->delete();

         return response()->json('user removed !');
     }

     public function suspend($id)
     {
        $user = User::find($id);

        if(! $user) {
            throw new NotFoundHttpException('user not found');
         }

         $user->active = false;
         $user->save();

         return response()->json([
            'message' => 'User Suspended Successfully'
         ]);
     }

     public function active($id)
     {

        $user = User::find($id);

        if(! $user) {
            throw new NotFoundHttpException('user not found');
         }

         $user->active = true;
         $user->save();

         return response()->json([
            'message' => 'User Been Active Successfully'
         ]);
     }
}
