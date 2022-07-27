@extends('layouts.adminTemplate')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
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
    <!-- <script src="{{ asset('/js/popupNotice.js') }}"></script> -->
    <script src="{{ asset('js/inbound/searchstock.js') }}"></script>
    <!--for notifications pop up -->
@endsection
@section('content')
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
    </head>
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">

            <h2 class="col-auto">{!! __('templateWords.inbound') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                <vue-bread-crumb></vue-bread-crumb>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('inboundpageLang.stockmonth') !!}{!! __('inboundpageLang.search') !!}</h3>
                <input class="form-control form-control-lg " type="text" id="numbersearch" name="numbersearch"
                    placeholder="{!! __('basicInfoLang.enterisn') !!}" oninput="if(value.length>12)value=value.slice(0,12)"
                    style="width: 200px">
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <form id="inboundsearch" method="POST">
                        @csrf
                        <input type="submit" id="download" name="download" class="btn btn-lg btn-primary"
                            value="{!! __('inboundpageLang.download') !!}">
                        {{-- <button class="btn btn-lg btn-primary" onclick="location.href='{{route('inbound.searchstock')}}'">{!! __('inboundpageLang.return') !!}</button> --}}
                        <input type="hidden" id="titlename" name="titlename" value="庫存使用月數">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><input type="hidden" id="title0" name="title0" value="客戶別">{!! __('inboundpageLang.client') !!}
                                    </th>
                                    <th><input type="hidden" id="title1" name="title1" value="料號">{!! __('inboundpageLang.isn') !!}
                                    </th>
                                    <th><input type="hidden" id="title2" name="title2" value="品名">{!! __('inboundpageLang.pName') !!}
                                    </th>
                                    <th><input type="hidden" id="title3" name="title3" value="規格">{!! __('inboundpageLang.format') !!}
                                    </th>
                                    <th><input type="hidden" id="title4" name="title4" value="單位">{!! __('inboundpageLang.unit') !!}
                                    </th>
                                    <th><input type="hidden" id="title5" name="title5" value="單價">{!! __('inboundpageLang.price') !!}
                                    </th>
                                    <th><input type="hidden" id="title6" name="title6" value="幣別">{!! __('inboundpageLang.money') !!}
                                    </th>
                                    <th><input type="hidden" id="title7" name="title7" value="A級資材">{!! __('inboundpageLang.gradea') !!}
                                    </th>
                                    <th><input type="hidden" id="title8" name="title8" value="月請購">{!! __('inboundpageLang.month') !!}
                                    </th>
                                    <th><input type="hidden" id="title9" name="title9" value="現有庫存">{!! __('inboundpageLang.nowstock') !!}
                                    </th>
                                    <th><input type="hidden" id="title10" name="title10"
                                            value="月使用量">{!! __('inboundpageLang.monthuse') !!}
                                    </th>
                                    <th><input type="hidden" id="title11" name="title11"
                                            value="庫存使用月數">{!! __('inboundpageLang.stockmonth') !!}
                                    </th>
                                    <input type="hidden" id="titlecount" name="titlecount" value="12">
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $data)
                                    <tr class="isnRows">
                                        <?php
                                        $name = $data->品名;
                                        $format = $data->規格;
                                        $unit = $data->單位;
                                        $price = $data->單價;
                                        $price = round($price, 2);
                                        $money = $data->幣別;
                                        $gradea = $data->A級資材;
                                        $month = $data->月請購;
                                        $belong = $data->耗材歸屬;
                                        $lt = $data->LT;
                                        $monthuse = 0;
                                        if ($belong === '單耗') {

                                            $machine = DB::table('MPS')
                                                ->where('客戶別', $data->客戶別)
                                                ->get('機種');
                                            $howmany = count($machine);

                                            $production = DB::table('MPS')
                                                ->where('客戶別', $data->客戶別)
                                                ->get('製程');


                                            for($i = 0 ; $i < $howmany ; $i++)
                                            {
                                                $nowmps = DB::table('MPS')
                                                    ->where('客戶別', $data->客戶別)
                                                    ->where('製程', $production[$i]->製程)
                                                    ->where('機種', $machine[$i]->機種)
                                                    ->value('本月MPS');

                                                $consume = DB::table('月請購_單耗')
                                                    ->where('料號', $data->料號)
                                                    ->where('客戶別', $data->客戶別)
                                                    ->where('製程', $production[$i]->製程)
                                                    ->where('機種', $machine[$i]->機種)
                                                    ->value('單耗');

                                                $monthuse = $monthuse + ($consume * $nowmps);
                                            }

                                            if ($monthuse != 0) {
                                                $stockmonth = $data->現有庫存 / $monthuse;
                                            } else {
                                                $stockmonth = 0;
                                            }


                                        } else {
                                            $machine = DB::table('MPS')
                                                ->where('客戶別', $data->客戶別)
                                                ->get('機種');
                                            $howmany = count($machine);

                                            $production = DB::table('MPS')
                                                ->where('客戶別', $data->客戶別)
                                                ->get('製程');
                                            $mpq = DB::table('consumptive_material')
                                                ->where('料號', $data->料號)
                                                ->value('MPQ');
                                            for($i = 0 ; $i < $howmany ; $i++)
                                            {

                                                $nowday = DB::table('MPS')
                                                    ->where('客戶別', $data->客戶別)
                                                    ->where('製程', $production[$i]->製程)
                                                    ->where('機種', $machine[$i]->機種)
                                                    ->value('本月生產天數');
                                                $nowstand = DB::table('月請購_站位')
                                                    ->where('料號', $data->料號)
                                                    ->where('客戶別', $data->客戶別)
                                                    ->where('製程', $production[$i]->製程)
                                                    ->where('機種', $machine[$i]->機種)
                                                    ->value('當月站位人數');
                                                $nowline = DB::table('月請購_站位')
                                                    ->where('料號', $data->料號)
                                                    ->where('客戶別', $data->客戶別)
                                                    ->where('製程', $production[$i]->製程)
                                                    ->where('機種', $machine[$i]->機種)
                                                    ->value('當月開線數');
                                                $nowclass = DB::table('月請購_站位')
                                                    ->where('料號', $data->料號)
                                                    ->where('客戶別', $data->客戶別)
                                                    ->where('製程', $production[$i]->製程)
                                                    ->where('機種', $machine[$i]->機種)
                                                    ->value('當月開班數');
                                                $nowneed = DB::table('月請購_站位')
                                                    ->where('料號', $data->料號)
                                                    ->where('客戶別', $data->客戶別)
                                                    ->where('製程', $production[$i]->製程)
                                                    ->where('機種', $machine[$i]->機種)
                                                    ->value('當月每人每日需求量');
                                                $nowchange = DB::table('月請購_站位')
                                                    ->where('料號', $data->料號)
                                                    ->where('客戶別', $data->客戶別)
                                                    ->where('製程', $production[$i]->製程)
                                                    ->where('機種', $machine[$i]->機種)
                                                    ->value('當月每日更換頻率');

                                                if ($mpq != 0 || $mpq != null) {
                                                    $monthuse = $monthuse + (($nowday * $nowstand * $nowline * $nowclass * $nowneed * $nowchange) / $mpq);
                                                } else {
                                                    $monthuse = $monthuse + 0;
                                                }
                                            }

                                            if ($monthuse != 0) {
                                                $stockmonth = $data->現有庫存 / $monthuse;
                                            } else {
                                                $stockmonth = 0;
                                            }
                                        }
                                        ?>
                                        <td><input type="hidden" id="dataa{{ $loop->index }}"
                                                name="data0{{ $loop->index }}"
                                                value="{{ $data->客戶別 }}">{{ $data->客戶別 }}</td>
                                        <td><input type="hidden" id="datab{{ $loop->index }}"
                                                name="data1{{ $loop->index }}"
                                                value="{{ $data->料號 }}">{{ $data->料號 }}</td>
                                        <td><input type="hidden" id="datac{{ $loop->index }}"
                                                name="data2{{ $loop->index }}"
                                                value="{{ $name }}">{{ $name }}</td>
                                        <td><input type="hidden" id="datad{{ $loop->index }}"
                                                name="data3{{ $loop->index }}"
                                                value="{{ $format }}">{{ $format }}</td>
                                        <td><input type="hidden" id="datae{{ $loop->index }}"
                                                name="data4{{ $loop->index }}"
                                                value="{{ $unit }}">{{ $unit }}</td>
                                        <td><input type="hidden" id="dataf{{ $loop->index }}"
                                                name="data5{{ $loop->index }}"
                                                value="{{ $price }}">{{ $price }}</td>
                                        <td><input type="hidden" id="datag{{ $loop->index }}"
                                                name="data6{{ $loop->index }}"
                                                value="{{ $money }}">{{ $money }}</td>
                                        <td><input type="hidden" id="datah{{ $loop->index }}"
                                                name="data7{{ $loop->index }}"
                                                value="{{ $gradea }}">{{ $gradea }}</td>
                                        <td><input type="hidden" id="datai{{ $loop->index }}"
                                                name="data8{{ $loop->index }}"
                                                value="{{ $month }}">{{ $month }}</td>
                                        <td><input type="hidden" id="dataj{{ $loop->index }}"
                                                name="data9{{ $loop->index }}"
                                                value="{{ round($data->現有庫存, 0) }}">{{ round($data->現有庫存, 0) }}</td>
                                        <td><input type="hidden" id="datak{{ $loop->index }}"
                                                name="data10{{ $loop->index }}"
                                                value="{{ $monthuse }}">{{ $monthuse }}</td>
                                        <td><input type="hidden" id="datal{{ $loop->index }}"
                                                name="data11{{ $loop->index }}"
                                                value="{{ round($stockmonth,5) }}">{{ round($stockmonth,5) }}</td>
                                    </tr>
                            </tbody>
                            <input type="hidden" id="count" name="count" value="{{ $loop->count }}">
                            @endforeach
                        </table>
                    </form>
                </div>
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            </div>
        </div>
    </div>

    </html>
@endsection
