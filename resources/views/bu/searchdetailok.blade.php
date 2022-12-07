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
    <script src="{{ asset('js/bu/searchlist.js?v=') . env('APP_VERSION') }}"></script>
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('bupagelang.bu') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                <vue-bread-crumb></vue-bread-crumb>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3>{!! __('bupagelang.searchdetail') !!}</h3>
            <input class="form-control form-control-lg " type="text" id="numbersearch" name="numbersearch"
                placeholder="{!! __('basicInfoLang.enterisn') !!}" oninput="if(value.length>12)value=value.slice(0,12)"
                style="width: 200px">
        </div>
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th><input type="hidden" id="title0" name="title0" value="調撥單號">{!! __('bupagelang.dblist') !!}
                            </th>
                            <th><input type="hidden" id="title1" name="title1" value="撥出廠區">{!! __('bupagelang.outfactory') !!}
                            </th>
                            <th><input type="hidden" id="title2" name="title2" value="接收廠區">{!! __('bupagelang.receivefac') !!}
                            </th>
                            <th><input type="hidden" id="title3" name="title3" value="客戶別">{!! __('bupagelang.client') !!}
                            </th>
                            <th><input type="hidden" id="title4" name="title4" value="料號">{!! __('bupagelang.isn') !!}
                            </th>
                            <th><input type="hidden" id="title5" name="title5" value="品名">{!! __('bupagelang.pName') !!}
                            </th>
                            <th><input type="hidden" id="title6" name="title6" value="規格">{!! __('bupagelang.format') !!}
                            </th>
                            @if ($choose === 'out')
                                <th><input type="hidden" id="title7" name="title7"
                                        value="現有庫存">{!! __('bupagelang.nowstock') !!}
                                </th>
                            @else
                                <th><input type="hidden" id="title7" name="title7"
                                        value="實際接收數量">{!! __('bupagelang.realpickamount') !!}
                                </th>
                            @endif
                            <th><input type="hidden" id="title8" name="title8" value="實際撥出數量">{!! __('bupagelang.realamount') !!}
                            </th>
                            <th><input type="hidden" id="title9" name="title9" value="儲位">{!! __('bupagelang.loc') !!}
                            </th>
                            @if ($choose === 'out')
                                <th><input type="hidden" id="title10" name="title10"
                                        value="預計撥出數量">{!! __('bupagelang.preamount') !!}
                                </th>
                            @endif
                            @if ($choose === 'out')
                                <th><input type="hidden" id="title11" name="title11"
                                        value="調撥人">{!! __('bupagelang.transpeople') !!}
                                </th>
                                <th><input type="hidden" id="title12" name="title12"
                                        value="撥出時間">{!! __('bupagelang.outtime') !!}
                                </th>
                            @else
                                <th><input type="hidden" id="title11" name="title11"
                                        value="接收人">{!! __('bupagelang.receivepeople') !!}
                                </th>
                                <th><input type="hidden" id="title12" name="title12"
                                        value="接收時間">{!! __('bupagelang.receivetime') !!}
                                </th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $data)
                            <tr id="{{ $loop->index }}" class="isnRows">
                                <td><input type="hidden" id="dataa{{ $loop->index }}" name="dataa{{ $loop->index }}"
                                        value="{{ $data->調撥單號 }}">{{ $data->調撥單號 }}</td>
                                <td><input type="hidden" id="datab{{ $loop->index }}" name="datab{{ $loop->index }}"
                                        value="{{ $data->撥出廠區 }}">{{ $data->撥出廠區 }}</td>
                                <td><input type="hidden" id="datac{{ $loop->index }}" name="datac{{ $loop->index }}"
                                        value="{{ $data->接收廠區 }}">{{ $data->接收廠區 }}</td>
                                <td><input type="hidden" id="datad{{ $loop->index }}" name="datad{{ $loop->index }}"
                                        value="{{ $data->客戶別 }}">{{ $data->客戶別 }}</td>
                                <td><input type="hidden" id="datae{{ $loop->index }}" name="datae{{ $loop->index }}"
                                        value="{{ $data->料號 }}">{{ $data->料號 }}</td>
                                <td><input type="hidden" id="dataf{{ $loop->index }}" name="dataf{{ $loop->index }}"
                                        value="{{ $data->品名 }}">{{ $data->品名 }}</td>
                                <td><input type="hidden" id="datag{{ $loop->index }}" name="datag{{ $loop->index }}"
                                        value="{{ $data->規格 }}">{{ $data->規格 }}</td>
                                @if ($choose === 'out')
                                    <td><input type="hidden" id="datah{{ $loop->index }}"
                                            name="datah{{ $loop->index }}"
                                            value="{{ $data->現有庫存 }}">{{ $data->現有庫存 }}</td>
                                @else
                                    <td><input type="hidden" id="datah{{ $loop->index }}"
                                            name="datah{{ $loop->index }}"
                                            value="{{ $data->實際接收數量 }}">{{ $data->實際接收數量 }}</td>
                                @endif
                                <td><input type="hidden" id="datai{{ $loop->index }}" name="datai{{ $loop->index }}"
                                        value="{{ $data->實際撥出數量 }}">{{ $data->實際撥出數量 }}</td>
                                <td><input type="hidden" id="dataj{{ $loop->index }}" name="dataj{{ $loop->index }}"
                                        value="{{ $data->儲位 }}">{{ $data->儲位 }}</td>
                                @if ($choose === 'out')
                                    <td><input type="hidden" id="datak{{ $loop->index }}"
                                            name="datak{{ $loop->index }}"
                                            value="{{ $data->預計撥出數量 }}">{{ $data->預計撥出數量 }}</td>
                                    <td><input type="hidden" id="datal{{ $loop->index }}"
                                            name="datal{{ $loop->index }}"
                                            value="{{ $data->調撥人 }}">{{ $data->調撥人 }}</td>
                                    <td><input type="hidden" id="datam{{ $loop->index }}"
                                            name="datam{{ $loop->index }}"
                                            value="{{ $data->撥出時間 }}">{{ $data->撥出時間 }}</td>
                                @else
                                    <td><input type="hidden" id="datan{{ $loop->index }}"
                                            name="datan{{ $loop->index }}"
                                            value="{{ $data->接收人 }}">{{ $data->接收人 }}</td>
                                    <td><input type="hidden" id="datao{{ $loop->index }}"
                                            name="datao{{ $loop->index }}"
                                            value="{{ $data->接收時間 }}">{{ $data->接收時間 }}</td>
                                @endif

                            </tr>
                            <input type="hidden" id="count" name="count" value="{{ $loop->count }}">
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- <br>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('bu.searchdetail')}}'">{!! __('bupagelang.return') !!}</button> --}}
        </div>
    </div>
@endsection
