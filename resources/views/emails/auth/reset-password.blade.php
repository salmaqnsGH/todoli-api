<x-mail::message>
# Reset Your Password
Hello {{ $user->first_name }},
Please click the button below to reset your password.
<x-mail::button :url="$url">
Reset Password
</x-mail::button>
If you did not request a password reset, no further action is required.
Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
