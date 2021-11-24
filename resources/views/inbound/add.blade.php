@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/inbound/add.js') }}"></script>
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
            <h3>{!! __('inboundpageLang.new') !!}</h3>
        </div>
        <div class="row justify-content-center">
            <div class="card-body">
                <form id="add">
                    @csrf
                    <div class="row w-100 justify-content-center mb-3">

                        <label class="col col-auto form-label">{!! __('inboundpageLang.client') !!}</label>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <div class="col-lg-6  col-md-12 col-sm-12">
                            <select class="form-select form-select-lg" id="client" name="client" required>
                                <option style="display: none" disabled selected value="">{!!
                                    __('inboundpageLang.enterclient') !!}</option>
                                @foreach($client as $client)
                                <option>{{ $client->客戶 }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        <label class="col col-auto form-label">{!! __('inboundpageLang.inreason') !!}</label>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <div class="col-lg-6  col-md-12 col-sm-12">
                            <select class="form-select form-select-lg " id="inreason" name="inreason" required>
                                <option style="display: none" disabled selected value="">{!!
                                    __('inboundpageLang.enterinreason') !!}</option>
                                @foreach($inreason as $inreason)
                                <option>{{ $inreason->入庫原因 }}</option>
                                @endforeach
                                <option>{!! __('inboundpageLang.other') !!}</option>
                            </select>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <input class="form-control form-control-lg " style="display:none;" type="text" id="reason"
                                name="reason" placeholder="{!! __('inboundpageLang.inputinreason') !!}">

                        </div>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        <label class="col col-auto form-label">{!! __('inboundpageLang.isn') !!}</label>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <div class="col-lg-6  col-md-12 col-sm-12">
                            <input class="form-control form-control-lg " type="text" id="number" name="number"
                                placeholder="{!! __('inboundpageLang.enterisn') !!}">

                            <div id="numbererror" style="display:none; color:red;">{!!
                                __('inboundpageLang.isnlength')
                                !!}</div>
                            <div id="numbererror1" style="display:none; color:red;">{!! __('inboundpageLang.noisn')
                                !!}
                            </div>

                            <div id="notransit" style="display:none; color:red;">{!! __('inboundpageLang.notransit')
                                !!}
                            </div>
                        </div>

                    </div>
                    <div class="row w-100 justify-content-center">
                        <div class="col col-auto">
                            <input type="submit" onclick="buttonIndex=0;" id="addto" name="addto"
                                class="btn btn-lg btn-primary" value="{!! __('inboundpageLang.add') !!}">
                        </div>
                    </div>

                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                    <div class="row w-100 justify-content-center">
                        <div class="col col-auto">
                            <input type="submit" onclick="buttonIndex=1;" id="addclient" name="addclient"
                                class="btn btn-lg btn-primary" value="{!! __('inboundpageLang.addclient') !!}">
                        </div>
                    </div>
                </form>
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            </div>
        </div>
    </div>
</div>

</html>
@endsection
