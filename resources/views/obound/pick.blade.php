@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')

<script src="{{ asset('js/obound/pick.js') }}"></script>
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
<div class="row justify-content-center">
    <div class="card w-75">
        <div class="card-header">
            <h3>{!! __('oboundpageLang.pick') !!}</h3>
        </div>
        <div class="row justify-content-center">
            <div class="card-body">
                <form id="pick">
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

                        <label class="col col-auto form-label">{!! __('oboundpageLang.machine') !!}</label>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <div class="col-lg-6  col-md-12 col-sm-12">
                            <select class="form-select form-select-lg" id="machine" name="machine" required>
                                <option style="display: none" disabled selected value="">{!!
                                    __('oboundpageLang.entermachine') !!}</option>
                                @foreach($machine as $machine)
                                <option>{{ $machine->機種 }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        <label class="col col-auto form-label">{!! __('oboundpageLang.process') !!}</label>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <div class="col-lg-6  col-md-12 col-sm-12">
                            <select class="form-select form-select-lg " id="production" name="production" required>
                                <option style="display: none" disabled selected value="">{!!
                                    __('oboundpageLang.enterprocess') !!}</option>
                                @foreach($production as $production)
                                <option>{{ $production->製程 }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        <label class="col col-auto form-label">{!! __('oboundpageLang.line') !!}</label>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <div class="col-lg-6  col-md-12 col-sm-12">
                            <select class="form-select form-select-lg " id="line" name="line" required>
                                <option style="display: none" disabled selected value="">{!!
                                    __('oboundpageLang.enterline')
                                    !!}</option>
                                @foreach($line as $line)
                                <option>{{ $line->線別 }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        <label class="col col-auto form-label">{!! __('oboundpageLang.usereason') !!}</label>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <div class="col-lg-6  col-md-12 col-sm-12">
                            <select class="form-select form-select-lg " id="usereason" name="usereason" required>
                                <option style="display: none" disabled selected value="">{!!
                                    __('oboundpageLang.enterusereason') !!}</option>
                                @foreach($usereason as $usereason)
                                <option>{{ $usereason->領用原因 }}</option>
                                @endforeach
                                <option>{!! __('oboundpageLang.other') !!}</option>
                            </select>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <input class="form-control form-control-lg " style="display:none;" type="text" id="reason"
                                name="reason" placeholder="{!! __('oboundpageLang.inputusereason') !!}">
                        </div>

                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        <label class="col col-auto form-label">{!! __('oboundpageLang.isn') !!}</label>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <div class="col-lg-6  col-md-12 col-sm-12">
                            <input class="form-control form-control-lg " type="text" id="number" name="number" required placeholder="{!! __('oboundpageLang.enterisn') !!}">
                            <div id="numbererror1" style="display:none; color:red;">{!! __('oboundpageLang.noisn') !!}
                            </div>
                            <div id="nostock" style="display:none; color:red;">{!! __('oboundpageLang.nostock') !!}
                            </div>
                        </div>
                    </div>
                    <div class="row w-100 justify-content-center">
                        <div class="col col-auto">
                            <input type="submit" id="submit" name="submit" class="btn btn-lg btn-primary"
                                value="{!! __('oboundpageLang.submit') !!}">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</html>
@endsection
