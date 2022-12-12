@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
    <!--for this page's sepcified js -->
    <script src="{{ asset('/js/month/notmonth.js?v=') . env('APP_VERSION') }}"></script>
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.monthly') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                <vue-bread-crumb></vue-bread-crumb>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="card w-75">
                <div class="card-header">
                    <h3>{!! __('monthlyPRpageLang.importNonMonthlyData') !!}</h3>
                </div>
                <div class="row justify-content-center">
                    <div class="card-body">
                        <div class=" w-100">
                            <form id="notmonth" method="POST">
                                @csrf
                                <div class="row w-100 justify-content-center mb-3">
                                    <label class="col col-auto form-label">{!! __('monthlyPRpageLang.client') !!}</label>
                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                    <div class="col-lg-6  col-md-12 col-sm-12">
                                        <select class="form-select form-select-lg col col-auto" id="client"
                                            name="client" required>
                                            <option style="display: none" disabled selected>{!! __('monthlyPRpageLang.enterclient') !!}</option>
                                            @foreach ($client as $client)
                                                <option>{{ $client->客戶 }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback" id="clienterror" style="display:none; color:red;">
                                            {!! __('inboundpageLang.enterclient') !!}</div>
                                    </div>

                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                                    <label class="col col-auto form-label">{!! __('monthlyPRpageLang.isn') !!}</label>
                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                    <div class="col-lg-6  col-md-12 col-sm-12">
                                        <input class="form-control form-control-lg" type="text" id="number"
                                            name="number" placeholder="{!! __('monthlyPRpageLang.enterisn') !!}"
                                            oninput="if(value.length>12)value=value.slice(0,12)">
                                        <div class="invalid-feedback" id="numbererror" style="display:none; color:red;">
                                            {!! __('inboundpageLang.enterisn') !!}</div>
                                        <div class="invalid-feedback" id="numbererror1" style="display:none; color:red;">
                                            {!! __('inboundpageLang.isnlength') !!}
                                        </div>
                                        <div class="invalid-feedback" id="numbererror2" style="display:none; color:red;">
                                            {!! __('inboundpageLang.noisn') !!}
                                        </div>
                                    </div>

                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                </div>
                                <div class="row w-100 justify-content-center">
                                    <div class="col col-auto">
                                        <input type="submit" onclick="buttonIndex=0;" id="search" name="search"
                                            class="btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.search') !!}">
                                        &emsp;
                                        <input type="submit" onclick="buttonIndex=1;" id="add" name="add"
                                            class="btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.add') !!}">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>


            {{-- <form style="display: none" action="{{ route('month.notmonthsearch') }}" method="POST"
            id="notmonthsearchform">
            @csrf
            <input type="hidden" id="varr1" name="varr1" value="" />
            <input type="hidden" id="varr2" name="varr2" value="" />
        </form> --}}

            <form style="display: none" action="{{ route('month.notmonthadd') }}" method="POST" id="notmonthaddform">
                @csrf
                <input type="hidden" id="var1" name="var1" value="" />
                <input type="hidden" id="var2" name="var2" value="" />
                <input type="hidden" id="var3" name="var3" value="" />
                <input type="hidden" id="var4" name="var4" value="" />
                <input type="hidden" id="var5" name="var5" value="" />
            </form>

            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <div class="card w-75">
                <div class="card-header">
                    <h3>{!! __('monthlyPRpageLang.upload') !!}</h3>
                </div>
                <div class="row justify-content-center">
                    <div class="card-body">
                        <div class=" w-100">
                            <form method="post" enctype="multipart/form-data" action="{{ route('month.uploadnotmonth') }}">
                                @csrf
                                <div class="row w-100 justify-content-center mb-3">
                                    <div class="col col-auto ">
                                        <a href="{{ asset('download/ImportNotmonthExample.xlsx') }}"
                                            download>{!! __('monthlyPRpageLang.exampleExcel') !!}</a>
                                    </div>

                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                                    <label class="col col-auto form-label">{!! __('monthlyPRpageLang.plz_upload') !!}</label>
                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                    <div class="col-lg-6  col-md-12 col-sm-12">
                                        <input class="form-control @error('select_file') is-invalid @enderror"
                                            type="file" name="select_file" />
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
    </div>
@endsection
