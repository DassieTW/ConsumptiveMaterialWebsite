<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    {{--
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords"
        content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">
    --}}

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="../admin/img/icons/icon-48x48.png" />

    <link rel="stylesheet" type="text/css" href="{{ asset('/css/bootstrap.min.css?v=') . env('APP_VERSION') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/app.css?v=') . env('APP_VERSION') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . env('APP_VERSION') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('/css/jquery.loadingModal.min.css?v=') . env('APP_VERSION') }}">
    <script src="{{ asset('/js/jquery.loadingModal.min.js?v=') . env('APP_VERSION') }}"></script>
    {{-- local lang for js --}}
    <script src="{{ asset('/messages.js?v=') . env('APP_VERSION') }}"></script>
    {{-- for date picker's js --}}
    <script type="text/javascript" href="{{ asset('/js/moment.min.js?v=') . env('APP_VERSION') }}"></script>
    {{-- get parameters from url, js version --}}
    {{-- usage example : var temp = getUrlParameter('parameterName'); --}}
    {{-- <script type="module" src="{{ asset('/js/getUrlParameter.js?v=') . env('APP_VERSION') }}"></script> --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    {{-- checkbox on top left of picture --}}
    <style>
        .checkboxContainer {
            position: relative;
            width: 70px;
            height: 70px;
            float: left;
        }

        .checkboxOnPic {
            position: absolute;
            top: 0px;
            left: 0px;
        }
    </style>
    <script>
        if (window.history.replaceState) {
            // java script to prvent "confirm form resubmission" dialog
            // 避免重新整理這個頁面時跳出要你重新提交表單的對話框
            // (避免重新提交表單)
            window.history.replaceState(null, null, window.location.href);
        } // if

        document.addEventListener("readystatechange", event => {
            switch (document.readyState) {
                case "loading":
                    // document is loading
                case "interactive":
                    // document has finish loading DOM
                    $("body").loadingModal({
                        text: "Loading...",
                        animation: "circle",
                    });
                    break;
                case "complete":
                    // the page DOM with sub-resources are now fully loaded.
                    break;
            } // switch
        });
        /**

                                                             __----~~~~~~~~~~~------___
                                            .  .   ~~//====......          __--~ ~~
                            -.            \_|//     |||\\  ~~~~~~::::... /~
                         ___-==_       _-~o~  \/    |||  \\            _/~~-
                 __---~~~.==~||\=_    -_--~/_-~|-   |\\   \\        _/~
             _-~~     .=~    |  \\-_    '-~7  /-   /  ||    \      /
           .~       .~       |   \\ -_    /  /-   /   ||      \   /
          /  ____  /         |     \\ ~-_/  /|- _/   .||       \ /
          |~~    ~~|--~~~~--_ \     ~==-/   | \~--===~~        .\
                   '         ~-|      /|    |-~\~~       __--~~
                               |-~~-_/ |    |   ~\_   _-~            /\
                                    /  \     \__   \/~                \__
                                _--~ _/ | .-~~____--~-/                  ~~==.
                               ((->/~   '.|||' -_|    ~~-/ ,              . _||
                                          -_     ~\      ~~---l__i__i__i--~~_/
                                          _-~-__   ~)  \--______________--~~
                                        //.-~~~-~_--~- |-------~~~~~~~~
                                               //.-~~~--\
                                        神獸保佑，程式碼沒Bug!

        */
    </script>
</head>

<body>
    <nav class="navbar navbar-expand fixed-top navbar-light navbar-bg p-0 m-0">
        <div class="navbar-collapse collapse">
            <ul class="navbar-nav navbar-align">
                <li class="nav-item dropdown">
                    <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#"
                        data-bs-toggle="dropdown" data-bs-auto-close="outside" data-bs-display="static"
                        aria-expanded="false">
                        <i class="align-middle" data-feather="settings"></i>
                    </a>

                    <a class="nav-link dropdown-toggle align-middle d-none d-sm-inline-block" href="#"
                        data-bs-toggle="dropdown" data-bs-auto-close="outside" data-bs-display="static"
                        aria-expanded="false">
                        <i class="align-middle text-dark" data-feather="settings"></i>
                        <u class="text-dark"><span
                                class="align-middle text-dark">{{ __('loginPageLang.settings') }}</span></u>
                    </a>

                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" data-bs-toggle="collapse" data-bs-target="#langMenu"
                            aria-expanded="false"><i class="align-middle mr-1" data-feather="book-open"></i>
                            {{ __('templateWords.language') }}</a>
                        <div class="collapse" id="langMenu">
                            <a class="dropdown-item justify-content-center" href="{{ url('/lang/en') }}">
                                English</a>
                            <a class="dropdown-item justify-content-center" href="{{ url('/lang/zh-TW') }}">
                                繁體中文</a>
                            <a class="dropdown-item justify-content-center" href="{{ url('/lang/zh-CN') }}">
                                简体中文</a>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#"><i class="align-middle mr-1"
                                data-feather="help-circle"></i>
                            Help
                            Center</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <button type="hidden" id="QueryFlag" name="QueryFlag" value="Posting" style="display: none;"></button>
    <main class="d-flex w-100 h-100 position-relative">
        <div class="container d-flex flex-column">
            <div class="row vh-100">
                <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
                    <div class="d-table-cell align-middle">
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <div class="w-100" style="height: 2ch;"></div>
                        <div class="text-center mt-4">
                            <h1 class="h2" id="fordatabase">
                                {{ __('loginPageLang.welcome') }}
                            </h1>
                            <br>
                            <p class="lead">
                                {!! __('loginPageLang.newSSO_msg') !!}
                            </p>
                        </div>

                        <div class="card">
                            <div class="card-body py-0">
                                <div class="m-sm-4">
                                    <div class="text-center">
                                        <img src="../admin/img/avatars/avatarBot.png" alt="Charles Hall"
                                            class="img-fluid rounded-circle" width="132" height="132" />
                                    </div>
                                    <form class="needs-validation" id="registerform" method="post"
                                        accept-charset="utf-8" novalidate>
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('loginPageLang.site') }}</label>
                                            <select class="form-select form-select-lg" id="site" name="site"
                                                required>
                                                <option value="" disabled selected>
                                                    {{ __('loginPageLang.site_placeholder') }}</option>
                                                @for ($i = 1; $i < count($database_list); $i++)
                                                    <option value="{{ $database_list[$i] }}">
                                                        {{ $database_names[$i] }}</option>
                                                @endfor
                                            </select>
                                            <div class="invalid-feedback" id="site_error"
                                                style="display:none; color:red;">
                                                {!! __('loginPageLang.site_error') !!}</div>
                                        </div>
                                        <div class="mb-3">
                                            <input id="job_id" value="{{ \Session::get('work_id') }}" hidden>
                                            <input id="p_name" value="{{ \Session::get('user_name') }}" hidden>
                                            <input id="email" value="{{ \Session::get('office_mail') }}" hidden>
                                            <input id="dep" value="{{ \Session::get('dept_name') }}" hidden>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">{!! __('loginPageLang.profile_pic') !!}</label>
                                            <div class="row justify-content-center align-items-center">
                                                <div class="form-check col col-auto checkboxContainer m-0 p-0">
                                                    <label class="form-check-label" for="radio1">
                                                        <img src="../admin/img/avatars/avatarBot1.png"
                                                            alt="Ed Sheeran" class="img-fluid rounded-circle" />
                                                    </label>
                                                    <input class="form-check-input m-0 p-0 checkboxOnPic"
                                                        type="radio" name="flexRadioDefault" id="radio1"
                                                        value="1">
                                                </div>
                                                &nbsp;
                                                <div class="form-check col col-auto checkboxContainer m-0 p-0">
                                                    <label class="form-check-label" for="radio2">
                                                        <img src="../admin/img/avatars/avatarBot2.png" alt="Adele"
                                                            class="img-fluid rounded-circle" />
                                                    </label>
                                                    <input class="form-check-input m-0 p-0 checkboxOnPic"
                                                        type="radio" name="flexRadioDefault" id="radio2"
                                                        value="2">
                                                </div>
                                                &nbsp;
                                                <div class="form-check col col-auto checkboxContainer m-0 p-0">
                                                    <label class="form-check-label" for="radio3">
                                                        <img src="../admin/img/avatars/avatarBot3.png"
                                                            alt="Taylor Swift" class="img-fluid rounded-circle" />
                                                    </label>
                                                    <input class="form-check-input m-0 p-0 checkboxOnPic"
                                                        type="radio" name="flexRadioDefault" id="radio3"
                                                        value="3">
                                                </div>
                                                &nbsp;
                                                <div class="form-check col col-auto checkboxContainer m-0 p-0">
                                                    <label class="form-check-label" for="radio4">
                                                        <img src="../admin/img/avatars/avatarBot4.png" alt="Lady GaGa"
                                                            class="img-fluid rounded-circle" />
                                                    </label>
                                                    <input class="form-check-input m-0 p-0 checkboxOnPic"
                                                        type="radio" name="flexRadioDefault" id="radio4"
                                                        value="4">
                                                </div>
                                                &nbsp;
                                                <div class="form-check col col-auto checkboxContainer m-0 p-0">
                                                    <label class="form-check-label" for="radio5">
                                                        <img src="../admin/img/avatars/avatarBot5.png" alt="ColdPlay"
                                                            class="img-fluid rounded-circle" />
                                                    </label>
                                                    <input class="form-check-input m-0 p-0 checkboxOnPic"
                                                        type="radio" name="flexRadioDefault" id="radio5"
                                                        value="5">
                                                </div>
                                                <div class="invalid-feedback" id="picerror"
                                                    style="display:none; color:red;">
                                                    {!! __('loginPageLang.selectprofile_pic') !!}</div>

                                            </div>
                                        </div>

                                        <div class="text-center mt-2">
                                            <p class="lead" style="color:rgb(160, 90, 0);">
                                                {!! __('loginPageLang.permission_notice') !!}
                                            </p>
                                        </div>

                                        <div class="text-center mt-3">
                                            <input type="submit" class="btn btn-lg btn-primary"
                                                value="{!! __('loginPageLang.submit') !!}">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="{{ asset('/js/app.js?v=') . env('APP_VERSION') }}"></script>
    <script src="{{ asset('/admin/js/app.js?v=') . env('APP_VERSION') }}"></script>
    <script src="{{ asset('/messages.js?v=') . env('APP_VERSION') }}"></script>
    <script src="{{ asset('js/popupNotice.js?v=') . env('APP_VERSION') }}"></script>
    <script src="{{ asset('/js/login/register.js?v=') . env('APP_VERSION') }}"></script>
</body>

</html>
