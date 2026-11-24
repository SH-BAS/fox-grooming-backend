<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\UserResource;
use Illuminate\Validation\Rule;
use App\Mail\SendVerificationTokenMail;
use App\Mail\SendPasswordResetTokenMail;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->whereNull('deleted_at')
            ],
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        try {
            DB::beginTransaction();

            $user = User::create([
                'role_id' => 5, // User role
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);
            // 6 digit numeric token
            $token = rand(100000, 999999);
            DB::table('email_verification_tokens')->insert([
                'email' => $user->email,
                'token' => $token,
                'created_at' => now(),
            ]);

            // Send verification email
            Mail::to($user->email)->send(new SendVerificationTokenMail($user, $token));

            $user['verification_token'] = $token;
            DB::commit();

            return $this->success(new UserResource($user), 'User registered successfully. Please verify your email.');

        } catch (\Exception $e) {
            DB::rollBack();
            $errorCode = $e->errorInfo[1];

            if ($errorCode == 1062) {
                return $this->error('Email already exists', 409);
            }

            if ($errorCode == 1452) {
                return $this->error('Invalid reference id', 409);
            }

            if ($errorCode == 1451) {
                return $this->error('Cannot delete record because it is in use', 409);
            }

            return $this->error('Database error occurred', 500);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
            'fcm_token' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->error('Invalid credentials', 401);
        }

        if ($request->filled('fcm_token')) {
            $user->fcm_token = $request->fcm_token;
            $user->save();
        }

        $token = $user->createToken('AuthToken')->accessToken;
        $user['access_token'] = $token;
        return $this->success(new UserResource($user), 'Login successful');
    }

    public function verifyToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $verification = DB::table('email_verification_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$verification) {
            return $this->error('Invalid or expired token', 400);
        }

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->email_verified_at = now();
            $user->save();
        }

        DB::table('email_verification_tokens')->where('email', $request->email)->delete();

        return $this->success([], 'Email verified successfully');
    }

    public function resendVerificationToken(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->error('User not found', 404);
        }
        // 6 digit numeric token
        $token = rand(100000, 999999);
        DB::table('email_verification_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now()]
        );

        // Send verification email
        Mail::to($user->email)->send(new SendVerificationTokenMail($user, $token));

        return $this->success(['verification_token' => $token], 'Verification token sent');
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return $this->error('User not found', 404);
        }
        // randon 6digit numeric token
        $token = rand(100000, 999999);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => now()]
        );

        // Send password reset email
        Mail::to($user->email)->send(new SendPasswordResetTokenMail($user, $token));

        return $this->success(['reset_token' => $token], 'Password reset token sent on your email');
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->error('Validation Error', 422, $validator->errors());
        }

        $reset = DB::table('password_reset_tokens')->where('email', $request->email)->first();
        // check if token is valid
        if (!$reset || $reset->token != $request->token) {
            return $this->error('Invalid token', 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return $this->success([], 'Password has been reset successfully');
    }
}
