<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ __('monthlyPRpageLang.consumemail')}}</title>
</head>

<body>
    <h1>{{ __('monthlyPRpageLang.consumemail')}}</h1>
    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
    <h3>http://172.22.255.22/month/testconsume?r={{$email}}&u={{$username}}</h3>
    <img src="{{ $message->embed(public_path() . '/admin/img/mail/email.png') }}" />

</body>

</html>
