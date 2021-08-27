@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
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
                <h3>{!! __('templateWords.newPInfo') !!}</h3>
            </div>

            <div class="card-body">
                <div class="d-flex w-100 h-100">
                    <div class="mb-3">
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

                        <form  action = "{{ route('member.insertuploadpeople') }}"method="POST">
                            @csrf
                            <table class="table" id = "test">
                                <tr>
                                    <th><input type = "hidden" id = "title0" name = "title0" value = "工號">{!! __('loginPageLang.jobnumber') !!}</th>
                                    <th><input type = "hidden" id = "title1" name = "title1" value = "姓名">{!! __('loginPageLang.name') !!}</th>
                                    <th><input type = "hidden" id = "title2" name = "title2" value = "部門">{!! __('loginPageLang.dep') !!}</th>

                                    <input type = "hidden" id = "time" name = "time" value = "3">
                                </tr>
                                    @foreach($data as $row)
                                    <tr>
                                        <td><input type = "text"  name = "data0{{$loop->index}}" value = "{{$row[0]}}"></td>
                                        <td><input type = "text"  name = "data1{{$loop->index}}" value = "{{$row[1]}}"></td>
                                        <td><input type = "text"  name = "data2{{$loop->index}}" value = "{{$row[2]}}"></td>
                                    </tr>
                                    <input type = "hidden" id="count" name = "count" value="{{$loop->count}}">
                                @endforeach
                            </table>
                            <input type = "submit" id = "delete" name = "delete" class="btn btn-lg btn-primary" value="{!! __('loginPageLang.addtodatabase') !!}">
                        </form>
                    </div>
                </div>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('member.new')}}'">{!! __('loginPageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
