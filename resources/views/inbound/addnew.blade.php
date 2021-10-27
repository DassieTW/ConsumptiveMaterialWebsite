@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/inbound/addnew.js') }}"></script>
@endsection
@section('content')
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>
<h2>{!! __('templateWords.inbound') !!}</h2>
<div class="card">
    <div class="card-header">
        <h3>{!! __('inboundpageLang.new') !!}</h3>
    </div>
    <div class="card-body">
        <form id="addnew">
            @csrf
            <div class="table-responsive">
                <table class="table" id="inboundsearch">
                    <tr>
                        <th>{!! __('inboundpageLang.client') !!}</th>
                        <th>{!! __('inboundpageLang.isn') !!}</th>
                        <th>{!! __('inboundpageLang.pName') !!}</th>
                        <th>{!! __('inboundpageLang.format') !!}</th>
                        <th>{!! __('inboundpageLang.unit') !!}</th>
                        <th>{!! __('inboundpageLang.transit') !!}</th>
                        <th>{!! __('inboundpageLang.nowstock') !!}</th>
                        <th>{!! __('inboundpageLang.safe') !!}</th>
                        <th>{!! __('inboundpageLang.inboundnum') !!}</th>
                        <th>{!! __('inboundpageLang.inreason') !!}</th>
                        <th>{!! __('inboundpageLang.oldloc') !!}</th>
                        <th>{!! __('inboundpageLang.newloc') !!}</th>
                    </tr>

                    <tr>
                        <td><input type="hidden" id="client" name="client" value="{{ Session::get('client') }}">{{
                            Session::get('client') }}</td>
                        <td><input type="hidden" id="number" name="number" value="{{ Session::get('number') }}">{{
                            Session::get('number') }}</td>
                        <td><input type="hidden" id="name" name="name" value="{{ Session::get('name') }}">{{
                            Session::get('name') }}</td>
                        <td><input type="hidden" id="format" name="format" value="{{ Session::get('format') }}">{{
                            Session::get('format') }}</td>
                        <td><input type="hidden" id="unit" name="unit" value="{{ Session::get('unit') }}">{{
                            Session::get('unit') }}</td>
                        <td><input type="hidden" id="amount" name="amount" value="{{ Session::get('amount') }}">{{
                            Session::get('amount') }}</td>
                        <td><input type="hidden" id="stock" name="stock" value="{{ Session::get('stock') }}">{{
                            Session::get('stock') }}</td>
                        <td><input type="hidden" id="safe" name="safe" value="{{ Session::get('safe') }}">{{
                            Session::get('safe') }}</td>
                        <td><input type="number" id="inamount" name="inamount"
                                placeholder="{!! __('inboundpageLang.enteramount') !!}" required></td>
                        <td><input type="hidden" id="inreason" name="inreason" value="{{ Session::get('inreason') }}">{{
                            Session::get('inreason') }}</td>
                        <td><input type="hidden" id="oldposition" name="oldposition"
                                value="{{ Session::get('positions') }}">
                            @foreach(Session::get('positions') as $oldloc)
                            {{ $oldloc }}
                            <br>
                            @endforeach

                        </td>
                        <div style="display: none" id="showposition">{{ $position = App\Models\儲位::all() }}</div>
                        <div style="display: none" id="showname">{{ $name = App\Models\人員信息::all() }}</div>
                        <td>
                            <select class="form-select form-select-lg" id="newposition" name="newposition" style="width: 250px" required>
                                <option style="display: none" disabled selected value="">{!!
                                    __('inboundpageLang.enterloc') !!}</option>
                                @foreach($position as $position)
                                <option>{{ $position->儲存位置 }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                </table>
            </div>
            <br>
            <div class="mb-3 col-md-6">
                <label class="form-label">{!! __('inboundpageLang.inpeople') !!}</label>
                <select class="form-select form-select-lg" id="inpeople" name="inpeople" required width="300"
                    style="width: 300px">
                    <option style="display: none" disabled selected value="">{!! __('inboundpageLang.enterinpeople') !!}
                    </option>
                    @foreach($name as $name)
                    <option>{{ $name->工號 .' '. $name->姓名 }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display: none ; color:red;" id="nostock">{!! __('inboundpageLang.transiterror') !!}</div>
            <input type="submit" class="btn btn-lg btn-primary" value="{!! __('inboundpageLang.submit') !!}">
        </form>
        <br>
        <button type="submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('inbound.add')}}'">{!!
            __('inboundpageLang.return') !!}</button>
    </div>
</div>

</html>
@endsection
