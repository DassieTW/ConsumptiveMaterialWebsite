@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/month/consumeadd.js') }}"></script>
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
            <h3>{!! __('monthlyPRpageLang.isnConsumeAdd') !!}</h3>
        </div>
        <div class="row justify-content-center">
            <div class="card-body">
                <div class=" w-100">
                    <form id="consumeadd" method="POST">
                        @csrf
                        <div class="row w-100 justify-content-center mb-3">
                            <label class="col col-auto form-label">{!! __('monthlyPRpageLang.client') !!}</label>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="col-lg-6  col-md-12 col-sm-12">
                                <select class="form-select form-select-lg col col-auto" id="client" name="client"
                                    required>
                                    <option style="display: none" disabled selected value="">{!!
                                        __('monthlyPRpageLang.enterclient')!!}</option>
                                    @foreach($client as $client)
                                    <option>{{  $client->客戶 }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <label class="col col-auto form-label">{!! __('monthlyPRpageLang.machine') !!}</label>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="col-lg-6  col-md-12 col-sm-12">
                                <select class="form-select form-select-lg col col-auto" id="machine" name="machine"
                                    required>
                                    <option style="display: none" disabled selected value="">{!!
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
                                    name="production" required>
                                    <option style="display: none" disabled selected value="">{!!
                                        __('monthlyPRpageLang.enterprocess') !!}</option>
                                    @foreach($production as $production)
                                    <option>{{  $production->製程 }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <label class="col col-auto form-label">{!! __('monthlyPRpageLang.isn') !!}</label>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="col-lg-6  col-md-12 col-sm-12">
                                <input class="form-control form-control-lg " type="text" id="number" name="number"
                                    required placeholder="{!! __('monthlyPRpageLang.enterisn') !!}">
                                <div id="numbererror" style="display:none; color:red;">{!!
                                    __('monthlyPRpageLang.isnlength') !!}
                                </div>
                                <div id="numbererror1" style="display:none; color:red;">{!!
                                    __('monthlyPRpageLang.noisn') !!}</div>
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        </div>

                        <div class="row w-100 justify-content-center">
                            <div class="col col-auto">
                                <input type="submit" id="search" name="search" class="btn btn-lg btn-primary"
                                    value="{!! __('monthlyPRpageLang.add') !!}">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

    <div class="card w-75">
        <div class="card-header">
            <h3>{!! __('monthlyPRpageLang.upload') !!}</h3>
        </div>

        <div class="row justify-content-center">
            <div class="card-body">
                <div class=" w-100">
                    <form method="post" enctype="multipart/form-data" action="{{ route('month.uploadconsume') }}">
                        @csrf
                        <div class="row w-100 justify-content-center mb-3">
                            <div class="col col-auto ">
                                <a href="{{asset('download/ConsumeExample.xlsx')}}" download>{!!
                                    __('monthlyPRpageLang.exampleExcel') !!}</a>
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <label class="col col-auto form-label">{!! __('monthlyPRpageLang.plz_upload') !!}</label>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <div class="col col-auto">
                                <input class="form-control @error('select_file') is-invalid @enderror" type="file"
                                    name="select_file" />
                                @error('select_file')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="row w-100 justify-content-center">
                                <div class="col col-auto">
                                    <input type="submit" name="upload" class="btn btn-lg btn-primary"
                                        value="{!! __('monthlyPRpageLang.upload1') !!}">
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
