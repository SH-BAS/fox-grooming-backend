<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendPasswordResetTokenMail;
use App\Mail\SendVerificationTokenMail;
use Carbon\Carbon;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        if (!$user->email_verified_at) {
            return redirect()->route('web.verify.email', ['email' => $user->email])
                ->with('info', 'Please verify your email to login.');
        }

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('web.index'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,NULL,id,deleted_at,NULL',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'role_id' => 5, // Default user role
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);

            $otp = rand(100000, 999999);
            DB::table('email_verification_tokens')->updateOrInsert(
                ['email' => $user->email],
                ['token' => $otp, 'created_at' => now()]
            );

            Mail::to($user->email)->send(new SendVerificationTokenMail($user, $otp));

            DB::commit();

            return redirect()->route('web.verify.email', ['email' => $user->email])
                ->with('success', 'Registration successful. An OTP has been sent to your email.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Something went wrong. Please try again later.');
        }
    }

    public function showVerifyForm(Request $request)
    {
        $email = $request->email;
        if (!$email) {
            return redirect()->route('web.login');
        }
        return view('auth.verify', compact('email'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string|size:6',
        ]);

        $verification = DB::table('email_verification_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$verification) {
            return back()->with('error', 'Invalid or expired OTP.');
        }

        $user = User::where('email', $request->email)->first();
        if ($user) {
            $user->email_verified_at = now();
            $user->save();
        }

        DB::table('email_verification_tokens')->where('email', $request->email)->delete();

        return redirect()->route('web.login')->with('success', 'Email verified successfully. You can now login.');
    }

    public function resendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        $otp = rand(100000, 999999);
        DB::table('email_verification_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $otp, 'created_at' => now()]
        );

        Mail::to($user->email)->send(new SendVerificationTokenMail($user, $otp));

        return back()->with('success', 'A new OTP has been sent to your email.');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.passwords.forgot');
    }

    public function sendPasswordResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'We could not find an account with that email address.',
            ])->onlyInput('email');
        }

        $this->storePasswordResetToken($user);

        $request->session()->forget(['password_reset_email', 'password_reset_verified_at']);

        return redirect()->route('web.password.verify.form', ['email' => $user->email])
            ->with('success', 'A 6-digit password reset code has been sent to your email.');
    }

    public function showPasswordResetVerifyForm(Request $request)
    {
        $email = $request->email;

        if (!$email) {
            return redirect()->route('web.password.request');
        }

        return view('auth.passwords.verify', compact('email'));
    }

    public function verifyPasswordResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|digits:6',
        ]);

        $reset = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$reset) {
            return back()->with('error', 'Invalid reset code. Please try again.');
        }

        if (Carbon::parse($reset->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return back()->with('error', 'This reset code has expired. Please request a new one.');
        }

        $request->session()->put('password_reset_email', $request->email);
        $request->session()->put('password_reset_verified_at', now()->timestamp);

        return redirect()->route('web.password.reset.form')
            ->with('success', 'Code verified. You can now set a new password.');
    }

    public function resendPasswordResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'We could not find an account with that email address.',
            ]);
        }

        $this->storePasswordResetToken($user);

        $request->session()->forget(['password_reset_email', 'password_reset_verified_at']);

        return back()->with('success', 'A new 6-digit password reset code has been sent.');
    }

    public function showResetPasswordForm(Request $request)
    {
        if (!$this->hasVerifiedPasswordResetSession($request)) {
            $request->session()->forget(['password_reset_email', 'password_reset_verified_at']);

            return redirect()->route('web.password.request')
                ->with('error', 'Please verify your reset code before choosing a new password.');
        }

        $email = $request->session()->get('password_reset_email');

        return view('auth.passwords.reset', compact('email'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!$this->hasVerifiedPasswordResetSession($request) || $request->session()->get('password_reset_email') !== $request->email) {
            $request->session()->forget(['password_reset_email', 'password_reset_verified_at']);

            return redirect()->route('web.password.request')
                ->with('error', 'Please verify your reset code before resetting your password.');
        }

        $reset = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$reset || Carbon::parse($reset->created_at)->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            $request->session()->forget(['password_reset_email', 'password_reset_verified_at']);

            return redirect()->route('web.password.request')
                ->with('error', 'Your reset session has expired. Please request a new code.');
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            $request->session()->forget(['password_reset_email', 'password_reset_verified_at']);

            return redirect()->route('web.password.request')
                ->with('error', 'We could not find an account with that email address.');
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        $request->session()->forget(['password_reset_email', 'password_reset_verified_at']);

        return redirect()->route('web.login')
            ->with('success', 'Your password has been reset successfully. Please login with your new password.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('web.index');
    }

    protected function hasVerifiedPasswordResetSession(Request $request): bool
    {
        $email = $request->session()->get('password_reset_email');
        $verifiedAt = $request->session()->get('password_reset_verified_at');

        if (!$email || !$verifiedAt) {
            return false;
        }

        return Carbon::createFromTimestamp($verifiedAt)->addMinutes(60)->isFuture();
    }

    protected function storePasswordResetToken(User $user): void
    {
        $token = rand(100000, 999999);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            ['token' => $token, 'created_at' => now()]
        );

        Mail::to($user->email)->send(new SendPasswordResetTokenMail($user, $token));
    }
}
