@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/outbound/pickadd.js') }}"></script>
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
        <h3>{!! __('outboundpageLang.pick') !!}</h3>
    </div>
    <div class="card-body">
        <form id="pickadd">
            @csrf
            <div class="table-responsive">
                <table class="table" id="test">
                    <tr id="require">
                        <th>{!! __('outboundpageLang.isn') !!}</th>
                        <th>{!! __('outboundpageLang.pName') !!}</th>
                        <th>{!! __('outboundpageLang.format') !!}</th>
                        <th>{!! __('outboundpageLang.unit') !!}</th>
                        <th>{!! __('outboundpageLang.senddep') !!}</th>
                        <th>{!! __('outboundpageLang.pickamount') !!}</th>
                        <th>{!! __('outboundpageLang.mark') !!}</th>
                        <th>{!! __('outboundpageLang.client') !!}</th>
                        <th>{!! __('outboundpageLang.machine') !!}</th>
                        <th>{!! __('outboundpageLang.process') !!}</th>
                        <th>{!! __('outboundpageLang.line') !!}</th>
                        <th>{!! __('outboundpageLang.usereason') !!}</th>
                    </tr>


                    @for ($i = 0 ; $i < $request->session()->get('pickcount') ; $i++)
                    <tr>
                        <td><input type="text" id="number{{$i}}" name="number{{$i}}" value=""></td>
                        <td><input type="text" id="name{{$i}}" name="name{{$i}}" value=""></td>
                        <td><input type="text" id="format{{$i}}" name="format{{$i}}" value=""></td>
                        <td><input type="text" id="unit{{$i}}" name="unit{{$i}}" value=""></td>
                        <td><input type="text" id="send{{$i}}" name="send{{$i}}" value=""></td>
                        <td><input type="text" id="amount{{$i}}" name="amount{{$i}}" value=""></td>
                        <td><input type="text" id="remark{{$i}}" name="remark{{$i}}" value=""></td>
                        <td><input type="text" id="client{{$i}}" name="client{{$i}}" value=""></td>
                        <td><input type="text" id="machine{{$i}}" name="machine{{$i}}" value=""></td>
                        <td><input type="text" id="production{{$i}}" name="production{{$i}}" value=""></td>
                        <td><input type="text" id="line{{$i}}" name="line{{$i}}" value=""></td>
                        <td><input type="text" id="usereason{{$i}}" name="usereason{{$i}}" value=""></td>
                        <td><input type="text" id="count" name="count" value="{{$pickcount}}"></td>
                    </tr>
                    @endfor

                </table>
            </div>
            <br>





            <input type="submit" class="btn btn-lg btn-primary" value="{!! __('outboundpageLang.submit') !!}">
        </form>
        <br>
        <button type="submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('outbound.pick')}}'">{!!
            __('outboundpageLang.return') !!}</button>
    </div>
</div>

</html>
@endsection
