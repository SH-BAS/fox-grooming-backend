@component('emails.layouts.admin')
# Hello {{ $user->name }},

We received a password reset request for your account. Use the 6-digit code below to continue.

@component('mail::panel')
**{{ $token }}**
@endcomponent

<!-- @component('mail::button', ['url' => config('app.url') . '/reset-password?token=' . $token . '&email=' . urlencode($user->email)])
Reset Password
@endcomponent -->

**Expiry Warning:** This password reset code will expire in 60 minutes.

If you did not request a password reset, no further action is required.

**Security Notice:** Do not share this code with anyone. If you believe your account has been compromised, please contact support immediately.

Thanks,<br>
{{ config('app.name') }}
@endcomponent