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
<div id="mountingPoint">
    <div class="row mb-2 mb-xl-3 justify-content-between">
        <h2 class="col-auto">{!! __('templateWords.monthly') !!}</h2>
        <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
            <vue-bread-crumb></vue-bread-crumb>
        </div>
    </div>
</div>
<div class="card w-100">
    <div class="card-header">
        <h3>{!! __('monthlyPRpageLang.importNonMonthlyData') !!}</h3>
    </div>
    <div class="card-body">
        <form id="notmonth" class="row gx-6 gy-1 align-items-center">
            @csrf
            <div class="col-auto">
                <label class="col col-lg-12 form-label">{!! __('outboundpageLang.client') !!}</label>
                <select class="form-select form-select-lg" id="client" name="client" required>
                    <option style="display: none" disabled selected value="">{!!
                        __('outboundpageLang.enterclient') !!}</option>
                    @foreach($showclient as $cli)
                    <option>{{ $cli->客戶 }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <label class="col col-auto form-label">{!! __('outboundpageLang.isn') !!}</label>
                <input class="form-control form-control-lg " type="text" id="number" name="number" required
                    placeholder="{!! __('outboundpageLang.enterisn') !!}"
                    oninput="if(value.length>12)value=value.slice(0,12)">
                <div id="numbererror1" style="display:none; color:red;">{!!
                    __('inboundpageLang.isnlength')
                    !!}</div>
                <div id="numbererror2" style="display:none; color:red;">{!!
                    __('inboundpageLang.noisn')
                    !!}</div>
            </div>

            <div class="col-auto">
                <label class="col col-auto form-label"></label>
                <input type="submit" id="add" name="add" class="form-control form-control-lg btn btn-lg btn-primary"
                    value="{!! __('outboundpageLang.add') !!}">
            </div>
        </form>
    </div>
</div>
<div class="card w-100">
    <div class="card-body">
        <div style="display: none">
            <select style="width: 150px;" class="form-select form-select-lg " id="reason" name="reason">
                <option style="display: none" disabled selected value="">{!!
                    __('monthlyPRpageLang.entercontrol')
                    !!}</option>
                <option>品質問題</option>
                <option>MPS上升</option>
                <option>其他</option>
            </select>
        </div>
        <form id="notmonthadd">
            @csrf
            <div class="table-responsive">
                <table class="table" id="notmonthtable">
                    <tbody id="notmonthbody">
                        <tr>
                            <td>{!! __('monthlyPRpageLang.delete') !!}</td>
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
                            <td><a id="deleteBtn0" href="javascript:deleteBtn(0)"><svg width="16" height="16"
                                        fill="#c94466" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                                        <path
                                            d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z">
                                        </path>
                                    </svg></a></td>
                            <td><span id="client0">{{$client}}</span></td>
                            <td><span id="number0">{{$number}}</span></td>
                            <td><input type="number" class="form-control form-control-lg" id="amount0" name="amount0"
                                    required placeholder="{!! __('monthlyPRpageLang.enteramount') !!}" min="1"></td>
                            <td><input type="text" class="form-control form-control-lg" id="sxb0" name="sxb0" required
                                    placeholder="{!! __('monthlyPRpageLang.entersxb') !!}"></td>
                            <td><input type="text" class="form-control form-control-lg" id="say0" name="say0"
                                    placeholder="{!! __('monthlyPRpageLang.enterdesc') !!}"></td>
                            <td><span id="name0">{{$name}}</span></td>
                            <td><span id="unit0">{{$unit}}</span></td>
                            <td><span id="month0">{{$month}}</span></td>
                            <td>
                                <select class="form-select form-select-lg " id="reason0" name="reason0">
                                    <option style="display: none" disabled selected value="">{!!
                                        __('monthlyPRpageLang.entercontrol')
                                        !!}</option>
                                    <option>品質問題</option>
                                    <option>MPS上升</option>
                                    <option>其他</option>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <div id="error" style="display:none;">
                <h3 id="errorrow" style="color: red"></h3>
                <h3 style="color: red">{!! __('monthlyPRpageLang.errormonth') !!}</h3>
            </div>

            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <input type="submit" id="submit" name="submit" class="btn btn-lg btn-primary"
                value="{!! __('monthlyPRpageLang.submit') !!}">
        </form>

        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
        {{-- <button class="btn btn-lg btn-primary" onclick="location.href='{{route('month.importnotmonth')}}'">{!!
            __('monthlyPRpageLang.return') !!}</button> --}}
    </div>
</div>
</div>
</html>
@endsection
