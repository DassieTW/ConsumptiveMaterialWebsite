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
<h2>{!! __('templateWords.monthly') !!}</h2>
<div class="card">
    <div class="card-header">
        <h3>{!! __('monthlyPRpageLang.importMonthlyData') !!}</h3>
    </div>
    <div class="card-body">
        <form action="{{ route('month.monthadd') }}" method="POST">
            @csrf
            <div class="table-responsive">
                <table class="table" id="month">
                    <tr>
                        <td>{!! __('monthlyPRpageLang.client') !!}</td>
                        <td>{!! __('monthlyPRpageLang.machine') !!}</td>
                        <td>{!! __('monthlyPRpageLang.process') !!}</td>
                        <td>{!! __('monthlyPRpageLang.nextmps') !!}</td>
                        <td>{!! __('monthlyPRpageLang.nextday') !!}</td>
                        <td>{!! __('monthlyPRpageLang.nowmps') !!}</td>
                        <td>{!! __('monthlyPRpageLang.nowday') !!}</td>
                    </tr>
                    @foreach($production as $production)
                    <tr>
                        <td><input type="hidden" id="client" name="client" value="{{$client}}">{{ $client }}</td>
                        <td><input type="hidden" id="machine" name="machine" value="{{$machine}}">{{ $machine }}</td>
                        <td><input type="hidden" id="production{{$loop->index}}" name="production{{$loop->index}}"
                                value="{{$production}}">{{ $production }}</td>
                        <td><input style="width:100px" type="number" id="nextmps{{$loop->index}}"
                                name="nextmps{{$loop->index}}" required value="{{$nextmps}}" step="0.001"
                                oninput="if(value.length>5)value=value.slice(0,5)"></td>
                        <td><input style="width:100px" type="number" id="nextday{{$loop->index}}"
                                name="nextday{{$loop->index}}" required value="{{$nextday}}" step="0.001"
                                oninput="if(value.length>5)value=value.slice(0,5)"></td>
                        <td><input style="width:10px" type="number" id="nowmps{{$loop->index}}"
                                name="nowmps{{$loop->index}}" required value="{{$nowmps}}" step="0.001"
                                oninput="if(value.length>5)value=value.slice(0,5)"></td>
                        <td><input style="width:100px" type="number" id="nowday{{$loop->index}}"
                                name="nowday{{$loop->index}}" required value="{{$nowday}}" step="0.001"
                                oninput="if(value.length>5)value=value.slice(0,5)"></td>
                    </tr>
                    <input type="hidden" id="count" name="count" value="{{$loop->count}}">
                    @endforeach
                </table>
            </div>
            <br>
            <input type="submit" id="add" name="add" class="btn btn-lg btn-primary"
                value="{!! __('monthlyPRpageLang.add') !!}">
        </form>
        <br>
        <button class="btn btn-lg btn-primary" onclick="location.href='{{route('month.importmonth')}}'">{!!
            __('monthlyPRpageLang.return') !!}</button>
    </div>
</div>

</html>
@endsection
