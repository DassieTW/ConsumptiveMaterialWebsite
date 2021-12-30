@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->

@endsection
@section('content')
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>
<h2>{!! __('bupagelang.bu') !!}</h2>
<div class="row justify-content-center">
    <div class="card w-75">
        <div class="card-header">
            <h3>{!! __('bupagelang.searchlist') !!}</h3>
        </div>
        <div class="row justify-content-center">
            <div class="card-body">
                <form action="{{ route('bu.searchlistsub') }}" method="POST">
                    @csrf
                    <div class="row w-100 justify-content-center mb-3">
                        <label class="col col-auto form-label">{!! __('bupagelang.outfactory') !!}</label>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <div class="col-lg-6  col-md-12 col-sm-12">

                            <select class="form-select form-select-lg" id="outfactory" name="outfactory">
                                <option style="display: none" disabled selected value="">{!!
                                    __('bupagelang.enterfactory') !!}</option>
                                <option value="M2_TEST_1112">M2_TEST</option>
                                <option value="巴淡SMT1214">巴淡SMT_TEST</option>
                                <option value="BB1_1214 Consumables management">BB1_TEST</option>
                                <option value="巴淡-LOT11 Consumables management">巴淡-LOT11</option>
                                <option value="巴淡-LOT2 Consumables management">巴淡-LOT2</option>
                                <option value="巴淡-PTSN Consumables management">巴淡-PTSN</option>
                            </select>
                        </div>

                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        <label class="col col-auto form-label">{!! __('bupagelang.receivefac') !!}</label>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <div class="col-lg-6  col-md-12 col-sm-12">

                            <select class="form-select form-select-lg" id="infactory" name="infactory">
                                <option style="display: none" disabled selected value="">{!!
                                    __('bupagelang.enterfactory') !!}</option>
                                <option value="M2_TEST_1112">M2_TEST</option>
                                <option value="巴淡SMT1214">巴淡SMT_TEST</option>
                                <option value="BB1_1214 Consumables management">BB1_TEST</option>
                                <option value="巴淡-LOT11 Consumables management">巴淡-LOT11</option>
                                <option value="巴淡-LOT2 Consumables management">巴淡-LOT2</option>
                                <option value="巴淡-PTSN Consumables management">巴淡-PTSN</option>
                            </select>
                        </div>

                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        <label class="col col-auto form-label">{!! __('bupagelang.isn') !!}</label>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <div class="col-lg-6  col-md-12 col-sm-12">
                            <input class="form-control form-control-lg @error('number') is-invalid @enderror"
                                type="text" id="number" name="number" placeholder="{!! __('bupagelang.enterisn') !!}"
                                oninput="if(value.length>12)value=value.slice(0,12)">
                            @error('number')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>

                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        <div class="row w-100 justify-content-center">
                            <div class="col col-auto">
                                <input class="basic" type="checkbox" id="date" name="date"
                                    style="width:20px;height:20px;"> {!! __('bupagelang.timepart') !!}
                            </div>
                        </div>

                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        <div class="row w-100 justify-content-center">
                            <div class="col col-auto">
                                <label class="col col-auto form-label">{!! __('bupagelang.begindate') !!}</label>:
                                <input class = "form-control form-control-lg" type="date" id="begin" name="begin" value="<?php echo date('Y-m-d'); ?>" />
                            </div>
                        </div>

                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        <div class="row w-100 justify-content-center">
                            <div class="col col-auto">
                                <label class="col col-auto form-label">{!! __('bupagelang.enddate') !!}</label>:
                                <input  class = "form-control form-control-lg" type="date" id="end" name="end" value="<?php echo date('Y-m-d'); ?>" />
                            </div>
                        </div>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        <div class="row w-100 justify-content-center">
                            <div class="col col-auto">
                                <input type="submit" id="search" name="search" class="btn btn-lg btn-primary"
                                    value="{!! __('bupagelang.search') !!}">
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
