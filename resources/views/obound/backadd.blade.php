@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<script src="{{ asset('js/obound/backadd.js') }}"></script>
<!-- <script src="{{ asset('js/popupNotice.js') }}"></script> -->
<!--for notifications pop up -->
@endsection
@section('content')
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    </head>
        <h2>{!! __('templateWords.obound') !!}</h2>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('oboundpageLang.back') !!}</h3>
            </div>
            <div class="card-body">
                <form id = "backadd">
                    @csrf
                    <div class="table-responsive">
                        <table class="table" id = "test">
                            <tr id = "require">
                                <th>{!! __('oboundpageLang.isn') !!}</th>
                                <th>{!! __('oboundpageLang.pName') !!}</th>
                                <th>{!! __('oboundpageLang.format') !!}</th>
                                <th>{!! __('oboundpageLang.backamount') !!}</th>
                                <th>{!! __('oboundpageLang.mark') !!}</th>
                                <th>{!! __('oboundpageLang.client') !!}</th>
                                <th>{!! __('oboundpageLang.machine') !!}</th>
                                <th>{!! __('oboundpageLang.process') !!}</th>
                                <th>{!! __('oboundpageLang.line') !!}</th>
                                <th>{!! __('oboundpageLang.backreason') !!}</th>
                            </tr>

                            <tr>
                                <td><input type="hidden" id ="number" name="number"  value = "{{ Session::get('number') }}">{{ Session::get('number') }}</td>
                                <td><input type="hidden" id ="name" name="name"  value = "{{ Session::get('name') }}">{{ Session::get('name') }}</td>
                                <td><input type="hidden" id ="format" name="format"  value = "{{ Session::get('format') }}">{{ Session::get('format') }}</td>
                                <td><input type="number" id ="amount" name="amount" required placeholder="{!! __('oboundpageLang.enteramount') !!}"></td>
                                <td><input type="text" id ="remark" name="remark"></td>
                                <td><input type="hidden" id ="client" name="client"  value = "{{ Session::get('client') }}">{{ Session::get('client') }}</td>
                                <td><input type="hidden" id ="machine" name="machine"  value = "{{ Session::get('machine') }}">{{ Session::get('machine') }}</td>
                                <td><input type="hidden" id ="production" name="production"  value = "{{ Session::get('production') }}">{{ Session::get('production') }}</td>
                                <td><input type="hidden" id ="line" name="line"  value = "{{ Session::get('line') }}">{{ Session::get('line') }}</td>
                                <td><input type="hidden" id ="backreason" name="backreason"  value = "{{ Session::get('backreason') }}">{{ Session::get('backreason') }}</td>
                            </tr>

                        </table>

                    </div>
                    <br>
                    <input type = "submit" class="btn btn-lg btn-primary" value="{!! __('oboundpageLang.submit') !!}">
                </form>
                <br>
                <button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.index')}}'">{!! __('oboundpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
