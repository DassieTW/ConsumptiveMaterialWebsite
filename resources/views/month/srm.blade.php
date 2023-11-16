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
                {{-- <vue-bread-crumb></vue-bread-crumb> --}}
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="card w-75">
                <div class="card-header">
                    <h3>{!! __('monthlyPRpageLang.SRM') !!}</h3>
                </div>
                <div class="row justify-content-center">
                    <div class="card-body">
                        <div class="w-100">
                            <form action="{{ route('month.srmsearch') }}" method="POST">
                                @csrf
                                <div class="row w-100 justify-content-center mb-3">

                                    <label class="col col-auto form-label">{!! __('monthlyPRpageLang.client') !!}</label>
                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                    <div class="col-lg-6  col-md-12 col-sm-12">
                                        <select class="form-select form-select-lg" id="client" name="client">
                                            <option style="display: none" disabled selected>{!! __('monthlyPRpageLang.enterclient') !!}
                                            </option>
                                            @foreach ($client as $client)
                                                <option>{{ $client->客戶 }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                                    <label class="col col-auto form-label">{!! __('monthlyPRpageLang.isn') !!}</label>
                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                    <div class="col-lg-6  col-md-12 col-sm-12">

                                        <input class="form-control form-control-lg" type="text" id="number"
                                            name="number" placeholder="{!! __('monthlyPRpageLang.enterisn') !!}"
                                            oninput="if(value.length>12)value=value.slice(0,12)">
                                    </div>

                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                    <label class="col col-auto form-label">{!! __('monthlyPRpageLang.srm') !!}</label>
                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                    <div class="col-lg-6  col-md-12 col-sm-12">
                                        <input class="form-control form-control-lg" type="text" id="srm"
                                            name="srm" placeholder="{!! __('monthlyPRpageLang.entersrm') !!}">
                                    </div>
                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                    <label class="col col-auto form-label">{!! __('monthlyPRpageLang.senddep') !!}</label>
                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                    <div class="col-lg-6  col-md-12 col-sm-12">
                                        <select class="form-select form-select-lg" id="send" name="send">
                                            <option style="display: none" disabled selected>{!! __('monthlyPRpageLang.entersenddep') !!}
                                            </option>
                                            @foreach ($send as $send)
                                                <option>{{ $send->發料部門 }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                </div>
                                <div class="row w-100 justify-content-center">
                                    <div class="col col-auto">
                                        <input type="submit" id="search" name="search" class="btn btn-lg btn-primary"
                                            value="{!! __('monthlyPRpageLang.search') !!}">
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
