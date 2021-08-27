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
                                <h1 class="h2">Welcome come</h1>
                                <p class="lead">
                                    New People
                                </p>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="m-sm-4">
                                        <div class="text-center">
                                            <img src="../admin/img/avatars/avatar.jpg" alt="Charles Hall" class="img-fluid rounded-circle" width="132" height="132" />
                                        </div>
                                        <form action="{{ route('member.new') }}" method="POST">
                                            @csrf
                                            <div class="mb-3">
                                                <label class="form-label">工號</label>
                                                <input class="form-control form-control-lg @error('number') is-invalid @enderror" type="text" id ="number" name="number" placeholder="Enter your job number" required/>
                                                @error('number')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror

                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">姓名</label>
                                                <input class="form-control form-control-lg @error('name') is-invalid @enderror" type="text" id = "name" name="name" placeholder="Enter your name" required/>
                                                @error('name')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">部門</label>
                                                <input class="form-control form-control-lg  @error('department') is-invalid @enderror" type="text" id = "department" name="department" placeholder="Enter your department" required/>
                                                @error('department')
                                                <span class="invalid-feedback d-block" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                            </div>

                                            <div class="text-center mt-3">
                                                <input type = "submit" class="btn btn-lg btn-primary" value="New People">
                                                <!-- <button type="submit" class="btn btn-lg btn-primary">Sign in</button> -->
                                            </div>
                                        </form>
                                        <div class="text-center mt-3">
                                            <input type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('member.index')}}'" value="Home">
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
    </main>
</body>
=======
@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/login/new.js') }}"></script>
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
                <h3>{!! __('templateWords.newPInfo') !!}</h3>
            </div>
            <div class="card-body">
                <form id = "new_people" >
                    @csrf
                    <div class="d-flex w-100 h-100">
                        <div class="mb-3">


                            <label class="form-label">{!! __('loginPageLang.jobnumber') !!}</label>
                            <input class="form-control form-control-lg " type="text" id ="number" name="number" required>
                            <div id = "message">
                                {!! __('loginPageLang.jobrepeat') !!}
                            </div>
                            <div id = "message1">
                                {!! __('loginPageLang.joblength') !!}
                            </div>
                            <label class="form-label">{!! __('loginPageLang.name') !!}</label>
                            <input class="form-control form-control-lg " type="text" id ="name" name="name" required>

                            <label class="form-label">{!! __('loginPageLang.dep') !!}</label>
                            <input class="form-control form-control-lg " type="text" id ="department" name="department" required>

                        </div>
                    </div>
                    <input type = "submit" id = "submit" name = "submit" class="btn btn-lg btn-primary" value="{!! __('loginPageLang.new') !!}">
                </form>
                <br>
                <a class="btn btn-lg btn-primary" href="{{asset('download/PeopleInformationExample.xlsx')}}" download>{!! __('loginPageLang.exampleExcel') !!}</a>
                <br>
                @if(count($errors) > 0)
                <div class="alert alert-danger">
                    Upload Validation Error<br><br>
                    <ul>
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                    </ul>
                </div>
                @endif

                @if($message = Session::get('success'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                </div>
                @endif
                <form method="post" enctype="multipart/form-data" action = "{{ route('member.uploadpeople') }}">
                    @csrf
                    <div class="col-6 col-sm-3">
                        <label>{!! __('loginPageLang.plz_upload') !!}</label>
                        <input  class="form-control"  type="file" name="select_file" />
                        <br>
                        <input type="submit" name="upload" class="btn btn-lg btn-primary" value="{!! __('loginPageLang.upload') !!}">
                    </div>
                </form>
                <br>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('member.index')}}'">{!! __('loginPageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
>>>>>>> 0827tony
