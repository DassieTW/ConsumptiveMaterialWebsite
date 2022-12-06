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
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="../admin/img/icons/icon-48x48.png" />
    <link href="../admin/css/app.css" rel="stylesheet">
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
</head>

<body>
    <nav class="navbar navbar-expand fixed-top navbar-light navbar-bg p-0 m-0">
        <a class="nav-icon d-inline-block d-sm-none" href="{{ route('member.index') }}">
            <i class="align-middle" data-feather="arrow-left"></i>
        </a>
        <div class="align-middle">
            <a class="nav-link d-none d-sm-inline-block" href="{{ route('member.index') }}">
                <i class="align-middle text-dark" data-feather="arrow-left"></i>
                <u class="text-dark"><span class="align-middle text-dark">{{ __('loginPageLang.pre_page') }}</span></u>
            </a>
        </div>
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
                        <a class="dropdown-item" href="#"><i class="align-middle mr-1" data-feather="user"></i>
                            Profile</a>
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

    <main class="d-flex w-100 h-100 position-relative">
        <div class="container d-flex flex-column">
            <div class="row vh-100">
                <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
                    <div class="d-table-cell align-middle">
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <div class="w-100 d-none d-sm-inline-block" style="height: 2ch;"></div>
                        <div class="text-center mt-4">
                            <h1 class="h2">{!! __('loginPageLang.adduser') !!}</h1>
                        </div>

                        <div class="card">
                            <div class="card-body py-0">
                                <div class="m-sm-4">
                                    <div class="text-center">
                                        <img src="../admin/img/avatars/avatarBot.png" alt="Charles Hall"
                                            class="img-fluid rounded-circle" width="132" height="132" />
                                    </div>
                                    <form class="needs-validation" id="registerform" method="post"
                                        accept-charset="utf-8">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label">{!! __('loginPageLang.username') !!}</label>
                                            <input
                                                class="form-control form-control-lg @error('username') is-invalid @enderror"
                                                type="text" id="username" name="username"
                                                placeholder="{!! __('loginPageLang.username_placeholder') !!}">
                                            <div class="invalid-feedback" id="usernameerror"
                                                style="display:none; color:red;">
                                                {!! __('loginPageLang.username_placeholder') !!}</div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">{!! __('loginPageLang.password') !!}</label>
                                            <div class="input-group" id="show_hide_password">
                                                <input
                                                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                                                    type="password" id="password" name="password"
                                                    placeholder="{!! __('loginPageLang.password_placeholder') !!}" />
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
                                                <div class="invalid-feedback" id="passworderror"
                                                    style="display:none; color:red;">
                                                    {!! __('loginPageLang.password_placeholder') !!}</div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">{!! __('loginPageLang.surepassword') !!}</label>
                                            <div class="input-group" id="show_hide_password2">
                                                <input
                                                    class="form-control form-control-lg @error('password2') is-invalid @enderror"
                                                    type="password" id="password2" name="password2"
                                                    placeholder="{!! __('loginPageLang.enterpassword') !!}" />
                                                <div class="input-group-text" id="eye-button2"
                                                    style="color: darkblue; border-radius: 5px;">
                                                    <a href="#">
                                                        <svg width="18" height="18" fill="currentColor"
                                                            viewBox="0 0 16 16">
                                                            <path id="eye-slash-fill3"
                                                                d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z" />
                                                            <path id="eye-slash-fill4"
                                                                d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                                <div class="invalid-feedback" id="passworderror1"
                                                    style="display:none; color:red;">
                                                    {!! __('loginPageLang.password_placeholder') !!}</div>

                                                <div class="invalid-feedback" id="passworderror2"
                                                    style="display:none; color:red;">
                                                    {!! __('loginPageLang.errorpassword2') !!}</div>
                                            </div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">{!! __('loginPageLang.name') !!}</label>
                                            <input class="form-control form-control-lg" type="text" id="name"
                                                name="name" placeholder="{!! __('loginPageLang.entername') !!}" />
                                            <div class="invalid-feedback" id="nameerror"
                                                style="display:none; color:red;">
                                                {!! __('loginPageLang.entername') !!}</div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">{!! __('loginPageLang.email') !!}</label>
                                            <div class="input-group">
                                                <input type="text" id="email" name="email"
                                                    class="form-control form-control" style="width: 200px;"
                                                    placeholder="{!! __('loginPageLang.enter_email') !!}">
                                                <select class="form-select form-select-lg" style="width: 200px;"
                                                    id="emailTail">
                                                    <option selected>@pegatroncorp.com</option>
                                                    <option>@intra.pegatroncorp.com</option>
                                                </select>
                                            </div>
                                            <div class="invalid-feedback" id="emailerror"
                                                style="display:none; color:red;">
                                                {!! __('loginPageLang.enteremail') !!}</div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">{!! __('loginPageLang.priority') !!}</label>

                                            <select class="form-select form-select-lg" id="priority"
                                                name="priority">
                                                <option style="display: none" disabled selected value="">
                                                    {!! __('loginPageLang.enterpriority') !!}</option>
                                                <option>1</option>
                                                <option>2</option>
                                                <option>3</option>
                                                <option>4</option>
                                            </select>
                                            <div class="invalid-feedback" id="priorityerror"
                                                style="display:none; color:red;">
                                                {!! __('loginPageLang.enterpriority') !!}</div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">{!! __('loginPageLang.dep') !!}</label>
                                            <input class="form-control form-control-lg " type="text"
                                                id="department" name="department"
                                                placeholder="{!! __('loginPageLang.enterdep') !!}">
                                            <div class="invalid-feedback" id="deperror"
                                                style="display:none; color:red;">
                                                {!! __('loginPageLang.enterdep') !!}</div>
                                        </div>

                                        <label class="form-label">{!! __('loginPageLang.profile_pic') !!}</label>
                                        <div class="row justify-content-center align-items-center">
                                            <div class="form-check col col-auto checkboxContainer m-0 p-0">
                                                <label class="form-check-label" for="radio1">
                                                    <img src="../admin/img/avatars/avatarBot1.png" alt="Ed Sheeran"
                                                        class="img-fluid rounded-circle" />
                                                </label>
                                                <input class="form-check-input m-0 p-0 checkboxOnPic" type="radio"
                                                    name="flexRadioDefault" id="radio1" value="1">
                                            </div>
                                            &nbsp;
                                            <div class="form-check col col-auto checkboxContainer m-0 p-0">
                                                <label class="form-check-label" for="radio2">
                                                    <img src="../admin/img/avatars/avatarBot2.png" alt="Adele"
                                                        class="img-fluid rounded-circle" />
                                                </label>
                                                <input class="form-check-input m-0 p-0 checkboxOnPic" type="radio"
                                                    name="flexRadioDefault" id="radio2" value="2">
                                            </div>
                                            &nbsp;
                                            <div class="form-check col col-auto checkboxContainer m-0 p-0">
                                                <label class="form-check-label" for="radio3">
                                                    <img src="../admin/img/avatars/avatarBot3.png" alt="Taylor Swift"
                                                        class="img-fluid rounded-circle" />
                                                </label>
                                                <input class="form-check-input m-0 p-0 checkboxOnPic" type="radio"
                                                    name="flexRadioDefault" id="radio3" value="3">
                                            </div>
                                            &nbsp;
                                            <div class="form-check col col-auto checkboxContainer m-0 p-0">
                                                <label class="form-check-label" for="radio4">
                                                    <img src="../admin/img/avatars/avatarBot4.png" alt="Lady GaGa"
                                                        class="img-fluid rounded-circle" />
                                                </label>
                                                <input class="form-check-input m-0 p-0 checkboxOnPic" type="radio"
                                                    name="flexRadioDefault" id="radio4" value="4">
                                            </div>
                                            &nbsp;
                                            <div class="form-check col col-auto checkboxContainer m-0 p-0">
                                                <label class="form-check-label" for="radio5">
                                                    <img src="../admin/img/avatars/avatarBot5.png" alt="Charles Hall"
                                                        class="img-fluid rounded-circle" />
                                                </label>
                                                <input class="form-check-input m-0 p-0 checkboxOnPic" type="radio"
                                                    name="flexRadioDefault" id="radio5" value="5">
                                            </div>

                                            {{-- @error('flexRadioDefault')
                                                <span class="invalid-feedback p-0 m-0" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror --}}

                                            <div class="invalid-feedback" id="picerror"
                                                style="display:none; color:red;">
                                                {!! __('loginPageLang.selectprofile_pic') !!}</div>

                                        </div>

                                        <div class="text-center mt-3">
                                            <input type="submit" class="btn btn-lg btn-primary"
                                                value="{!! __('loginPageLang.signup') !!}">
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

    <script src="{{ asset('/js/app.js') }}"></script>
    <script src="{{ asset('/admin/js/app.js') }}"></script>
    <script src="{{ asset('/js/jquery.loadingModal.min.js') }}"></script>
    <script src="{{ asset('/messages.js') }}"></script>
    <script src="{{ asset('js/popupNotice.js') }}"></script>
    <script src="{{ asset('/js/login/register.js') }}"></script>
</body>

</html>
