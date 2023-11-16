@extends('layouts.adminTemplate')
@section('css')
    <style>
        /* for single line table with over-flow , SAP style as asked */
        table {
            /* table-layout: fixed; */
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
    <script src="{{ asset('js/month/srmsubmit.js?v=') . env('APP_VERSION') }}"></script>
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.monthly') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                {{-- <vue-bread-crumb></vue-bread-crumb> --}}
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('monthlyPRpageLang.SRM') !!}</h3>
            </div>
            <div class="card-body">

                <form id="srmsubmit" method="POST">
                    @csrf
                    <div class="table-responsive text-nowrap">
                        <thead>
                            <table class="table">
                                <tr>
                                    <th>{!! __('monthlyPRpageLang.check') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.sxb') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.srm') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.client') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.isn') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.pName') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.buyamount') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.sxbamount') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.nowneed') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.nextneed') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.price') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.money') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.rate') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.transit') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.nowstock') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.buytime') !!}</th>
                                </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $data)
                                <tr>
                                    <?php
                                    $data->單價 = floatval($data->單價);
                                    $data->安全庫存 = floatval($data->安全庫存);
                                    $data->請購金額 = floatval($data->請購金額);
                                    $data->現有庫存 = floatval($data->現有庫存);
                                    $data->本次請購數量 = round($data->本次請購數量, 0);
                                    ?>
                                    <td><input class="innumber" type="checkbox" id="innumber" name="innumber"
                                            style="width:20px;height:20px;" value="{{ $loop->index }}"></td>
                                    <td><input class="form-control form-control-lg" style="width:150px" type="text"
                                            id="sxbnumber{{ $loop->index }}" name="sxbnumber{{ $loop->index }}"
                                            placeholder="{!! __('monthlyPRpageLang.entersxb') !!}"></td>
                                    <td><input type="hidden" id="srmnumber{{ $loop->index }}"
                                            name="srmnumber{{ $loop->index }}"
                                            value="{{ $data->SRM單號 }}">{{ $data->SRM單號 }}</td>
                                    <td><input type="hidden" id="client{{ $loop->index }}"
                                            name="client{{ $loop->index }}"
                                            value="{{ $data->客戶 }}">{{ $data->客戶 }}</td>
                                    <td><input type="hidden" id="number{{ $loop->index }}"
                                            name="number{{ $loop->index }}"
                                            value="{{ $data->料號 }}">{{ $data->料號 }}</td>
                                    <td>{{ $data->品名 }}</td>
                                    <td><input type="hidden" id="buyamount{{ $loop->index }}"
                                            name="buyamount{{ $loop->index }}"
                                            value="{{ $data->本次請購數量 }}">{{ $data->本次請購數量 }}</td>
                                    <td><input class="form-control form-control-lg" style="width:120px" type="number"
                                            id="sxbamount{{ $loop->index }}" name="sxbamount{{ $loop->index }}" required
                                            value="{{ $data->本次請購數量 }}" min="0" max="{{ $data->本次請購數量 }}"></td>
                                    <td>{{ $data->當月需求 }}</td>
                                    <td>{{ $data->下月需求 }}</td>
                                    <td>{{ $data->單價 }}</td>
                                    <td>{{ $data->幣別 }}</td>
                                    <td>{{ $data->匯率 }}</td>
                                    <td>{{ $data->在途數量 }}</td>
                                    <td>{{ $data->現有庫存 }}</td>
                                    <td>{{ $data->請購時間 }}</td>
                                </tr>
                                <input type="hidden" id="count" name="count" value="{{ $loop->count }}">
                            @endforeach
                        </tbody>
                        </table>
                    </div>
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                    <input type="submit" id="submit" name="submit" class="btn btn-lg btn-primary"
                        value="{!! __('monthlyPRpageLang.submit') !!}">
                </form>
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                {{-- <button class="btn btn-lg btn-primary" onclick="location.href='{{route('month.srm')}}'">{!!
            __('monthlyPRpageLang.return') !!}</button> --}}
            </div>
        </div>

    </div>
@endsection
