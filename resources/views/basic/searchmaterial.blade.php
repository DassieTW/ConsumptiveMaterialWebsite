@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
    <script src="{{ asset('js/basic/search.js?v=') . env('APP_VERSION') }}"></script>
    <!--for this page's sepcified js -->
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('basicInfoLang.matsInfo') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                {{-- <vue-bread-crumb></vue-bread-crumb> --}}
            </div>
            <div class="row justify-content-center">
                <div class="card-body">
                    <div class=" w-100">
                        <form method="POST" id="form1">
                            @csrf
                            <div class="row w-100 justify-content-center mb-3">
                                <div class="w-100" style="height: 1ch;"></div>
                                <!-- </div>breaks cols to a new line-->

                                <label class="col col-auto form-label">{!! __('basicInfoLang.matssearch') !!}</label>
                                <div class="w-100" style="height: 1ch;"></div>
                                <!-- </div>breaks cols to a new line-->
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="numberradio" id="numberradio"
                                            checked value="1">
                                        <input class="form-control form-control-lg col col-auto" type="text"
                                            id="number" name="number" placeholder="{!! __('basicInfoLang.enterisn') !!}"
                                            >
                                        <span style="color: red;font-size:11px;">&nbsp;{!! __('basicInfoLang.searchisn') !!}</span>
                                    </div>
                                </div>

                                <div class="w-100" style="height: 1ch;"></div>
                                <!-- </div>breaks cols to a new line-->

                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="numberradio" id="numberradio1"
                                            value="2">
                                        <textarea class="form-control form-control-lg col col-auto" id="numberarea" name="numberarea" for="numberradio1"
                                            rows="10" placeholder="{!! __('basicInfoLang.enterisn') !!}"></textarea>
                                        <span style="color: red;font-size:11px;">&nbsp;{!! __('basicInfoLang.searchisn1') !!}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="col-12">
                                <div class="form-check">
                                    <div class="col-lg-6  col-md-12 col-sm-12">
                                        <select class="form-select form-select-lg" id="send" name="send">
                                            <option style="display: none" disabled selected>{!! __('inboundpageLang.entersenddep') !!}</option>
                                            @foreach ($data as $data)
                                                <option>{{ $data->發料部門 }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="w-100" style="height: 2ch;"></div><!-- </div>breaks cols to a new line-->
                            <div class="row w-100 justify-content-center">
                                <div class="col col-auto">
                                    <input type="submit" id="search" name="search" class="btn btn-lg btn-primary"
                                        value="{!! __('basicInfoLang.matssearch') !!}">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
