@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')

<script src="{{ asset('js/obound/inbound.js') }}"></script>
<!--for notifications pop up -->
@endsection
@section('content')
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>
<h2>{!! __('templateWords.obound') !!}</h2>
<div class="row justify-content-center">
    <div class="card w-75">
        <div class="card-header">
            <h3>{!! __('oboundpageLang.inbound') !!}</h3>
        </div>
        <div class="row justify-content-center">
            <div class="card-body">
                <form id="test">
                    @csrf
                    <div class="row w-100 justify-content-center mb-3">
                        <label class="col col-auto form-label">{!! __('oboundpageLang.client') !!}</label>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <div class="col-lg-6  col-md-12 col-sm-12">
                            <select class="form-select form-select-lg" id="client" name="client" required>
                                <option style="display: none" disabled selected value="">{!!
                                    __('oboundpageLang.enterclient') !!}</option>
                                @foreach($client as $client)
                                <option>{{ $client->客戶 }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        <label class="col col-auto form-label">{!! __('oboundpageLang.inreason') !!}</label>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <div class="col-lg-6  col-md-12 col-sm-12">
                            <select class="form-select form-select-lg " id="inreason" name="inreason" required>
                                <option style="display: none" disabled selected value="">{!!
                                    __('oboundpageLang.enterinreason') !!}</option>
                                @foreach($inreason as $inreason)
                                <option>{{ $inreason->入庫原因 }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        <label class="col col-auto form-label">{!! __('oboundpageLang.oisn') !!}</label>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <div class="col-lg-6  col-md-12 col-sm-12">

                            <input class="form-control form-control-lg " type="text" id="number" name="number" required
                                placeholder="{!!
                                __('oboundpageLang.enterisn') !!}">
                            <div id="numbererror" style="display:none; color:red;">{!! __('oboundpageLang.noisn') !!}
                            </div>
                        </div>

                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        <div class="row w-100 justify-content-center">
                            <div class="col col-auto">
                                <input type="submit" class="btn btn-lg btn-primary"
                                    value="{!! __('oboundpageLang.add') !!}">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</html>
@endsection
