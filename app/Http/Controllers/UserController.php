<?php

namespace App\Http\Controllers;
use Stripe;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Support\Facades\DB;

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
    function forgetpassword()
    {

        $email = \Request::input('email');
        $otp = $this->generateOTP();
        $fromAddress = env('MAIL_FROM_ADDRESS');
        $userDetails = User::where('email', $email)->first();
        // print_r($userDetails);
        // exit();
        
        if (!empty($userDetails)) {
            $user_name = $userDetails->first_name;
            $user = DB::table('users')->where('email', $email)
            ->update(['otp' => $otp,
                'otp_generated_at' => now()]);
            Mail::to($email)->send(new OtpMail($otp, $user_name, $fromAddress));

            return response()->json([
                'status_code' => 200,
                'message' => "OTP sent to the registered Email"
            ]);
        }
        else{
            return response()->json(['invalid_email']);
        }
        

    }
    public function resetPassword($value='')
    {
        $Password = \Request::input('new_password');
        $newPAssword = bcrypt($Password);
        $email = \Request::input('email');
        $updatePassword = User::where('email', $email)
        ->update(['password' => $newPAssword]);
        return response()->json(['message'=>'updated']);
    }
    public function stripePost(Request $request)
    {
        // echo "<pre>";
        // print_r($request->all());
        // exit;
        // $subscription_type = $request->subscription_type;
        // $subscription = Subscription::where('name', $subscription_type)->first();
        $amount = 7.50 * 100;
        
        // $description = $subscription->name;

        //$api_key = env('APP_NAME');
        try {
            $api_key = env('STRIPE_SECRET');

            Stripe\Stripe::setApiKey($api_key);
        
            $stripe = Stripe\Charge::create([
                "payment_method_types" => "card",
                "amount" => $amount,
                "currency" => "eur",
                "source" => $request->stripeToken,
                // "description" => $description." Subcription from Cardify."
                // "metadata" => ["product_id" => "prod_Ni6iiqzPNgmWKe"]
            ]);
        
            // Handle successful charge here
            // ...
        
        } catch (Stripe\Exception\CardException $e) {
            // Handle card-related errors (e.g., card declined, insufficient balance)
            $error = $e->getError();
            $error_message = $error['message'];
            return response()->json(['error', $error_message]);
        
        } catch (Stripe\Exception\RateLimitException $e) {
            // Handle rate limit error
            $error_message = "Too many requests. Please try again later.";
            return response()->json(['error', $error_message]);
        
        } catch (Stripe\Exception\InvalidRequestException $e) {
            // Handle invalid request error
            $error_message = $e->getMessage();
            return response()->json(['error', $error_message]);
        
        } catch (Stripe\Exception\AuthenticationException $e) {
            // Handle authentication error
            $error_message = "Authentication with Stripe failed.";
           return response()->json(['error', $error_message]);
        
        } catch (Stripe\Exception\ApiConnectionException $e) {
            // Handle API connection error
            $error_message = "Network communication with Stripe failed.";
            return response()->json(['error', $error_message]);
        
        } catch (Stripe\Exception\ApiErrorException $e) {
            // Generic API error
            $error_message = "An error occurred while processing the payment.";
            return response()->json(['error', $error_message]);
        
        } catch (Exception $e) {
            // Catch any other unexpected exceptions
            $error_message = "An unexpected error occurred.";
            return response()->json(['error', $error_message]);
        }
        // echo "<pre>";
        // print_r($stripe);
        // exit;
        // $company = Company::find(auth()->user()->company_id);
        // $subscription_invoice_old = SubscriptionInvoice::where('company_id', $company->id)->where('is_active', 1)->first();
        // if($subscription_invoice_old)
        // {
        //     $subscription_invoice_old->is_active = 0;
        //     $subscription_invoice_old->save();
        // }
        // $subscription_invoice = new SubscriptionInvoice;
        // $subscription_invoice->company_id = $company->id;
        // $subscription_invoice->stripe_id = $stripe->id;
        // $subscription_invoice->subscription_id = 2;
        // $subscription_invoice->amount = 24;
        // $subscription_invoice->start_date = date('Y-m-d');
        // $subscription_invoice->end_date = date('Y-m-d', strtotime('+1 year'));
        // $subscription_invoice->is_active = 1;
        // $subscription_invoice->save();
        // $company->subscription_id = 2;
        // $company->save();
        // print_r('success');
        // exit();
        return response()->json(['success', 'Payment successful! Now you can Add Cards']);
    }
}
