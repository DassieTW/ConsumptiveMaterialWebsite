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
                <h3>{!! __('templateWords.PInfo') !!}</h3>
            </div>
            <div class="card-body">
                <div class="d-flex w-100 h-100">
                    <div class="mb-3">
                        <form action="{{ route('member.numberchangeordel') }}" method="POST">
                            @csrf
                            <table class="table" id = "test">
                                <tr>
                                    <th>{!! __('loginPageLang.delete') !!}</th>
                                    <th>{!! __('loginPageLang.jobnumber') !!}</th>
                                    <th>{!! __('loginPageLang.name') !!}</th>
                                    <th>{!! __('loginPageLang.dep') !!}</th>
                                </tr>
                                    @foreach($data as $data)
                                    <tr>
                                        <td><input class ="innumber" type="checkbox" id="innumber{{$loop->index}}" name="innumber{{$loop->index}}" style="width:20px;height:20px;" ></td>
                                        <td><input type = "hidden" id = "number{{$loop->index}}" name = "number{{$loop->index}}" value = "{{$data->工號}}">{{$data->工號}}</td>
                                        <td><input style="width:100px" type = "text" id = "name{{$loop->index}}" name = "name{{$loop->index}}" value = "{{$data->姓名}}"></td>
                                        <td><input style="width:100px" type = "text" id = "department{{$loop->index}}" name = "department{{$loop->index}}" value = "{{$data->部門}}"></td>
                                    </tr>
                                    <input type = "hidden" id="count" name = "count" value="{{$loop->count}}">
                                @endforeach

                            </table>
                            <input type = "submit" id = "delete" name = "delete" class="btn btn-lg btn-primary" value="{!! __('loginPageLang.delete') !!}">
                            <input type = "submit" id = "change" name = "change" class="btn btn-lg btn-primary" value="{!! __('loginPageLang.change') !!}">

                        </form>
                    </div>
                </div>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('member.index')}}'">{!! __('loginPageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
