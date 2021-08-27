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
                <h3>{!! __('templateWords.UserInfo') !!}</h3>
            </div>
            <div class="card-body">
                <div class="d-flex w-100 h-100">
                    <div class="mb-3">
                        <form action="{{ route('member.usernamechangeordel') }}" method="POST">
                            @csrf
                            <table class="table" id = "test">
                                <tr>
                                    <th>{!! __('loginPageLang.delete') !!}</th>
                                    <th>{!! __('loginPageLang.username') !!}</th>
                                    <th>{!! __('loginPageLang.password') !!}</th>
                                    <th>{!! __('loginPageLang.priority') !!}</th>
                                    <th>{!! __('loginPageLang.name') !!}</th>
                                    <th>{!! __('loginPageLang.dep') !!}</th>
                                </tr>
                                    @foreach($data as $data)
                                    <tr>
                                        <td><input class ="innumber" type="checkbox" id="innumber{{$loop->index}}" name="innumber{{$loop->index}}" style="width:20px;height:20px;" ></td>
                                        <td><input type = "hidden" id = "username{{$loop->index}}" name = "username{{$loop->index}}" value = "{{$data->username}}">{{$data->username}}</td>
                                        <td>{{$data->password}}</td>
                                        <td>{{$data->priority}}</td>
                                        <td>{{$data->姓名}}</td>
                                        <td>{{$data->部門}}</td>
                                    </tr>
                                    <input type = "hidden" id="count" name = "count" value="{{$loop->count}}">
                                @endforeach

                            </table>
                            <input type = "submit" id = "delete" name = "delete" class="btn btn-lg btn-primary" value="{!! __('loginPageLang.delete') !!}">
                        </form>
                    </div>
                </div>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('member.username')}}'">{!! __('loginPageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
