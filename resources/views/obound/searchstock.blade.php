@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
    <script src="{{ asset('js/obound/searchstock.js?v=') . env('APP_VERSION') }}"></script>
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.obound') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                {{-- <vue-bread-crumb></vue-bread-crumb> --}}
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="card w-75">
            <div class="card-header">
                <h3>{!! __('oboundpageLang.searchstock') !!}</h3>
            </div>
            <div class="row justify-content-center">
                <div class="card-body">
                    <div class="d-flex w-100">
                        <form id="form1" method="POST">
                            @csrf
                            <div class="row w-100 justify-content-center mb-3">
                                <label class="col col-auto form-label">{!! __('oboundpageLang.bound') !!}</label>
                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                <div class="col-lg-6  col-md-12 col-sm-12">
                                    <select class="form-select form-select-lg" id="bound" name="bound">
                                        <option style="display: none" disabled selected>{!! __('oboundpageLang.enterbound') !!}</option>
                                        @foreach ($bound as $bound)
                                            <option>{{ $bound->O庫 }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                                <label class="col col-auto form-label">{!! __('oboundpageLang.client') !!}</label>
                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                <div class="col-lg-6  col-md-12 col-sm-12">
                                    <select class="form-select form-select-lg" id="client" name="client">
                                        <option style="display: none" disabled selected>{!! __('oboundpageLang.enterclient') !!}</option>
                                        @foreach ($client as $client)
                                            <option>{{ $client->客戶 }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                                <label class="col col-auto form-label">{!! __('oboundpageLang.oisn') !!}</label>
                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                <div class="col-lg-6  col-md-12 col-sm-12">
                                    <input class="form-control form-control-lg @error('number') is-invalid @enderror"
                                        type="text" id="number" name="number" placeholder="{!! __('oboundpageLang.enterisn') !!}">
                                    @error('number')
                                        <span class="invalid-feedback d-block" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                                <div class="row w-100 justify-content-center">
                                    <div class="col col-auto">
                                        <input class="basic" type="checkbox" id="nogood" name="nogood"
                                            style="width:20px;height:20px;" value="2">
                                        <label class="form-label">{!! __('oboundpageLang.nogood') !!}</label>
                                    </div>
                                </div>
                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                <div class="row w-100 justify-content-center">
                                    <div class="col col-auto">
                                        <input type="submit" id="search" name="search" class="btn btn-lg btn-primary"
                                            value="{!! __('oboundpageLang.search') !!}">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
