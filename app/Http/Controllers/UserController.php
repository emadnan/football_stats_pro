<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Validator;

use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class UserController extends Controller
{
    function index(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password) && $user->status == 1) {
            return response([
                'message' => ['These credentials do not match our records.']
            ], 404);
        }

        // Check if the user status is active
        if ($user->status !== '1') {
            return response([
                'message' => ['Your account is not active. Please contact the administrator.']
            ], 403);
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
            return response()->json(['status_code' => 400, 'message' => "bad Request"], 422);
        }
    
        // Generate and save OTP
        $otp = $this->generateOTP();
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone_no = $request->phone_no;
        $user->password = bcrypt($request->password);
        $user->otp = $otp; // Save the generated OTP
        // $user->status = ''; // Set a temporary status for pending verification
        $user->otp_generated_at = now();
        $user->save();
    
        $fromAddress = env('MAIL_FROM_ADDRESS');
        Mail::to($user->email)->send(new OtpMail($otp, $user->first_name, $fromAddress));

        return response()->json([
            'status_code' => 200,
            'message' => "OTP sent to the registered phone number",
        ]);
    }
    
    public function getUserById($id)
    {
        $user = User::find($id);
    
        if (!$user) {
            return response()->json(['status_code' => 404, 'message' => "User not found"], 404);
        }
    
        return response()->json([
            'status_code' => 200,
            'user' => $user,
        ]);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['status_code' => 404, 'message' => "User not found"], 404);
        }

        // Validate the input data for the update
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone_no' => 'required',
            // 'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status_code' => 400, 'message' => "Bad Request"], 400);
        }

        // Update the user information
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->phone_no = $request->input('phone_no');
        // $user->password = bcrypt($request->input('password'));
        $user->save();
    
        return response()->json([
            'status_code' => 200,
            'message' => "User updated successfully",
            'user' => $user,
        ]);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['status_code' => 404, 'message' => "User not found"], 404);
        }

        // Delete the user
        $user->delete();

        return response()->json([
            'status_code' => 200,
            'message' => "User deleted successfully",
        ]);
    }

    public function verifyOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status_code' => 400, 'message' => "bad Request"], 422);
        }

        // Find the user by email and OTP
        $user = User::where('email', $request->email)->where('otp', $request->otp)->first();

        if (!$user) {
            return response()->json(['status_code' => 401, 'message' => "Invalid OTP"], 401);
        }

        // Check if OTP has expired (more than 2 minutes have passed)
        $otpExpirationTime = now()->subMinutes(2);
        if ($user->otp_generated_at < $otpExpirationTime) {
            return response()->json(['status_code' => 401, 'message' => "OTP has expired"], 401);
        }

        // Verify OTP and update user status
        $user->otp = null;
        $user->status = 1; // Set user status to active after OTP verification
        $user->save();

        return response()->json([
            'status_code' => 200,
            'message' => "OTP verification successful",
            'user' => $user,
        ]);
    }

    public function resendVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['status_code' => 400, 'message' => "Bad Request"], 422);
        }

        // Find the user by email
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['status_code' => 404, 'message' => "User not found"], 404);
        }

        // Check if the user is already verified
        if ($user->status == 1) {
            return response()->json(['status_code' => 400, 'message' => "User is already verified"], 400);
        }

        // Generate a new OTP
        $otp = $this->generateOTP();
        $user->otp = $otp;
        $user->otp_generated_at = now();
        $user->save();

        // Send the new OTP to the user's email
        $fromAddress = env('MAIL_FROM_ADDRESS');
        Mail::to($user->email)->send(new OtpMail($otp, $user->first_name, $fromAddress));

        return response()->json([
            'status_code' => 200,
            'message' => "New OTP sent to the registered email address",
        ]);
    }

    private function generateOTP()
    {
        return str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
    }

    public function logout(Request $request)
    {
        
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'status_code'=>200,
            'messge'=>"logout successfully"
        ]);
    }

    function getUsers()
    {
        $users = User::all();
        return response()->json(['users'=> $users]);
    }
}
