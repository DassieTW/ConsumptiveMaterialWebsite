@extends('layouts.adminTemplate')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
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
    <script src="{{ asset('js/month/standsearch.js') }}"></script>
@endsection
@section('content')
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
    </head>
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.monthly') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                <vue-bread-crumb></vue-bread-crumb>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('monthlyPRpageLang.standUpdate') !!}</h3>
                <input class="form-control form-control-lg " type="text" id="numbersearch" name="numbersearch"
                    placeholder="{!! __('basicInfoLang.enterisn') !!}" oninput="if(value.length>12)value=value.slice(0,12)"
                    style="width: 200px">
            </div>
            <div class="card-body" id="standbody">

                <form id="stand" method="POST">
                    @csrf
                    <input type="hidden" id="titlename" name="titlename" value="站位人力">

                    <input type="submit" id="delete" name="delete" class="btn btn-lg btn-primary"
                        value="{!! __('monthlyPRpageLang.delete') !!}">
                    &nbsp;
                    <input type="submit" id="change" name="change" class="btn btn-lg btn-primary"
                        value="{!! __('monthlyPRpageLang.change') !!}">
                    &nbsp;
                    <input type="submit" id="download" name="download" class="btn btn-lg btn-primary"
                        value="{!! __('monthlyPRpageLang.download') !!}">
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                    <label class="form-label">{!! __('monthlyPRpageLang.surepeopleemail') !!}:</label>
                    <div class="input-group" style="width: 410px;">
                        <input type="text" id="email" name="email" class="form-control form-control"
                            style="width: 160px;" placeholder="{!! __('loginPageLang.enter_email') !!}">
                        <select class="form-select form-select-lg" style="width: 250px;" id="emailTail">
                            <option selected>@pegatroncorp.com</option>
                            <option>@intra.pegatroncorp.com</option>
                        </select>
                    </div>

                    <div class="table-responsive text-nowrap">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{!! __('monthlyPRpageLang.check') !!}</th>
                                    <th><input type="hidden" id="title0" name="title0"
                                            value="料號">{!! __('monthlyPRpageLang.isn') !!}</th>
                                    <th><input type="hidden" id="title1" name="title1"
                                            value="品名">{!! __('monthlyPRpageLang.pName') !!}</th>
                                    <th><input type="hidden" id="title2" name="title2"
                                            value="單位">{!! __('monthlyPRpageLang.unit') !!}</th>
                                    <th><input type="hidden" id="title3" name="title3"
                                            value="MPQ">{!! __('monthlyPRpageLang.mpq') !!}</th>
                                    <th><input type="hidden" id="title4" name="title4"
                                            value="LT">{!! __('monthlyPRpageLang.lt') !!}
                                    </th>
                                    <th><input type="hidden" id="title5" name="title5"
                                            value="客戶別">{!! __('monthlyPRpageLang.client') !!}</th>
                                    <th><input type="hidden" id="title6" name="title6"
                                            value="機種">{!! __('monthlyPRpageLang.machine') !!}</th>
                                    <th><input type="hidden" id="title7" name="title7"
                                            value="製程">{!! __('monthlyPRpageLang.process') !!}</th>
                                    <th><input type="hidden" id="title8" name="title8"
                                            value="當月站位人數">{!! __('monthlyPRpageLang.nowpeople') !!}</th>
                                    <th><input type="hidden" id="title9" name="title9"
                                            value="當月開線數">{!! __('monthlyPRpageLang.nowline') !!}</th>
                                    <th><input type="hidden" id="title10" name="title10"
                                            value="當月開班數">{!! __('monthlyPRpageLang.nowclass') !!}</th>
                                    <th><input type="hidden" id="title11" name="title11"
                                            value="當月每人每日需求量">{!! __('monthlyPRpageLang.nowuse') !!}</th>
                                    <th><input type="hidden" id="title12" name="title12"
                                            value="當月每日更換頻率">{!! __('monthlyPRpageLang.nowchange') !!}</th>
                                    <th><input type="hidden" id="title13" name="title13"
                                            value="當月每日需求">{!! __('monthlyPRpageLang.nowdayneed') !!}</th>
                                    <th><input type="hidden" id="title14" name="title14"
                                            value="下月站位人數">{!! __('monthlyPRpageLang.nextpeople') !!}</th>
                                    <th><input type="hidden" id="title15" name="title15"
                                            value="下月開線數">{!! __('monthlyPRpageLang.nextline') !!}</th>
                                    <th><input type="hidden" id="title16" name="title16"
                                            value="下月開班數">{!! __('monthlyPRpageLang.nextclass') !!}</th>
                                    <th><input type="hidden" id="title17" name="title17"
                                            value="下月每人每日需求量">{!! __('monthlyPRpageLang.nextuse') !!}</th>
                                    <th><input type="hidden" id="title18" name="title18"
                                            value="下月每日更換頻率">{!! __('monthlyPRpageLang.nextchange') !!}</th>
                                    <th><input type="hidden" id="title19" name="title19"
                                            value="下月每日需求">{!! __('monthlyPRpageLang.nextdayneed') !!}</th>
                                    <th><input type="hidden" id="title20" name="title20"
                                            value="安全庫存">{!! __('monthlyPRpageLang.safe') !!}</th>
                                    <th><input type="hidden" id="title21" name="title21"
                                            value="畫押信箱">{!! __('monthlyPRpageLang.email') !!}</th>
                                    <th><input type="hidden" id="title22" name="title22"
                                            value="備註">{!! __('monthlyPRpageLang.remark') !!}</th>
                                    <input type="hidden" id="titlecount" name="titlecount" value="23">
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $data)
                                    <?php
                                    
                                    $data->當月站位人數 = round($data->當月站位人數, 7);
                                    $data->當月開線數 = round($data->當月開線數, 7);
                                    $data->當月開班數 = round($data->當月開班數, 7);
                                    $data->當月每人每日需求量 = round($data->當月每人每日需求量, 7);
                                    $data->當月每日更換頻率 = round($data->當月每日更換頻率, 7);
                                    $data->下月站位人數 = round($data->下月站位人數, 7);
                                    $data->下月開線數 = round($data->下月開線數, 7);
                                    $data->下月開班數 = round($data->下月開班數, 7);
                                    $data->下月每人每日需求量 = round($data->下月每人每日需求量, 7);
                                    $data->下月每日更換頻率 = round($data->下月每日更換頻率, 7);
                                    // $test = str_replace(";","<br>",$data->紀錄) ;
                                    ?>
                                    <tr @class([
                                        'isnRows',
                                        'table-success' => ($data->狀態 === '已完成'),
                                        'table-danger' => ($data->狀態 !== '已完成'),
                                    ])>
                                        <td><input class="innumber" type="checkbox" id="innumber" name="innumber"
                                                style="width:20px;height:20px;" value="{{ $loop->index }}"></td>
                                        <td><input type="hidden" id="dataa{{ $loop->index }}"
                                                name="dataa{{ $loop->index }}"
                                                value="{{ $data->料號 }}">{{ $data->料號 }}</td>
                                        <td><input type="hidden" id="datab{{ $loop->index }}"
                                                name="datab{{ $loop->index }}"
                                                value="{{ $data->品名 }}">{{ $data->品名 }}</td>
                                        <td><input type="hidden" id="datac{{ $loop->index }}"
                                                name="datac{{ $loop->index }}"
                                                value="{{ $data->單位 }}">{{ $data->單位 }}</td>
                                        <td><input type="hidden" id="datad{{ $loop->index }}"
                                                name="datad{{ $loop->index }}"
                                                value="{{ $data->MPQ }}">{{ $data->MPQ }}</td>
                                        <td><input type="hidden" id="datae{{ $loop->index }}"
                                                name="datae{{ $loop->index }}"
                                                value="{{ $data->LT }}">{{ $data->LT }}</td>
                                        <td><input type="hidden" id="dataf{{ $loop->index }}"
                                                name="dataf{{ $loop->index }}"
                                                value="{{ $data->客戶別 }}">{{ $data->客戶別 }}</td>
                                        <td><input type="hidden" id="datag{{ $loop->index }}"
                                                name="datag{{ $loop->index }}"
                                                value="{{ $data->機種 }}">{{ $data->機種 }}</td>
                                        <td><input type="hidden" id="datah{{ $loop->index }}"
                                                name="datah{{ $loop->index }}"
                                                value="{{ $data->製程 }}">{{ $data->製程 }}</td>
                                        <td><input style="width:100px" class="form-control form-control-lg"
                                                type="number" id="datai{{ $loop->index }}"
                                                name="datai{{ $loop->index }}" value="{{ $data->當月站位人數 }}"
                                                step="0.0000001" min="0"></td>
                                        <td><input style="width:100px" class="form-control form-control-lg"
                                                type="number" id="dataj{{ $loop->index }}"
                                                name="dataj{{ $loop->index }}" value="{{ $data->當月開線數 }}"
                                                step="0.0000001" min="0"></td>
                                        <td><input style="width:100px" class="form-control form-control-lg"
                                                type="number" id="datak{{ $loop->index }}"
                                                name="datak{{ $loop->index }}" value="{{ $data->當月開班數 }}"
                                                step="0.0000001" min="0"></td>
                                        <td><input style="width:100px" class="form-control form-control-lg"
                                                type="number" id="datal{{ $loop->index }}"
                                                name="datal{{ $loop->index }}" value="{{ $data->當月每人每日需求量 }}"
                                                step="0.0000001" min="0">
                                        </td>
                                        <td><input style="width:100px" class="form-control form-control-lg"
                                                type="number" id="datam{{ $loop->index }}"
                                                name="datam{{ $loop->index }}" value="{{ $data->當月每日更換頻率 }}"
                                                step="0.0000001" min="0">
                                        </td>
                                        <td><input style="width:120px" class="form-control form-control-lg"
                                                type="number" id="datan{{ $loop->index }}"
                                                name="datan{{ $loop->index }}" readonly></td>
                                        <td><input style="width:100px" class="form-control form-control-lg"
                                                type="number" id="datao{{ $loop->index }}"
                                                name="datao{{ $loop->index }}" value="{{ $data->下月站位人數 }}"
                                                step="0.0000001" min="0">
                                        </td>
                                        <td><input style="width:100px" class="form-control form-control-lg"
                                                type="number" id="datap{{ $loop->index }}"
                                                name="datap{{ $loop->index }}" value="{{ $data->下月開線數 }}"
                                                step="0.0000001" min="0"></td>
                                        <td><input style="width:100px" class="form-control form-control-lg"
                                                type="number" id="dataq{{ $loop->index }}"
                                                name="dataq{{ $loop->index }}" value="{{ $data->下月開班數 }}"
                                                step="0.0000001" min="0"></td>
                                        <td><input style="width:100px" class="form-control form-control-lg"
                                                type="number" id="datar{{ $loop->index }}"
                                                name="datar{{ $loop->index }}" value="{{ $data->下月每人每日需求量 }}"
                                                step="0.0000001" min="0">
                                        </td>
                                        <td><input style="width:100px" class="form-control form-control-lg"
                                                type="number" id="datas{{ $loop->index }}"
                                                name="datas{{ $loop->index }}" value="{{ $data->下月每日更換頻率 }}"
                                                step="0.0000001" min="0">
                                        </td>
                                        <td><input style="width:120px" class="form-control form-control-lg"
                                                type="number" id="datat{{ $loop->index }}"
                                                name="datat{{ $loop->index }}" readonly></td>
                                        <td><input style="width:120px" class="form-control form-control-lg"
                                                type="number" id="datau{{ $loop->index }}"
                                                name="datau{{ $loop->index }}" readonly></td>
                                        <td><input type="hidden" id="datav{{ $loop->index }}"
                                                name="datav{{ $loop->index }}"
                                                value="{{ $data->畫押信箱 }}">{{ $data->畫押信箱 }}</td>
                                        <td><input type="hidden" id="dataw{{ $loop->index }}"
                                                name="dataw{{ $loop->index }}"
                                                value="{{ $data->狀態 }}">{{ $data->狀態 }}</td>

                                    </tr>
                                    <input type="hidden" id="count" name="count" value="{{ $loop->count }}">
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>


    </html>
@endsection
