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
<div id="url"></div>
<div class="card w-100" id="consumehead">
    <div class="card-header">
        <h3>{!! __('monthlyPRpageLang.isnConsumeAdd') !!}</h3>
    </div>
    <div class="card-body">
        <form id="consume" class="row gx-6 gy-1 align-items-center">
            @csrf
            <div class="col-auto">
                <label class="col col-lg-12 form-label">{!! __('monthlyPRpageLang.client') !!}</label>
                <select class="form-select form-select-lg" id="client" name="client" required>
                    <option style="display: none" disabled selected value="">{!!
                        __('monthlyPRpageLang.enterclient') !!}</option>
                    @foreach($client as $client)
                    <option>{{ $client->客戶 }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <label class="col col-lg-12 form-label">{!! __('monthlyPRpageLang.machine') !!}</label>
                <select class="form-select form-select-lg" id="machine" name="machine" required>
                    <option style="display: none" disabled selected value="">{!!
                        __('monthlyPRpageLang.entermachine') !!}</option>
                    @foreach($machine as $machine)
                    <option>{{ $machine->機種 }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <label class="col col-lg-12 form-label">{!! __('monthlyPRpageLang.process') !!}</label>
                <select class="form-select form-select-lg " id="production" name="production" required>
                    <option style="display: none" disabled selected value="">{!!
                        __('monthlyPRpageLang.enterprocess') !!}</option>
                    @foreach($production as $production)
                    <option>{{ $production->制程 }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-auto">
                <label class="col col-auto form-label">{!! __('monthlyPRpageLang.isn') !!}</label>
                <input class="form-control form-control-lg " type="text" id="number" name="number" required
                    placeholder="{!! __('monthlyPRpageLang.enterisn') !!}"
                    oninput="if(value.length>12)value=value.slice(0,12)">
                <div id="numbererror" style="display:none; color:red;">{!!
                    __('monthlyPRpageLang.isnlength')!!}</div>
                <div id="numbererror1" style="display:none; color:red;">{!! __('monthlyPRpageLang.noisn') !!}
                </div>
            </div>
            <div class="col-auto">
                <label class="col col-auto form-label"></label>
                <input type="submit" id="add" name="add" class="form-control form-control-lg btn btn-lg btn-primary"
                    value="{!! __('monthlyPRpageLang.add') !!}">
            </div>

        </form>
        <div class="col-auto">
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <button class="btn btn-lg btn-primary" id ="loadconsume">{!! __('monthlyPRpageLang.loadconsume') !!}</button>
        </div>
    </div>
</div>
<div class="card w-100" id="consumebody">
    <div class="card-body">
        <form id="consumeadd" style="display: none">
            @csrf
            <div class="table-responsive">
                <table class="table" id="consumeaddtable">
                    <tbody id="consumeaddbody">
                        <tr>
                            <th>{!! __('monthlyPRpageLang.delete') !!}</th>
                            <th>{!! __('monthlyPRpageLang.isn') !!}</th>
                            <th>{!! __('monthlyPRpageLang.pName') !!}</th>
                            <th>{!! __('monthlyPRpageLang.format') !!}</th>
                            <th>{!! __('monthlyPRpageLang.unit') !!}</th>
                            <th>{!! __('monthlyPRpageLang.lt') !!}</th>
                            <th>{!! __('monthlyPRpageLang.consume') !!}</th>
                            {{-- <th>{!! __('monthlyPRpageLang.nowneed') !!}</th>
                            <th>{!! __('monthlyPRpageLang.nextneed') !!}</th>
                            <th>{!! __('monthlyPRpageLang.safe') !!}</th> --}}
                            <th>{!! __('monthlyPRpageLang.client') !!}</th>
                            <th>{!! __('monthlyPRpageLang.machine') !!}</th>
                            <th>{!! __('monthlyPRpageLang.process') !!}</th>
                            {{-- <th>{!! __('monthlyPRpageLang.nowmps') !!}</th>
                            <th>{!! __('monthlyPRpageLang.nowday') !!}</th>
                            <th>{!! __('monthlyPRpageLang.nextmps') !!}</th>
                            <th>{!! __('monthlyPRpageLang.nextday') !!}</th> --}}
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
            <input type="email" id="email" name="email" required pattern=".+@pegatroncorp\.com"
                placeholder="xxx@pegatroncorp.com">
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <div class="row w-100 justify-content-center">
                <div class="col col-auto">
                    <input type="submit" class="btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.submit') !!}"
                        style="width: 80px">
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row justify-content-center">
    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
    <div class="card w-75" id="consumeupload">
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
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</html>
@endsection
