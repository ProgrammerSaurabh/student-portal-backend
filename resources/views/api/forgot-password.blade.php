@component('mail::message')
# Hello!

You are receiving this email because we received a password reset request for your
account.

@component('mail::button', ['url' => $url])
Reset Password
@endcomponent

If you did not request a password reset, no further action is required.

Thanks,<br>
{{ config('app.name') }}

If you're having trouble clicking the "Reset Password" button, copy and paste the URL below into your web browser:
<br />
<a href="{{ $url }}" target="_blank">{{ $url }}</a>
@endcomponent