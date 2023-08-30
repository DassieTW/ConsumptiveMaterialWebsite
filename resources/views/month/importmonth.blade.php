@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
    <!--for this page's sepcified js -->
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.monthly') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                <vue-bread-crumb></vue-bread-crumb>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="card w-50">
            <div class="card-header">
                <h3>{!! __('monthlyPRpageLang.importMonthlyData') !!}</h3>
            </div>
            <div class="row justify-content-center">
                <div class="card-body">
                    <div class="d-flex w-100">
                        <form action="{{ route('month.monthsearchoradd') }}" method="POST">
                            @csrf
                            <div class="row w-100 justify-content-center mb-3">
                                <label class="col col-auto form-label">{!! __('monthlyPRpageLang.client') !!}</label>
                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                <div class="col-lg-6  col-md-12 col-sm-12">
                                    <select
                                        class="form-select form-select-lg col col-auto @error('client') is-invalid @enderror"
                                        id="client" name="client">
                                        <option style="display: none" disabled selected>{!! __('monthlyPRpageLang.enterclient') !!}</option>
                                        @foreach ($client as $client)
                                            <option>{{ $client->客戶 }}</option>
                                        @endforeach
                                    </select>
                                    @error('client')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{!! __('monthlyPRpageLang.enterclient') !!}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                                <label class="col col-auto form-label">{!! __('monthlyPRpageLang.machine') !!}</label>
                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                <div class="col-lg-6  col-md-12 col-sm-12">
                                    <select
                                        class="form-select form-select-lg col col-auto @error('machine') is-invalid @enderror"
                                        id="machine" name="machine">
                                        <option style="display: none" disabled selected>{!! __('monthlyPRpageLang.entermachine') !!}</option>
                                        @foreach ($machine as $machine)
                                            <option>{{ $machine->機種 }}</option>
                                        @endforeach
                                    </select>
                                    @error('machine')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{!! __('monthlyPRpageLang.entermachine') !!}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                                <label class="col col-auto form-label">{!! __('monthlyPRpageLang.90isn') !!}</label>
                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                <div class="col-lg-6  col-md-12 col-sm-12">
                                    <input
                                        class="form-control form-control-lg col col-auto @error('number90') is-invalid @enderror"
                                        type="text" id="number90" name="number90" placeholder="{!! __('monthlyPRpageLang.enter90isn') !!}"
                                        oninput="if(value.length>12)value=value.slice(0,12)">
                                    @error('number90')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{!! __('monthlyPRpageLang.enter90isn') !!}</strong>
                                        </span>
                                    @enderror
                                    @error('number90length')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{!! __('monthlyPRpageLang.90isnlength') !!}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                                <label class="col col-auto form-label">{!! __('monthlyPRpageLang.nowmps') !!}</label>
                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                <div class="col-lg-6  col-md-12 col-sm-12">
                                    <input class="form-control form-control-lg col col-auto" type="number" id="nowmps"
                                        name="nowmps" value="0" step="0.001" min="0">
                                </div>
                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                <label class="col col-auto form-label">{!! __('monthlyPRpageLang.nowday') !!}</label>
                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                <div class="col-lg-6  col-md-12 col-sm-12">
                                    <input class="form-control form-control-lg" type="number" id="nowday" name="nowday"
                                        value="0" step="0.001" min="0">
                                </div>
                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                <label class="col col-auto form-label">{!! __('monthlyPRpageLang.nextmps') !!}</label>
                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                <div class="col-lg-6  col-md-12 col-sm-12">
                                    <input class="form-control form-control-lg" type="number" id="nextmps" name="nextmps"
                                        value="0" step="0.001" min="0">
                                </div>
                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                <label class="col col-auto form-label">{!! __('monthlyPRpageLang.nextday') !!}</label>
                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                <div class="col-lg-6  col-md-12 col-sm-12">
                                    <input class="form-control form-control-lg" type="number" id="nextday" name="nextday"
                                        value="0" step="0.001" min="0">
                                </div>
                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            </div>
                            <div class="row w-100 justify-content-center">
                                <div class="col col-auto">
                                    <input type="submit" id="search" name="search" class="btn btn-lg btn-primary"
                                        value="{!! __('monthlyPRpageLang.search') !!}">
                                    &emsp;
                                    <input type="submit" id="add" name="add" class="btn btn-lg btn-primary"
                                        value="{!! __('monthlyPRpageLang.add') !!}">
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card w-25">
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <label class="col col-auto form-label">{!! __('monthlyPRpageLang.process') !!}</label>
            <table class="table table-borderless">
                @foreach ($production as $production)
                    <tr>
                        <td><input class="innumber" type="checkbox" id="innumber{{ $loop->index }}"
                                name="innumber{{ $loop->index }}" style="width:20px;height:20px;"></td>
                        <td>
                            <h4>{{ $production->制程 }}
                        </td>
                    </tr>
                    <input type="hidden" id="count" name="count" value="{{ $loop->count }}">
                @endforeach
                @error('production')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{!! __('monthlyPRpageLang.enterprocess') !!}</strong>
                    </span>
                @enderror
            </table>
        </div>
        </form>
        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
        <div class="card w-75">
            <div class="card-header">
                <h3>{!! __('monthlyPRpageLang.upload') !!}</h3>
            </div>
            <div class="row justify-content-center">
                <div class="card-body">
                    <div class=" w-100">
                        <form method="post" enctype="multipart/form-data" action="{{ route('month.uploadmonth') }}">
                            @csrf
                            <div class="row w-100 justify-content-center mb-3">
                                <div class="col col-auto ">
                                    <a href="{{ asset('download/ImportMonthExample.xlsx') }}"
                                        download>{!! __('monthlyPRpageLang.exampleExcel') !!}</a>
                                </div>

                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                                <label class="col col-auto form-label">{!! __('monthlyPRpageLang.plz_upload') !!}</label>
                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                <div class="col-lg-6  col-md-12 col-sm-12">
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
                                            value="{!! __('monthlyPRpageLang.upload') !!}">
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
