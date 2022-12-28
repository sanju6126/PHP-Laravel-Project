<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\support\Facades\Validator;    //this is for your own validation with your type of status code
use App\Models\User;    //for storing the user data to the Database table name Users
use Hash;              //for Hashing the Password while storing into the Database

class AuthController extends Controller
{
    public function register(Request $request){
        // $request->validate([
        //     'name' => 'required|min:2|max:100',
        //     'email' => 'required|email|unique:users',
        //     'password' => 'required|min:6|max:100',
        //     'confirm_password' => 'required|same:password'
        // ]);

        // return response()->json([
        //     'message' => 'Registration',
        // ]);

        $validator = validator::make($request->all(),[
            'phone' => 'required','digits:10','unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|max:100',
            'confirm_password' => 'required|same:password'
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Validation fails',
                'errors' => $validator->errors()
            ],422);
        }

        //creating user Record in the database
        $user = User::create([
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        //showing message to the user when Resgistration is successfull and returning the user data in the variable 
        return response()->json([
            'message'=>'Registration successfull',
            'data' => $user
        ],200);

        
    }


    public function login(Request $request){
        $validator = validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Validation fails',
                'errors' => $validator->errors()
            ],422);
        }

        //taking the user from the input field
        $user = User::where('email',$request->email)->first();

        //if user is present 
        if($user){
            //checking for the users Password
            if(Hash::check($request->password,$user->password)){
                $token=$user->createToken('auth-token')->plainTextToken;
                
                return response()->json([
                    'message'=>'Login Successfull',
                    'token'=>$token,
                    'data'=>$user
                ],200);

                
            }
            else{
                return response()->json([
                    'message'=>'Incorrect Credentials',
                ],400);
            }
        }
        else{
            return response()->json([
                'message'=>'Incorrect Credentials',
            ],400);
        }


    }
}
