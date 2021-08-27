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
