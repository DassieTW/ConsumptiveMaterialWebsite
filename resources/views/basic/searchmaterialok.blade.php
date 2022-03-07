@extends('layouts.adminTemplate')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
    <style>
        /* for single line table with over-flow , SAP style as asked */
        table {
            table-layout: fixed;
            /* width: 900px; */
        }
    </style>
@endsection

@section('js')
    <script src="{{ asset('js/basic/material.js') }}"></script>
    <!--for this page's sepcified js -->
@endsection
@section('content')
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
    </head>
    <h2>{!! __('basicInfoLang.basicInfo') !!}</h2>
    <div class="card">
        <div class="card-header">
            <h3>{!! __('basicInfoLang.matssearch') !!}</h3>
            <input class="form-control form-control-lg " type="text" id="numbersearch" name="numbersearch"
                placeholder="{!! __('basicInfoLang.enterisn') !!}" oninput="if(value.length>12)value=value.slice(0,12)"
                style="width: 200px">
        </div>
        <div class="card-body">

            <form id="materialsearch" method="POST">
                @csrf
                <input type="submit" id="delete" name="delete" class="btn btn-lg btn-primary"
                    value="{!! __('basicInfoLang.delete') !!}">
                <input type="submit" id="change" name="change" class="btn btn-lg btn-primary"
                    value="{!! __('basicInfoLang.change') !!}">
                <input type="submit" id="download" name="download" class="btn btn-lg btn-primary"
                    value="{!! __('basicInfoLang.download') !!}">
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                <div class="tabvle table-responsive">
                    <table class="table table-bordered table-hover align-items-center text-nowrap align-middle text-center justify-content-center" id="test">
                        <tr>
                            <th class="p-0 m-0" style="width: 3ch;">#</th>
                            <th class="p-0 m-0" style="width: 13ch;"><input type="hidden" id="title0" name="title0"
                                    value="料號">{!! __('basicInfoLang.isn') !!}
                            </th>
                            <th class="p-0 m-0" style="width: 13ch;"><input type="hidden" id="title1" name="title1" value="品名">{!! __('basicInfoLang.pName') !!}
                            </th>
                            <th class="p-0 m-0" style="width: 13ch;"><input type="hidden" id="title2" name="title2"    value="規格">{!! __('basicInfoLang.format') !!}
                            </th>
                            <th class="p-0 m-0" style="width: 15ch;"><input type="hidden" id="title3" name="title3" value="A級資材">{!! __('basicInfoLang.gradea') !!}</th>
                            <th class="p-0 m-0" style="width: 13ch;"><input type="hidden" id="title4" name="title4" value="月請購">{!! __('basicInfoLang.month') !!}
                            </th>
                            <th class="p-0 m-0" style="width: 15ch;"><input type="hidden" id="title5" name="title5" value="發料部門">{!! __('basicInfoLang.senddep') !!}</th>
                            <th class="p-0 m-0" style="width: 13ch;"><input type="hidden" id="title6" name="title6" value="耗材歸屬">{!! __('basicInfoLang.belong') !!}</th>
                            <th class="p-0 m-0" style="width: 13ch;"><input type="hidden" id="title7" name="title7" value="單價">{!! __('basicInfoLang.price') !!}
                            </th>
                            <th class="p-0 m-0" style="width: 13ch;"><input type="hidden" id="title8" name="title8" value="幣別">{!! __('basicInfoLang.money') !!}
                            </th>
                            <th class="p-0 m-0" style="width: 8ch;"><input type="hidden" id="title9" name="title9" value="單位">{!! __('basicInfoLang.unit') !!}
                            </th>
                            <th class="p-0 m-0" style="width: 8ch;"><input type="hidden" id="title10" name="title10" value="MPQ">{!! __('basicInfoLang.mpq') !!}
                            </th>
                            <th class="p-0 m-0" style="width: 8ch;"><input type="hidden" id="title11" name="title11" value="MOQ">{!! __('basicInfoLang.moq') !!}
                            </th>
                            <th class="p-0 m-0" style="width: 8ch;"><input type="hidden" id="title12" name="title12" value="LT">{!! __('basicInfoLang.lt') !!}
                            </th>
                            <th class="p-0 m-0" style="width: 13ch;"><input type="hidden" id="title13" name="title13" value="安全庫存">{!! __('basicInfoLang.safe') !!}</th>
                            <input type="hidden" id="time" name="time" value="14">
                        </tr>
                        @foreach ($data as $data)
                            <?php
                            $data->單價 = round($data->單價, 3);
                            $data->LT = round($data->LT, 3);
                            $currency = array('0' => 'RMB', '1' => 'USD', '2' => 'JPY', '3' => 'TWD', '4' => 'VND', '5' => 'IDR');
                            ?>
                            <tr class="isnRows">
                                <td class="p-0 m-0"><div class="pb-2"><input class="innumber" type="checkbox" id="innumber" name="innumber" value="{{ $loop->index }}"></div></td>
                                <td class="p-0 m-0"><input type="hidden" id="number{{ $loop->index }}" name="number{{ $loop->index }}" value="{{ $data->料號 }}">
                                    <div class="text-nowrap pb-2" style="width: 100%;">{{ $data->料號 }}</div>
                                </td>
                                <td class="py-0 m-0"><input type="hidden" id="name{{ $loop->index }}" name="name{{ $loop->index }}" value="{{ $data->品名 }}">
                                    <div class="text-nowrap pb-2" style="overflow-x: auto; width: 100%;">{{ $data->品名 }}</div>
                                </td>
                                <td class="py-0 m-0"><input type="hidden" id="format{{ $loop->index }}" name="format{{ $loop->index }}" value="{{ $data->規格 }}">
                                    <div class="text-nowrap pb-2" style="overflow-x: auto; width: 100%;">{{ $data->規格 }}</div>
                                </td>
                                <td class="p-0 m-0" align="center">
                                    <select style="width: 6ch;" class="col col-auto form-select form-select-lg p-0 m-0"
                                        id="gradea{{ $loop->index }}" name="gradea{{ $loop->index }}">
                                        @if($data->A級資材 === "是")
                                        <option selected value="是">{!! __('basicInfoLang.yes') !!}</option>
                                        <option value="否">{!! __('basicInfoLang.no') !!}</option>
                                        @else
                                        <option value="是">{!! __('basicInfoLang.yes') !!}</option>
                                        <option selected value="否">{!! __('basicInfoLang.no') !!}</option>
                                        @endif
                                    </select>
                                </td>
                                <td class="p-0 m-0 month" align="center">
                                    <select style="width: 6ch;" class="form-select form-select-lg p-0 m-0"
                                        id="month{{ $loop->index }}" name="month{{ $loop->index }}">
                                        @if($data->月請購 === "是")
                                        <option selected value="是">{!! __('basicInfoLang.yes') !!}</option>
                                        <option value="否">{!! __('basicInfoLang.no') !!}</option>
                                        @else
                                        <option value="是">{!! __('basicInfoLang.yes') !!}</option>
                                        <option selected value="否">{!! __('basicInfoLang.no') !!}</option>
                                        @endif
                                    </select>
                                </td>
                                <td class="p-0 m-0" align="center">
                                    <select style="width: 13ch;" class="form-select form-select-lg p-0 m-0"
                                        id="send{{ $loop->index }}" name="send{{ $loop->index }}">
                                        {{-- <option>{{ $data->發料部門 }}</option> --}}
                                        @foreach ($sends as $send)
                                            @if ($data->發料部門 === $send->發料部門)
                                            <option selected>{{ $send->發料部門 }}</option>
                                            @else
                                            <option>{{ $send->發料部門 }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </td>
                                <td class="p-0 m-0" align="center">
                                    <select style="width: 10ch;" class="form-select form-select-lg p-0 m-0"
                                        id="belong{{ $loop->index }}" name="belong{{ $loop->index }}">
                                        @if($data->耗材歸屬 === "單耗")
                                        <option selected value="單耗">{!! __('basicInfoLang.consume') !!}</option>
                                        <option value="站位">{!! __('basicInfoLang.stand') !!}</option>
                                        @else
                                        <option value="單耗">{!! __('basicInfoLang.consume') !!}</option>
                                        <option selected value="站位">{!! __('basicInfoLang.stand') !!}</option>
                                        @endif
                                    </select>
                                </td>
                                <td class="p-0 m-0" align="center"><input style="width: 7ch;" type="number" id="price{{ $loop->index }}"
                                        class="form-control text-center p-0 m-0" name="price{{ $loop->index }}"
                                        value="{{ $data->單價 }}" step="0.00001" min="0"></td>
                                <td class="p-0 m-0" align="center">
                                    <select style="width: 8ch;" class="form-select form-select-lg p-0 m-0"
                                        id="money{{ $loop->index }}" name="money{{ $loop->index }}">
                                        @foreach ($currency as $currency)
                                            @if ($data->幣別 === $currency)
                                            <option selected>{{ $currency }}</option>
                                            @else
                                            <option>{{ $currency }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </td>
                                <td class="p-0 m-0" align="center"><input style="width:5ch;" type="text" id="unit{{ $loop->index }}"
                                        name="unit{{ $loop->index }}" value="{{ $data->單位 }}"
                                        class="form-control text-center p-0 m-0"></td>
                                <td class="p-0 m-0" align="center"><input style="width:8ch;" type="number" id="mpq{{ $loop->index }}"
                                        name="mpq{{ $loop->index }}" value="{{ $data->MPQ }}"
                                        class="form-control text-center p-0 m-0" min="0"></td>
                                <td class="p-0 m-0" align="center"><input style="width:8ch;" type="number" id="moq{{ $loop->index }}"
                                        name="moq{{ $loop->index }}" value="{{ $data->MOQ }}"
                                        class="form-control text-center p-0 m-0" min="0"></td>
                                <td class="p-0 m-0" align="center"><input style="width:8ch;" type="number" id="lt{{ $loop->index }}"
                                        name="lt{{ $loop->index }}" value="{{ $data->LT }}"
                                        class="form-control text-center p-0 m-0" min="0"></td>
                                <td class="p-0 m-0 month" align="center">
                                    @if ( $data->月請購 === "否" )
                                        <input class="form-control text-center p-0 m-0" style="width:8ch;" type="number"
                                        id="safe{{ $loop->index }}" name="safe{{ $loop->index }}"
                                        value="{{ $data->安全庫存 }}" min="0"></td>
                                    @else
                                        <input class="form-control text-center p-0 m-0" style="width:8ch;" type="number"
                                        id="safe{{ $loop->index }}" name="safe{{ $loop->index }}" value="" min="0" readonly></td>
                                    @endif

                                <input type="hidden" id="count" name="count" value="{{ $loop->count }}">
                            </tr>
                        @endforeach
                    </table>
                </div>

                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->


            </form>
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <button class="btn btn-lg btn-primary"
                onclick="location.href='{{ route('basic.material') }}'">{!! __('basicInfoLang.return') !!}</button>
        </div>
    </div>

    </html>
@endsection
