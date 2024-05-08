<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ __('monthlyPRpageLang.standemail') }}</title>
</head>

<body>
    <h1>{{ __('monthlyPRpageLang.standmail') }} By {{ urldecode($name) }}</h1>
    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
    <h2><a
            href="{{ urldecode($app_url) }}/month/teststand?r={{ $email }}&u={{ $username }}&d={{ $database }}">{{ __('monthlyPRpageLang.clickmail') }}</a>
    </h2>
    <img src="{{ $message->embed(public_path() . '/admin/img/mail/email.png') }}" />

</body>

</html>
