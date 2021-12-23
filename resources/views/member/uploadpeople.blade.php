@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/login/uploadpeople.js') }}"></script>
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

        <form id="uploadpeople" method="POST">
            @csrf
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th><input type="hidden" id="title0" name="title0" value="工號">{!! __('loginPageLang.jobnumber')
                            !!}</th>
                        <th><input type="hidden" id="title1" name="title1" value="姓名">{!! __('loginPageLang.name') !!}
                        </th>
                        <th><input type="hidden" id="title2" name="title2" value="部門">{!! __('loginPageLang.dep') !!}
                        </th>

                        <input type="hidden" id="titlecount" name="titlecount" value="3">
                    </tr>
                    @foreach($data as $row)
                    <tr id = "row{{$loop->index}}">
                        <td><input type="text" id="data0{{$loop->index}}" name="data0{{$loop->index}}"
                                value="{{$row[0]}}" required oninput="if(value.length>9)value=value.slice(0,9)"
                                class="form-control form-control-lg"></td>
                        <td><input type="text" id="data1{{$loop->index}}" name="data1{{$loop->index}}"
                                value="{{$row[1]}}" required class="form-control form-control-lg"></td>
                        <td><input type="text" id="data2{{$loop->index}}" name="data2{{$loop->index}}"
                                value="{{$row[2]}}" required class="form-control form-control-lg"></td>
                    </tr>
                    <input type="hidden" id="count" name="count" value="{{$loop->count}}">
                    @endforeach
                </table>
            </div>
            <br>
            <input type="submit" id="delete" name="delete" class="btn btn-lg btn-primary"
                value="{!! __('loginPageLang.addtodatabase') !!}">
        </form>
        <br>
        <button class="btn btn-lg btn-primary" onclick="location.href='{{route('member.new')}}'">{!!
            __('loginPageLang.return') !!}</button>
    </div>
</div>

</html>
@endsection
