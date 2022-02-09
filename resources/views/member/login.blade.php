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

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="../admin/img/icons/icon-48x48.png" />

    <link rel="stylesheet" type="text/css" href="{{ asset('/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/jquery.loadingModal.min.css') }}">
    <script>
        if (window.history.replaceState) {
            // java script to prvent "confirm form resubmission" dialog
            // 避免重新整理這個頁面時跳出要你重新提交表單的對話框
            // (避免重新提交表單)
            window.history.replaceState(null, null, window.location.href);
        } // if
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    {{-- 使用asset時，須注意Laravel是抓http header裡面的Host作為base url，
    所以若域名不是ip而有經過轉換時要進到Nginx裡面的proxy_set_header裡面確認Host設定值，
    否則會抓到錯誤的Host導致找不到資源 --}}

    <script src="{{ asset('/js/app.js') }}"></script>
    <script src="{{ asset('/admin/js/app.js') }}"></script>
    <script src="{{ asset('/js/jquery.loadingModal.min.js') }}"></script>

    <script>
        //place your code to be executed early here
        $('body').loadingModal({
          text: 'Loading...',
          animation: 'circle'
        });
    </script>
    <nav class="navbar navbar-expand fixed-top navbar-light navbar-bg p-0 m-0">
        <a class="nav-icon d-inline-block d-sm-none" href="{{ route('welcome') }}">
            <i class="align-middle" data-feather="home"></i>
        </a>
        <div class="align-middle">
            <a class="nav-link d-none d-sm-inline-block" href="{{ route('welcome') }}">
                <i class="align-middle text-dark" data-feather="home"></i>
                <u class="text-dark"><span class="align-middle text-dark">{{ __('loginPageLang.home')}}</span></u>
            </a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="navbar-nav navbar-align">
                <li class="nav-item dropdown">
                    <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown"
                        data-bs-auto-close="outside" data-bs-display="static" aria-expanded="false">
                        <i class="align-middle" data-feather="settings"></i>
                    </a>

                    <a class="nav-link dropdown-toggle align-middle d-none d-sm-inline-block" href="#"
                        data-bs-toggle="dropdown" data-bs-auto-close="outside" data-bs-display="static"
                        aria-expanded="false">
                        <i class="align-middle text-dark" data-feather="settings"></i>
                        <u class="text-dark"><span class="align-middle text-dark">{{
                                __('loginPageLang.settings')}}</span></u>
                    </a>

                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="#"><i class="align-middle mr-1" data-feather="user"></i>
                            Profile</a>
                        <a class="dropdown-item" data-bs-toggle="collapse" data-bs-target="#langMenu"
                            aria-expanded="false"><i class="align-middle mr-1" data-feather="book-open"></i>
                            {{ __('templateWords.language')}}</a>
                        <div class="collapse" id="langMenu">
                            <a class="dropdown-item justify-content-center" href="{{ url('/lang/en') }}">
                                English</a>
                            <a class="dropdown-item justify-content-center" href="{{ url('/lang/zh-TW') }}">
                                繁體中文</a>
                            <a class="dropdown-item justify-content-center" href="{{ url('/lang/zh-CN') }}">
                                简体中文</a>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#"><i class="align-middle mr-1" data-feather="help-circle"></i>
                            Help
                            Center</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <main class="d-flex w-100 h-100">
        <div class="container d-flex flex-column">
            <div class="row vh-100">
                <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
                    <div class="d-table-cell align-middle">
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <div class="w-100 d-none d-sm-inline-block" style="height: 2ch;"></div>
                        <!-- </div>breaks cols to a new line-->
                        <div class="text-center mt-4">
                            <h1 class="h2 d-none d-sm-inline-block">
                                {{ __('loginPageLang.welcome')}}
                            </h1>
                            <br>
                            <p class="lead d-none d-sm-inline-block">
                                {{ __('loginPageLang.welcome_msg')}}
                            </p>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="m-sm-4">
                                    <div class="text-center">
                                        <img src="../admin/img/avatars/avatarBot.png" alt="Charles"
                                            class="img-fluid rounded-circle" width="132" height="132" />
                                    </div>
                                    <form id="loginForm" class="needs-validation" method="post" accept-charset="utf-8"
                                        novalidate>
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('loginPageLang.site')}}</label>
                                            <select class="form-select form-select-lg" id="site" name="site" required>
                                                <option value="" selected>{{ __('loginPageLang.site_placeholder')}}
                                                </option>
                                                <option value="M2_TEST_1112">M2_TEST</option>
                                                <option value="巴淡SMT1214">巴淡SMT_TEST</option>
                                                <option value="BB1_1214 Consumables management">BB1_TEST</option>
                                                <option value="巴淡-LOT11 Consumables management">巴淡-LOT11</option>
                                                <option value="巴淡-LOT2 Consumables management">巴淡-LOT2</option>
                                                <option value="巴淡-PTSN Consumables management">巴淡-PTSN</option>
                                                {{--
                                                <option value="testing">TEST</option>
                                                <option value="default">Default</option>
                                                <option value="BB1">BB1</option>
                                                <option value="BB4">BB4</option>
                                                <option value="M1">M1</option>
                                                <option value="M2">M2</option>
                                                <option value="M3">M3</option>
                                                <option value="SMT">SMT</option> --}}
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('loginPageLang.username')}}</label>
                                            <input class="form-control form-control-lg" type="text" id="username"
                                                name="username"
                                                placeholder="{{ __('loginPageLang.username_placeholder')}}" required
                                                autocomplete="username" autofocus />

                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">{{ __('loginPageLang.password')}}</label>
                                            <div class="input-group" id="show_hide_password">
                                                <input class="form-control form-control-lg" type="password"
                                                    id="password" name="password"
                                                    placeholder="{{ __('loginPageLang.password_placeholder')}}"
                                                    required />
                                                <div class="input-group-text" id="eye-button"
                                                    style="color: darkblue; border-radius: 5px;">
                                                    <a href="#">
                                                        <svg width="18" height="18" fill="currentColor"
                                                            viewBox="0 0 16 16">
                                                            <path id="eye-slash-fill"
                                                                d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z" />
                                                            <path id="eye-slash-fill2"
                                                                d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-center mt-3">
                                            <input type="submit" class="btn btn-lg btn-primary"
                                                value="{{ __('loginPageLang.login_btn')}}">
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

    <script src="{{ asset('/messages.js') }}"></script>
    <script src="{{ asset('js/popupNotice.js') }}"></script>
    <script src="{{ asset('/js/login/login.js') }}"></script>
</body>
