<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\LoginNeedsVerification;

class LoginController extends Controller
{

   
     public function submit(Request $request)
     {
        //validate phone no
        $request->validate([
           'email'=> 'required|email'
        ]);

        //find or  create a user model
        $user = User::firstOrCreate([
            'email' =>$request->email
        ]);

       
        if(!$user){
            return response()->json(['message'=>'Could not process a user with provided email'],401);
        }

        //send the user  a one-time use code
        $user->notify(new LoginNeedsVerification());
  
        //return back a response
        return response()->json(['message'=>'Verification Code to your email is sent']);
     
     }

     public function verify(Request $request)
     {
        //validate the incoming request
        $request -> validate([
            'email'=>'required|email',
            'login_code'=>'required|numeric|between:111111,999999'
        ]);


        //find the user
        $user =User::where('email',$request->email)
        ->where('login_code',$request->login_code)
        ->first();


     

        //if so,return back an auth token
        if($user){
            $user->update([
                'login_code'=> null
            ]);
            return $user->createToken($request->login_code)->plainTextToken;

        }

        //if not,return back a message
        return response()->json(['message'=>'Invalid Verification code'],401);

     }
}
