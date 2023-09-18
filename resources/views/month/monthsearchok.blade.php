@extends('layouts.adminTemplate')
@section('css')
    <style>
        /* for single line table with over-flow , SAP style as asked */
        table {
            table-layout: fixed;
            /* width: 900px; */
        }

        .table-responsive {
            height: 600px;
            overflow: scroll;
        }

        thead tr:nth-child(1) th {
            background: white;
            position: sticky;
            top: 0;
            z-index: 10;
        }
    </style>
@endsection

@section('js')
    <!--for this page's sepcified js -->
    <script src="{{ asset('/js/month/monthsearch.js?v=') . env('APP_VERSION') }}"></script>
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.monthly') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                <vue-bread-crumb></vue-bread-crumb>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('monthlyPRpageLang.importMonthlyData') !!}</h3>
                <input class="form-control form-control-lg " type="text" id="numbersearch" name="numbersearch"
                    placeholder="{!! __('monthlyPRpageLang.enter90isn') !!}" oninput="if(value.length>12)value=value.slice(0,12)"
                    style="width: 200px">

            </div>
            <div class="card-body">
                <form id="monthsearch" method="POST">
                    @csrf
                    <input type="hidden" id="titlename" name="titlename" value="月請購資料">

                    <input type="submit" id="delete" name="delete" class="btn btn-lg btn-primary"
                        value="{!! __('monthlyPRpageLang.delete') !!}">
                    &nbsp;
                    {{-- <input type="submit" id="download" name="download" class="btn btn-lg btn-primary"
                        value="{!! __('monthlyPRpageLang.download') !!}"> --}}
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                    {{-- <input type="submit" id="return" name="return" class="btn btn-lg btn-primary"
                value="{!! __('monthlyPRpageLang.return') !!}"> --}}

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{!! __('monthlyPRpageLang.delete') !!}</th>
                                    <th><input type="hidden" id="title0" name="title0"
                                            value="90料號">{!! __('monthlyPRpageLang.90isn') !!}</th>
                                    <th><input type="hidden" id="title1" name="title1"
                                            value="料號">{!! __('monthlyPRpageLang.isn') !!}</th>
                                    <th><input type="hidden" id="title2" name="title2"
                                            value="當月MPS">{!! __('monthlyPRpageLang.nowmps') !!}</th>
                                    <th><input type="hidden" id="title3" name="title3"
                                            value="當月生產天數">{!! __('monthlyPRpageLang.nowday') !!}</th>
                                    <th><input type="hidden" id="title4" name="title4"
                                            value="下月MPS">{!! __('monthlyPRpageLang.nextmps') !!}</th>
                                    <th><input type="hidden" id="title5" name="title5"
                                            value="下月生產天數">{!! __('monthlyPRpageLang.nextday') !!}</th>
                                    <th><input type="hidden" id="title6" name="title6"
                                            value="填寫時間">{!! __('monthlyPRpageLang.writetime') !!}</th>
                                </tr>
                                <input type="hidden" id="titlecount" name="titlecount" value="7">

                            </thead>
                            <tbody>
                                @foreach ($data as $data)
                                    <tr class="isnRows" id="{{ $loop->index }}">
                                        <?php
                                        $data->本月MPS = round($data->本月MPS, 5);
                                        $data->本月生產天數 = round($data->本月生產天數, 5);
                                        $data->下月MPS = round($data->下月MPS, 5);
                                        $data->下月生產天數 = round($data->下月生產天數, 5);
                                        ?>
                                        <td><input class="innumber" type="checkbox" id="innumber" name="innumber"
                                                style="width:20px;height:20px;" value="{{ $loop->index }}"></td>
                                        <td><input type="hidden" id="90number{{ $loop->index }}"
                                                name="90number{{ $loop->index }}"
                                                value="{{ $data->料號90 }}">{{ $data->料號90 }}</td>
                                        <td><input type="hidden" id="number{{ $loop->index }}"
                                                name="number{{ $loop->index }}"
                                                value="{{ $data->料號 }}">{{ $data->料號 }}</td>
                                        <td><input type="hidden" id="nowmps{{ $loop->index }}"
                                                name="nowmps{{ $loop->index }}"
                                                value="{{ $data->本月MPS }}">{{ $data->本月MPS }}</td>
                                        <td><input type="hidden" id="nowday{{ $loop->index }}"
                                                name="nowday{{ $loop->index }}"
                                                value="{{ $data->本月生產天數 }}">{{ $data->本月生產天數 }}</td>
                                        <td><input type="hidden" id="nextmps{{ $loop->index }}"
                                                name="nextmps{{ $loop->index }}"
                                                value="{{ $data->下月MPS }}">{{ $data->下月MPS }}</td>
                                        <td><input type="hidden" id="nextday{{ $loop->index }}"
                                                name="nextday{{ $loop->index }}"
                                                value="{{ $data->下月生產天數 }}">{{ $data->下月生產天數 }}</td>
                                        <td><input type="hidden" id="writetime{{ $loop->index }}"
                                                name="writetime{{ $loop->index }}"
                                                value="{{ $data->填寫時間 }}">{{ $data->填寫時間 }}</td>
                                    </tr>
                                    <input type="hidden" id="count" name="count" value="{{ $loop->count }}">
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                </form>
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            </div>
        </div>
    </div>
@endsection
