<x-mail::message>
# Hello, Sir/Madam!

We are excited to share this message with you.

## Your Special Code
Here is your unique code:  
**{{ $details['code'] }}**

Please use this code for verification or to access your account.

<x-mail::button :url="$details['url']">
Verify Now
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
