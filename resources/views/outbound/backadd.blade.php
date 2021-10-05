@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/outbound/backadd.js') }}"></script>
@endsection
@section('content')
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    </head>
        <h2>{!! __('templateWords.outbound') !!}</h2>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('outboundpageLang.back') !!}</h3>
            </div>
            <div class="card-body">
                <form id = "backadd">
                    @csrf
                    <div class="table-responsive">
                        <table class="table" id = "test">
                            <tr id = "require">
                                <th>{!! __('outboundpageLang.isn') !!}</th>
                                <th>{!! __('outboundpageLang.pName') !!}</th>
                                <th>{!! __('outboundpageLang.format') !!}</th>
                                <th>{!! __('outboundpageLang.unit') !!}</th>
                                <th>{!! __('outboundpageLang.senddep') !!}</th>
                                <th>{!! __('outboundpageLang.backamount') !!}</th>
                                <th>{!! __('outboundpageLang.mark') !!}</th>
                                <th>{!! __('outboundpageLang.client') !!}</th>
                                <th>{!! __('outboundpageLang.machine') !!}</th>
                                <th>{!! __('outboundpageLang.process') !!}</th>
                                <th>{!! __('outboundpageLang.line') !!}</th>
                                <th>{!! __('outboundpageLang.backreason') !!}</th>
                            </tr>

                            <tr>
                                <td><input type="hidden" id ="number" name="number"  value = "{{ Session::get('number') }}">{{ Session::get('number') }}</td>
                                <td><input type="hidden" id ="name" name="name"  value = "{{ Session::get('name') }}">{{ Session::get('name') }}</td>
                                <td><input type="hidden" id ="format" name="format"  value = "{{ Session::get('format') }}">{{ Session::get('format') }}</td>
                                <td><input type="hidden" id ="unit" name="unit"  value = "{{ Session::get('unit') }}">{{ Session::get('unit') }}</td>
                                <td><input type="hidden" id ="send" name="send"  value = "{{ Session::get('send') }}">{{ Session::get('send') }}</td>
                                <td><input type="number" id ="amount" name="amount" required placeholder="{!! __('outboundpageLang.enteramount') !!}"></td>
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
                    <input type = "submit" class="btn btn-lg btn-primary" value="{!! __('outboundpageLang.submit') !!}">
                </form>
                <br>
                <button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('outbound.index')}}'">{!! __('outboundpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
