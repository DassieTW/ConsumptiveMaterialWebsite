@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/month/standadd.js') }}"></script>
@endsection
@section('content')
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>
<h2>{!! __('templateWords.monthly') !!}</h2>
<div id="url"></div>
<div class="card w-100" id="standhead">
    <div class="card-header">
        <h3>{!! __('monthlyPRpageLang.standAdd') !!}</h3>
    </div>
    <div class="card-body">
        <form id="stand" class="row gx-6 gy-1 align-items-center">
            @csrf
            <div class="col-auto">
                <label class="col col-lg-12 form-label">{!! __('outboundpageLang.client') !!}</label>
                <select class="form-select form-select-lg" id="client" name="client" required>
                    <option style="display: none" disabled selected value="">{!!
                        __('outboundpageLang.enterclient') !!}</option>
                    @foreach($client as $client)
                    <option>{{ $client->客戶 }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <label class="col col-lg-12 form-label">{!! __('outboundpageLang.machine') !!}</label>
                <select class="form-select form-select-lg" id="machine" name="machine" required>
                    <option style="display: none" disabled selected value="">{!!
                        __('outboundpageLang.entermachine') !!}</option>
                    @foreach($machine as $machine)
                    <option>{{ $machine->機種 }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <label class="col col-lg-12 form-label">{!! __('outboundpageLang.process') !!}</label>
                <select class="form-select form-select-lg " id="production" name="production" required>
                    <option style="display: none" disabled selected value="">{!!
                        __('outboundpageLang.enterprocess') !!}</option>
                    @foreach($production as $production)
                    <option>{{ $production->制程 }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-auto">
                <label class="col col-auto form-label">{!! __('outboundpageLang.isn') !!}</label>
                <input class="form-control form-control-lg " type="text" id="number" name="number" required
                    placeholder="{!! __('outboundpageLang.enterisn') !!}"
                    oninput="if(value.length>12)value=value.slice(0,12)">
                <div id="numbererror" style="display:none; color:red;">{!!
                    __('outboundpageLang.isnlength')!!}</div>
                <div id="numbererror1" style="display:none; color:red;">{!! __('outboundpageLang.noisn') !!}
                </div>
            </div>
            <div class="col-auto">
                <label class="col col-auto form-label"></label>
                <input type="submit" id="add" name="add" class="form-control form-control-lg btn btn-lg btn-primary"
                    value="{!! __('outboundpageLang.add') !!}">
            </div>
        </form>
    </div>
</div>

<div class="card w-100" id="standbody">
    <div class="card-body">
        <form id="standadd" style="display: none">
            @csrf
            <div class="table-responsive">
                <table class="table" id="standaddtable">
                    <tbody id="standaddbody">
                        <tr>
                            <th>{!! __('monthlyPRpageLang.delete') !!}</th>
                            <th>{!! __('monthlyPRpageLang.isn') !!}</th>
                            <th>{!! __('monthlyPRpageLang.pName') !!}</th>
                            <th>{!! __('monthlyPRpageLang.unit') !!}</th>
                            <th>{!! __('monthlyPRpageLang.mpq') !!}</th>
                            <th>{!! __('monthlyPRpageLang.lt') !!}</th>
                            <th>{!! __('monthlyPRpageLang.nowpeople') !!}</th>
                            <th>{!! __('monthlyPRpageLang.nowline') !!}</th>
                            <th>{!! __('monthlyPRpageLang.nowclass') !!}</th>
                            <th>{!! __('monthlyPRpageLang.nowuse') !!}</th>
                            <th>{!! __('monthlyPRpageLang.nowchange') !!}</th>
                            <th>{!! __('monthlyPRpageLang.nowdayneed') !!}</th>
                            <th>{!! __('monthlyPRpageLang.nextpeople') !!}</th>
                            <th>{!! __('monthlyPRpageLang.nextline') !!}</th>
                            <th>{!! __('monthlyPRpageLang.nextclass') !!}</th>
                            <th>{!! __('monthlyPRpageLang.nextuse') !!}</th>
                            <th>{!! __('monthlyPRpageLang.nextchange') !!}</th>
                            <th>{!! __('monthlyPRpageLang.nextdayneed') !!}</th>
                            <th>{!! __('monthlyPRpageLang.safe') !!}</th>
                            <th>{!! __('monthlyPRpageLang.client') !!}</th>
                            <th>{!! __('monthlyPRpageLang.machine') !!}</th>
                            <th>{!! __('monthlyPRpageLang.process') !!}</th>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <label class="form-label">{!! __('monthlyPRpageLang.surepeople') !!}:</label>
            <input type="text" id="jobnumber" name="jobnumber" required oninput="if(value.length>9)value=value.slice(0,9)">

            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <label class="form-label">{!! __('monthlyPRpageLang.surepeopleemail') !!}:</label>
            <input type="email" id="email" name="email" pattern=".+@pegatroncorp\.com" required
                placeholder="xxx@pegatroncorp.com">

            <div class="row w-100 justify-content-center">
                <div class="col col-auto">
                    <input type="submit" class="btn btn-lg btn-primary" value="{!! __('outboundpageLang.submit') !!}"
                        style="width: 80px">
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row justify-content-center">
    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
    <div class="card w-75" id="standupload">
        <div class="card-header">
            <h3>{!! __('monthlyPRpageLang.upload') !!}</h3>
        </div>
        <div class="row justify-content-center">
            <div class="card-body">
                <div class=" w-100">

                    <form method="post" enctype="multipart/form-data" action="{{ route('month.uploadstand') }}">
                        @csrf
                        <div class="row w-100 justify-content-center mb-3">
                            <div class="col col-auto ">
                                <a href="{{asset('download/StandExample.xlsx')}}" download>{!!
                                    __('monthlyPRpageLang.exampleExcel') !!}</a>
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <label class="col col-auto form-label">{!! __('monthlyPRpageLang.plz_upload') !!}</label>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <div class="col col-auto">
                                <input class=" form-control @error('select_file') is-invalid @enderror" type="file"
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
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</html>
@endsection
