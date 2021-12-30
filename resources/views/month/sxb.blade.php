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
<div class="row justify-content-center">
    <div class="card w-75">
        <div class="card-header">
            <h3>{!! __('monthlyPRpageLang.SXB_search') !!}</h3>
        </div>
        <div class="row justify-content-center">
            <div class="card-body">
                <div class="d-flex w-100">
                    <form action="{{ route('month.sxbsearch') }}" method="POST">
                        @csrf
                        <div class="row w-100 justify-content-center mb-3">
                            <label class="col col-auto form-label p-0 m-0">{!! __('monthlyPRpageLang.client') !!}</label>
                            <div class="w-100" style="height: 0ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="col-lg-6  col-md-12 col-sm-12">
                                <select class="form-select form-select-lg col col-auto" id="client" name="client">
                                    <option style="display: none" disabled selected>{!!
                                        __('monthlyPRpageLang.enterclient') !!}</option>
                                    @foreach($client as $client)
                                    <option>{{ $client->客戶 }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <label class="col col-auto form-label p-0 m-0">{!! __('monthlyPRpageLang.isn') !!}</label>
                            <div class="w-100" style="height: 0ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="col-lg-6  col-md-12 col-sm-12">
                                <input class="form-control form-control-lg @error('number') is-invalid @enderror"
                                    type="text" id="number" name="number"
                                    placeholder="{!! __('monthlyPRpageLang.enterisn') !!}" oninput="if(value.length>12)value=value.slice(0,12)">
                                @error('number')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <label class="col col-auto form-label p-0 m-0">{!! __('monthlyPRpageLang.sxb') !!}</label>
                            <div class="w-100" style="height: 0ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="col-lg-6  col-md-12 col-sm-12">
                                <input class="form-control form-control-lg @error('sxb') is-invalid @enderror"
                                    type="text" id="sxb" name="sxb"
                                    placeholder="{!! __('monthlyPRpageLang.entersxb') !!}" >
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <label class="col col-auto form-label p-0 m-0">{!! __('monthlyPRpageLang.senddep') !!}</label>
                            <div class="w-100" style="height: 0ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="col-lg-6  col-md-12 col-sm-12">
                                <select class="form-select form-select-lg" id="send" name="send">
                                    <option style="display: none" disabled selected>{!!
                                        __('monthlyPRpageLang.entersenddep') !!}</option>
                                    @foreach($send as $send)
                                    <option>{{ $send->發料部門 }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <div class="row w-100 justify-content-center align-items-center">
                                <input class="basic col col-auto p-0 m-0" type="checkbox" id="date" name="date"
                                    style="width:20px;height:20px;">
                                <span class="col col-auto p-1 m-0">{!! __('monthlyPRpageLang.timepart') !!}</span>
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <div class="row w-100 justify-content-center">
                                <div class="col col-auto">
                                    <label class="col col-auto form-label">{!! __('monthlyPRpageLang.begindate')
                                        !!}</label>:
                                    <input class = "form-control form-control-lg" type="date" id="begin" name="begin" value="<?php echo date('Y-m-d'); ?>" />
                                </div>
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <div class="row w-100 justify-content-center">
                                <div class="col col-auto">
                                    <label class="col col-auto form-label">{!! __('monthlyPRpageLang.enddate')
                                        !!}</label>:
                                    <input class = "form-control form-control-lg" type="date" id="end" name="end" value="<?php echo date('Y-m-d'); ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="row w-100 justify-content-center">
                            <div class="col col-auto">
                                <input type="submit" id="search" name="search" class="btn btn-lg btn-primary"
                                    value="{!! __('monthlyPRpageLang.search') !!}">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</html>
@endsection
