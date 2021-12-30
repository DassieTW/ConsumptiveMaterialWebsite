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
            <h3>{!! __('monthlyPRpageLang.standUpdate') !!}</h3>
        </div>
        <div class="row justify-content-center">
            <div class="card-body">
                <div class=" w-100">
                    <form action="{{ route('month.standsearch') }}" method="POST">
                        @csrf
                        <div class="row w-100 justify-content-center mb-3">
                            <label class="col col-auto form-label">{!! __('monthlyPRpageLang.client') !!}</label>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="col-lg-6  col-md-12 col-sm-12">
                                <select class="form-select form-select-lg col col-auto" id="client" name="client">
                                    <option style="display: none" disabled selected>{!!
                                        __('monthlyPRpageLang.enterclient') !!}</option>
                                    @foreach($client as $client)
                                    <option>{{  $client->客戶 }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <label class="col col-auto form-label">{!! __('monthlyPRpageLang.machine') !!}</label>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="col-lg-6  col-md-12 col-sm-12">
                                <select class="form-select form-select-lg col col-auto" id="machine" name="machine">
                                    <option style="display: none" disabled selected>{!!
                                        __('monthlyPRpageLang.entermachine') !!}</option>
                                    @foreach($machine as $machine)
                                    <option>{{  $machine->機種 }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <label class="col col-auto form-label">{!! __('monthlyPRpageLang.process') !!}</label>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="col-lg-6  col-md-12 col-sm-12">
                                <select class="form-select form-select-lg col col-auto" id="production"
                                    name="production">
                                    <option style="display: none" disabled selected value="">{!!
                                        __('monthlyPRpageLang.enterprocess') !!}</option>
                                    @foreach($production as $production)
                                    <option>{{  $production->制程 }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <label class="col col-auto form-label">{!! __('monthlyPRpageLang.isn') !!}</label>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
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

                            <label class="col col-auto form-label">{!! __('monthlyPRpageLang.senddep') !!}</label>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="col-lg-6  col-md-12 col-sm-12">
                                <select class="form-select form-select-lg col col-auto" id="send" name="send">
                                    <option style="display: none" disabled selected>{!!
                                        __('monthlyPRpageLang.entersenddep') !!}</option>
                                    @foreach($send as $send)
                                    <option>{{  $send->發料部門 }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

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
</div>

</html>
@endsection
