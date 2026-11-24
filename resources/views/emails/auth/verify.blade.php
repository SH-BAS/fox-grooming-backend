@component('emails.layouts.admin')
# Hello {{ $user->name }},

Use the verification token below to verify your account.

@component('mail::panel')
**{{ $token }}**
@endcomponent

<!-- @component('mail::button', ['url' => config('app.url') . '/verify-email?token=' . $token . '&email=' . urlencode($user->email)])
Verify Account
@endcomponent -->

If you did not create an account, no further action is required.

**Security Notice:** Please do not share this token with anyone.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
