<<<<<<< HEAD
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="../admin/img/icons/icon-48x48.png" />
    <script src="{{ asset('../js/app.js') }}" defer></script>

    <script src="{{ asset('../js/login.js') }}" defer></script>

    <link href="../admin/css/app.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>
    <main class="d-flex w-100 h-100">
            <div class="container d-flex flex-column">
                <div class="row vh-100">
                    <div class="col-sm-10 col-md-8 col-lg-6 mx-auto d-table h-100">
                        <div class="d-table-cell align-middle">

                            <div class="text-center mt-4">
                                <h1 class="h2">Welcome</h1>
                                <p class="lead">
                                    Change your password or not.
                                </p>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="m-sm-4">
                                        <div class="text-center">
                                            <img src="../admin/img/avatars/avatar.jpg" alt="Charles Hall" class="img-fluid rounded-circle" width="132" height="132" />
                                        </div>
                                        <form>
                                            @csrf
                                            <?php
                                                $username = Session::get('username');
                                                echo "now login user name : " . $username;
                                            ?>
                                            <div class="mb-3">
                                                <label class="form-label">Old Password</label>
                                                <input class="form-control form-control-lg @error('password') is-invalid @enderror" type="password" id ="password" name="password" placeholder="Enter your old password" required/>
                                                <input type="checkbox" onclick="showpassword()">Show Password<br>
                                                @error('password')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">New Password</label>
                                                <input class="form-control form-control-lg @error('newpassword') is-invalid @enderror" type="password" id = "newpassword" name="newpassword" placeholder="Enter your new password" required/>
                                                <input type="checkbox" onclick="showpassword1()">Show Password<br>
                                                @error('newpassword')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label"> Confirm New Password</label>
                                                <input class="form-control form-control-lg @error('surepassword') is-invalid @enderror" type="password" id = "surepassword" name="surepassword" placeholder="Enter your new password again" required/>
                                                <input type="checkbox" onclick="showpassword2()">Show Password<br>
                                                @error('surepassword')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="text-center mt-3">
                                                <input type = "submit" class="btn btn-lg btn-primary" value="Change">

                                                <!-- <button type="submit" class="btn btn-lg btn-primary">Sign in</button> -->
                                            </div>
                                        </form>
                                        <div class="text-center mt-3">
                                            <input type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('member.index')}}'" value="Home">
                                        </div>
=======
@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')

<script src="{{ asset('/js/login/change.js') }}" defer></script>
<!--for this page's sepcified js -->

@endsection
@section('content')
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    </head>
        <h2>{!! __('templateWords.userManage') !!}</h2>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('templateWords.changePass') !!}</h3>
            </div>
            <div class="card-body">
                <div class="d-flex w-100 h-100">
                    <div class="mb-3">
                        <form id = "changepassword">
                            @csrf

                            <?php
                                $username = Session::get('username');
                                echo __('templateWords.nowuser') .' '. $username;
                            ?>
                            <hr />

                            <div class="mb-3">
                                <label class="form-label">{!! __('loginPageLang.oldpass') !!}</label>
                                <div class="input-group" id="show_hide_password">
                                    <input
                                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                                        type="password" id="password" name="password"
                                        placeholder="{!! __('loginPageLang.enteroldpass') !!}" required />
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

                            <div id = "message">{!! __('loginPageLang.errorpassword') !!}</div>

                            <div class="mb-3">
                                <label class="form-label">{!! __('loginPageLang.newpass') !!}</label>
                                <div class="input-group" id="show_hide_password1">
                                    <input
                                        class="form-control form-control-lg @error('newpassword') is-invalid @enderror"
                                        type="password" id="newpassword" name="newpassword"
                                        placeholder="{!! __('loginPageLang.enternewpass') !!}" required />
                                    <div class="input-group-text" id="eye-button1"
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
                                    @error('newpassword')
                                    <span class="invalid-feedback p-0 m-0" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">{!! __('loginPageLang.surepassword') !!}</label>
                                <div class="input-group" id="show_hide_password2">
                                    <input
                                        class="form-control form-control-lg @error('surepassword') is-invalid @enderror"
                                        type="password" id="surepassword" name="surepassword"
                                        placeholder="{!! __('loginPageLang.enterpassword') !!}" required />
                                    <div class="input-group-text" id="eye-button2"
                                        style="color: darkblue; border-radius: 5px;">
                                        <a href="#">
                                            <svg width="18" height="18" fill="currentColor"
                                                viewBox="0 0 16 16">
                                                <path id="eye-slash-fill5"
                                                    d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z" />
                                                <path id="eye-slash-fill6"
                                                    d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z" />
                                            </svg>
                                        </a>
>>>>>>> 0827tony
                                    </div>
                                </div>
                            </div>

<<<<<<< HEAD
                        </div>
                    </div>
                </div>
            </div>
    </main>
</body>
=======
                            <div id = "message2">{!! __('loginPageLang.errorpassword2') !!}</div>

                            <input type = "submit" class="btn btn-lg btn-primary" value="{!! __('loginPageLang.change') !!}">
                        </form>
                    </div>
                </div>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('member.index')}}'">{!! __('loginPageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
>>>>>>> 0827tony
