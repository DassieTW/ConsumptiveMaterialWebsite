@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('/js/month/notmonthadd.js') }}"></script>
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
                <h3>{!! __('monthlyPRpageLang.importNonMonthlyData') !!}</h3>
            </div>
            <div class="card-body">
                <form id="notmonthadd">
                    @csrf
                <div class="d-flex w-100 h-100">
                    <div class="mb-3">
                        <table class="table" id ="notmonth">
                            <tr>
                                <td>{!! __('monthlyPRpageLang.client') !!}</td>
                                <td>{!! __('monthlyPRpageLang.isn') !!}</td>
                                <td>{!! __('monthlyPRpageLang.buyamount') !!}</td>
                                <td>{!! __('monthlyPRpageLang.sxb') !!}</td>
                                <td>{!! __('monthlyPRpageLang.description') !!}</td>
                                <td>{!! __('monthlyPRpageLang.pName') !!}</td>
                                <td>{!! __('monthlyPRpageLang.unit') !!}</td>
                                <td>{!! __('templateWords.monthly') !!}</td>
                                <td>{!! __('monthlyPRpageLang.control') !!}</td>
                            </tr>
                            <tr>
                                <td><input type="hidden" id ="client" name="client"  value="{{$client}}">{{$client}}</td>
                                <td><input type="hidden" id ="number" name="number"  value="{{$number}}">{{ $number }}</td>
                                <td><input type="number" id ="amount" name="amount" required placeholder="{!! __('monthlyPRpageLang.enteramount') !!}"></td>
                                <td><input type="text" id ="sxb" name="sxb" required placeholder="{!! __('monthlyPRpageLang.entersxb') !!}"></td>
                                <td><input type="text" id ="say" name="say" placeholder="{!! __('monthlyPRpageLang.enterdesc') !!}"></td>
                                <td>{{ $name }}</td>
                                <td>{{ $unit }}</td>
                                <td><input type="hidden" id ="month" name="month"  value="{{$month}}">{{ $month }}</td>
                                <td>
                                    <select class="form-control form-control-lg " id = "reason" name="reason" >
                                    <option style="display: none" disabled selected>{!! __('monthlyPRpageLang.entercontrol') !!}</option>
                                    <option>品質問題</option>
                                    <option>MPS上升</option>
                                    <option>其他</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <div id = "error">{!! __('monthlyPRpageLang.errormonth') !!}</div>
                    </div>
                </div>
                <input type = "submit" id = "add" name ="add" class="btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.add') !!}">
                </form>
                <br>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('month.importnotmonth')}}'">{!! __('monthlyPRpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
