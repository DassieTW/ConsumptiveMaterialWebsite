@extends('layouts.adminTemplate')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')

    <script src="{{ asset('/js/login/change.js') }}"></script>
    <!--for this page's sepcified js -->

@endsection
@section('content')
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
    </head>


<div id="mountingPoint">
    <div class="row mb-2 mb-xl-3 justify-content-between">
        <h2 class="col-auto">{!! __('templateWords.userManage') !!}</h2>
        <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
            <vue-bread-crumb></vue-bread-crumb>
        </div>
    </div>
</div>
    <div class="row justify-content-center">
        <div class="card w-75">
            <div class="card-header">
                <h3>{!! __('loginPageLang.changePass') !!}</h3>
            </div>
            <div class="row justify-content-center">
                <div class="card-body">
                    <form id="changepassword" class="needs-validation text-center" method="post" novalidate>
                        @csrf
                        <div class="row justify-content-center mb-3">

                            <?php
                            $username = Session::get('username');
                            echo __('templateWords.nowuser') . ' ' . $username;
                            ?>
                            <hr />

                            <label class="col col-auto form-label p-0 m-0">{!! __('loginPageLang.oldpass') !!}</label>
                            <div class="w-100" style="height: 0ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="col-lg-6  col-md-12 col-sm-12">
                                <div class="input-group" id="show_hide_password">
                                    <input class="form-control form-control-lg" type="password" id="password"
                                        name="password" placeholder="{!! __('loginPageLang.enteroldpass') !!}" required />
                                    <div class="input-group-text" id="eye-button"
                                        style="color: darkblue; border-radius: 5px;">
                                        <a href="#">
                                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                                <path id="eye-slash-fill"
                                                    d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z" />
                                                <path id="eye-slash-fill2"
                                                    d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <label class="col col-auto form-label p-0 m-0">{!! __('loginPageLang.newpass') !!}</label>
                            <div class="w-100" style="height: 0ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="col-lg-6  col-md-12 col-sm-12">
                                <div class="input-group" id="show_hide_password1">
                                    <input class="form-control form-control-lg" type="password" id="newpassword"
                                        name="newpassword" placeholder="{!! __('loginPageLang.enternewpass') !!}" required />
                                    <div class="input-group-text" id="eye-button1"
                                        style="color: darkblue; border-radius: 5px;">
                                        <a href="#">
                                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                                <path id="eye-slash-fill3"
                                                    d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z" />
                                                <path id="eye-slash-fill4"
                                                    d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <label class="col col-auto form-label p-0 m-0">{!! __('loginPageLang.surepassword') !!}</label>
                            <div class="w-100" style="height: 0ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="col-lg-6  col-md-12 col-sm-12">
                                <div class="input-group" id="show_hide_password2">
                                    <input class="form-control form-control-lg" type="password" id="surepassword"
                                        name="surepassword" placeholder="{!! __('loginPageLang.enterpassword') !!}" required />
                                    <div class="input-group-text" id="eye-button2"
                                        style="color: darkblue; border-radius: 5px;">
                                        <a href="#">
                                            <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                                <path id="eye-slash-fill5"
                                                    d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7.029 7.029 0 0 0 2.79-.588zM5.21 3.088A7.028 7.028 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474L5.21 3.089z" />
                                                <path id="eye-slash-fill6"
                                                    d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829l-2.83-2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12-.708.708z" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <div class="row w-100 justify-content-center">
                                <div class="col col-auto">
                                    <input type="submit" class="btn btn-lg btn-primary" value="{!! __('loginPageLang.change') !!}">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="card w-75">
            <div class="card-header">
                <h3>{!! __('loginPageLang.changeMail') !!}</h3>
            </div>
            <div class="row justify-content-center">
                <div class="card-body">
                    <form id="changeEmail" class="needs-validation text-center" method="post" novalidate>
                        @csrf
                        <div class="row justify-content-center mb-3">
                            <?php
                            $username = Session::get('username');
                            echo __('templateWords.nowuser') . ' ' . $username;
                            ?>
                            <hr />
                            <label class="col col-auto form-label p-0 m-0">{!! __('loginPageLang.old_email') !!}</label>
                            <div class="w-100" style="height: 0ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="col-lg-6  col-md-12 col-sm-12">
                                <input class="form-control form-control-lg text-center" type="text" id="oldMail"
                                    name="oldMail" placeholder="{!! __('loginPageLang.no_email') !!}" value="@php
                                        // echo $oldMail[0]->email; // test
                                        if (count($oldMail) == 0) {
                                            echo '';
                                        }
                                        // if
                                        else {
                                            echo $oldMail[0]->email;
                                        } // else
                                    @endphp"
                                    readonly />
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <label class="col col-auto form-label p-0 m-0">{!! __('loginPageLang.new_email') !!}</label>
                            <div class="w-100" style="height: 0ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="col-10">
                                <div class="input-group">
                                    <input class="form-control form-control-lg text-center" type="text" id="newMail"
                                        name="newMail" placeholder="{!! __('loginPageLang.enter_email') !!}" />
                                    <select class="form-select form-select-lg" id="emailTail">
                                        <option selected>@pegatroncorp.com</option>
                                        <option>@intra.pegatroncorp.com</option>
                                    </select>
                                </div>
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <div class="row w-100 justify-content-center">
                                <div class="col col-auto">
                                    <input type="submit" class="btn btn-lg btn-primary" value="{!! __('loginPageLang.change') !!}">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    </html>
@endsection
