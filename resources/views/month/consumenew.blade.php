@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/month/consumenew.js') }}"></script>
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
                <h3>{!! __('monthlyPRpageLang.isnConsumeAdd') !!}</h3>
            </div>
            <div class="card-body">
                <form id = "consumenew">
                    @csrf
                    <div class="table-responsive">
                    <table class="table" id = "inboundsearch">
                        <tr id = "">
                            <th>{!! __('monthlyPRpageLang.isn') !!}</th>
                            <th>{!! __('monthlyPRpageLang.pName') !!}</th>
                            <th>{!! __('monthlyPRpageLang.format') !!}</th>
                            <th>{!! __('monthlyPRpageLang.unit') !!}</th>
                            <th>{!! __('monthlyPRpageLang.lt') !!}</th>
                            <th>{!! __('monthlyPRpageLang.consume') !!}</th>
                            <th>{!! __('monthlyPRpageLang.nowneed') !!}</th>
                            <th>{!! __('monthlyPRpageLang.nextneed') !!}</th>
                            <th>{!! __('monthlyPRpageLang.safe') !!}</th>
                            <th>{!! __('monthlyPRpageLang.client') !!}</th>
                            <th>{!! __('monthlyPRpageLang.machine') !!}</th>
                            <th>{!! __('monthlyPRpageLang.process') !!}</th>
                            <th>{!! __('monthlyPRpageLang.nowmps') !!}</th>
                            <th>{!! __('monthlyPRpageLang.nowday') !!}</th>
                            <th>{!! __('monthlyPRpageLang.nextmps') !!}</th>
                            <th>{!! __('monthlyPRpageLang.nextday') !!}</th>
                        </tr>
                        <tr>
                            <td><input style="width:150px" class="form-control form-control-lg" type="text" id ="number" name="number" readonly value = "{{ Session::get('number') }}"></td>
                            <td>{{ Session::get('name') }}</td>
                            <td>{{ Session::get('format') }}</td>
                            <td>{{ Session::get('unit') }}</td>
                            <td><input style="width:70px" class="form-control form-control-lg " type="text" id ="lt" name="lt" readonly value = "{{ Session::get('lt') }}"></td>
                            <td><input style="width:100px" class="form-control form-control-lg " type="number" id ="amount" name="amount" value = "0" placeholder="請輸入單耗" step="0.01"></td>
                            <td><input style="width:100px" class="form-control form-control-lg " type="number" id ="nowneed" name="nowneed" readonly></td>
                            <td><input style="width:100px" class="form-control form-control-lg " type="number" id ="nextneed" name="nextneed" readonly></td>
                            <td><input style="width:100px" class="form-control form-control-lg " type="number" id ="safe" name="safe" readonly ></td>
                            <td><input style="width:150px" class="form-control form-control-lg " type="text" id ="client" name="client" readonly value = "{{ Session::get('client') }}"></td>
                            <td><input style="width:150px" class="form-control form-control-lg " type="text" id ="machine" name="machine" readonly value = "{{ Session::get('machine') }}"></td>
                            <td><input style="width:150px" class="form-control form-control-lg " type="text" id ="production" name="production" readonly value = "{{ Session::get('production') }}"></td>
                            <td><input style="width:100px" class="form-control form-control-lg " type="number" id ="nowmps" name="nowmps" value = "{{ Session::get('nowmps') }}"></td>
                            <td><input style="width:100px" class="form-control form-control-lg " type="number" id ="nowday" name="nowday" value = "{{ Session::get('nowday') }}"></td>
                            <td><input style="width:100px" class="form-control form-control-lg " type="number" id ="nextmps" name="nextmps" value = "{{ Session::get('nextmps') }}"></td>
                            <td><input style="width:100px" class="form-control form-control-lg " type="number" id ="nextday" name="nextday" value = "{{ Session::get('nextday') }}"></td>
                        </tr>

                    </table>
                    </div>
                    <br>
                    <input type = "submit" class="btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.submit') !!}">
                </form>
                <br>
                <button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('month.consumeadd')}}'">{!! __('monthlyPRpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
