<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Validator;

class UserController extends Controller
{
    function index(Request $request)
    {
        $user= User::where('email', $request->email)->first();
        // print_r($data);
            if (!$user || !Hash::check($request->password, $user->password)) {
                return response([
                    'message' => ['These credentials do not match our records.']
                ], 404);
            }
        
             $token = $user->createToken('my-app-token')->plainTextToken;
        
            $response = [
                'user' => $user,
                'token' => $token
            ];
        
             return response($response, 201);
    }
    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'phone_no' => 'required', 
            'password' => 'required',  
        ]);
if ($validator->fails()) { 
            return response()->json(['status_code'=>400, 'message'=>"bad Request"],422);            
        }

        $user = new User();
        $user->first_name = $request ->first_name;
        $user->last_name = $request ->last_name;
        $user->email = $request ->email;
        $user->phone_no = $request->phone_no;
        $user->password = bcrypt($request->password);
        // $user->role_id = 8;
        $user->save();

        return response()->json([
            'message'=>"user created Successfully"
        ]);

        }
        public function logout(Request $request){
            
            $request->user()->currentAccessToken()->delete();
            return response()->json([
                'status_code'=>200,
                'messge'=>"Token deleted successfully"
            ]);
        }
}
