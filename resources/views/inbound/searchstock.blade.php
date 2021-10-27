@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/inbound/stock.js') }}"></script>
@endsection
@section('content')
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>
<h2>{!! __('templateWords.inbound') !!}</h2>
<div class="row justify-content-center">
    <div class="card w-75">
        <div class="card-header">
            <h3>{!! __('inboundpageLang.searchstock') !!}</h3>
        </div>
        <div class="row justify-content-center">
            <div class="card-body">
                <div class="d-flex w-100">
                    <form action="{{ route('inbound.searchstocksubmit') }}" method="POST">
                        @csrf
                        <div class="row w-100 justify-content-center mb-3">
                            <label class="col col-auto form-label">{!! __('inboundpageLang.client') !!}</label>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="col-lg-6  col-md-12 col-sm-12">
                                <select class="form-select form-select-lg" id="client" name="client">
                                    <option style="display: none" disabled selected>{!!
                                        __('inboundpageLang.enterclient')
                                        !!}</option>
                                    @foreach($client as $client)
                                    <option>{{  $client->客戶 }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <label class="col col-auto form-label">{!! __('inboundpageLang.loc') !!}</label>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="col-lg-6  col-md-12 col-sm-12">
                                <select class="form-select form-select-lg" id="position" name="position">
                                    <option style="display: none" disabled selected>{!! __('inboundpageLang.enterloc')
                                        !!}
                                    </option>
                                    @foreach($position as $position)
                                    <option>{{  $position->儲存位置 }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <label class="col col-auto form-label">{!! __('inboundpageLang.isn') !!}</label>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="col-lg-6  col-md-12 col-sm-12">
                                <input class="form-control form-control-lg @error('number') is-invalid @enderror"
                                    type="text" id="number" name="number"
                                    placeholder="{!! __('inboundpageLang.enterisn') !!}">
                                @error('number')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <label class="col col-auto form-label">{!! __('inboundpageLang.senddep') !!}</label>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="col-lg-6  col-md-12 col-sm-12">
                                <select class="form-select form-select-lg" id="send" name="send">
                                    <option style="display: none" disabled selected>{!!
                                        __('inboundpageLang.entersenddep')
                                        !!}</option>
                                    @foreach($senddep as $senddep)
                                    <option>{{  $senddep->發料部門 }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <div class="row w-100 justify-content-center">
                                <div class="col col-auto">
                                    <input class="basic" type="checkbox" id="month" name="month"
                                        style="width:20px;height:20px;" value="1"> {!! __('inboundpageLang.stockmonth')
                                    !!}
                                    <div class="w-100" style="height: 1ch;"></div>
                                    <!-- </div>breaks cols to a new line-->
                                    <input class="basic" type="checkbox" id="nogood" name="nogood"
                                        style="width:20px;height:20px;" value="2"> {!! __('inboundpageLang.nogood') !!}
                                </div>
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="row w-100 justify-content-center">
                                <div class="col col-auto">
                                    <input type="submit" id="search" name="search" class="btn btn-lg btn-primary"
                                        value="{!! __('inboundpageLang.search') !!}">
                                </div>
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
