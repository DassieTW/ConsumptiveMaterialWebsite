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
    <script src="{{ asset('/js/call/safe.js?v=') . env('APP_VERSION') }}"></script>
    <script>
        $(document).ready(function() {
            $('body').loadingModal('hide');
            $('body').loadingModal('destroy');
        });
    </script>
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('callpageLang.callsys') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                <vue-bread-crumb></vue-bread-crumb>
            </div>
        </div>

        <div class="card">
            <form method="POST" id="safe">
                <div class="card-header">
                    <h3>{!! __('callpageLang.safealert') !!}</h3>
                    <input class="form-control form-control-lg " type="text" id="numbersearch" name="numbersearch"
                        placeholder="{!! __('basicInfoLang.enterisn') !!}" oninput="if(value.length>12)value=value.slice(0,12)"
                        style="width: 200px">
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                    <input type="submit" id="submit" name="submit" class="btn btn-lg btn-primary"
                        value="{!! __('callpageLang.saveremark') !!}">

                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="test">
                            <thead>
                                <tr>
                                    <th>{!! __('callpageLang.client') !!}</th>
                                    <th>{!! __('callpageLang.isn') !!}</th>
                                    <th>{!! __('callpageLang.pName') !!}</th>
                                    <th>{!! __('callpageLang.format') !!}</th>
                                    <th>{!! __('callpageLang.stock') !!}</th>
                                    <th>{!! __('callpageLang.safe') !!}</th>
                                    <th>{!! __('callpageLang.loc') !!}</th>
                                    <th>{!! __('callpageLang.mark') !!}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $data)
                                    <?php
                                    $astock = DB::table('inventory')
                                        ->where('料號', $data->料號)
                                        ->where('客戶別', $data->客戶別)
                                        ->pluck('現有庫存')
                                        ->toArray();
                                    $position = DB::table('inventory')
                                        ->where('料號', $data->料號)
                                        ->where('客戶別', $data->客戶別)
                                        ->pluck('儲位')
                                        ->toArray();
                                    $astock = array_map(function ($v) {
                                        return round($v, 0);
                                    }, $astock);
                                    $test = array_combine($position, $astock);
                                    if ($data->inventory現有庫存 === null) {
                                        $data->inventory現有庫存 = 0;
                                    }
                                    ?>

                                    <tr id="data{{ $loop->index }}" class="isnRows">

                                        <td>{{ $data->客戶別 }}</td>
                                        <td>{{ $data->料號 }}</td>
                                        <input type="hidden" id="client{{ $loop->index }}" value="{{ $data->客戶別 }}">
                                        <input type="hidden" id="number{{ $loop->index }}" value="{{ $data->料號 }}">
                                        <td>{{ $data->品名 }}</td>
                                        <td>{{ $data->規格 }}</td>
                                        <td id="stocka{{ $loop->index }}">{{ $data->inventory現有庫存 }}</td>
                                        <td id="safea{{ $loop->index }}">{{ $data->安全庫存 }}</td>
                                        @if ($data->inventory現有庫存 === 0)
                                            <td>{!! __('callpageLang.nostock') !!}</td>
                                        @else
                                            <td>
                                                @foreach ($test as $k => $a)
                                                    @if ($a > 0)
                                                        {!! __('callpageLang.loc') !!} : {{ $k }}
                                                        {!! __('callpageLang.nowstock') !!} :
                                                        {{ $a }}<br>
                                                    @else
                                                        {!! __('callpageLang.nostock') !!}
                                                    @break
                                                @endif
                                            @endforeach
                                        </td>
                                    @endif
                                    <td><input class="form-control form-control-lg" type="text" style="width:100px"
                                            id="remark{{ $loop->index }}" value="{{ $data->備註 }}"></td>
                                </tr>

                                <input type="hidden" id="count" name="count" value="{{ $loop->count }}">
                            @endforeach

                            @foreach ($data1 as $data)
                                <?php
                                $astock1 = DB::table('inventory')
                                    ->where('料號', $data->料號)
                                    ->where('客戶別', $data->客戶別)
                                    ->pluck('現有庫存')
                                    ->toArray();
                                $position1 = DB::table('inventory')
                                    ->where('料號', $data->料號)
                                    ->where('客戶別', $data->客戶別)
                                    ->pluck('儲位')
                                    ->toArray();
                                $astock1 = array_map(function ($v) {
                                    return round($v, 0);
                                }, $astock1);
                                $test1 = array_combine($position1, $astock1);
                                if ($data->inventory現有庫存 === null) {
                                    $data->inventory現有庫存 = 0;
                                }
                                ?>

                                <tr id="dataa{{ $loop->index }}" class="isnRows1">
                                    <td>{{ $data->客戶別 }}</td>
                                    <td>{{ $data->料號 }}</td>
                                    <input type="hidden" id="clienta{{ $loop->index }}" value="{{ $data->客戶別 }}">
                                    <input type="hidden" id="numbera{{ $loop->index }}" value="{{ $data->料號 }}">
                                    <td>{{ $data->品名 }}</td>
                                    <td>{{ $data->規格 }}</td>
                                    <td id="stockb{{ $loop->index }}">{{ $data->inventory現有庫存 }}</td>
                                    <td id="safeb{{ $loop->index }}">{{ $data->安全庫存 }}</td>
                                    @if ($data->inventory現有庫存 === 0)
                                        <td>{!! __('callpageLang.nostock') !!}</td>
                                    @else
                                        <td>
                                            @foreach ($test1 as $k => $a)
                                                @if ($a > 0)
                                                    {!! __('callpageLang.loc') !!} : {{ $k }}
                                                    {!! __('callpageLang.nowstock') !!} :
                                                    {{ $a }}<br>
                                                @else
                                                    {!! __('callpageLang.nostock') !!}
                                                @break
                                            @endif
                                        @endforeach
                                    </td>
                                @endif
                                <td><input class="form-control form-control-lg" type="text" style="width:100px"
                                        id="remarka{{ $loop->index }}" value="{{ $data->備註 }}"></td>
                            </tr>

                            <input type="hidden" id="count1" name="count1" value="{{ $loop->count }}">
                        @endforeach

                        @foreach ($data2 as $data)
                            <?php
                            $astock2 = DB::table('inventory')
                                ->select('現有庫存')
                                ->where('料號', $data->料號)
                                ->get()
                                ->toArray();
                            $position2 = DB::table('inventory')
                                ->select('儲位')
                                ->where('料號', $data->料號)
                                ->get()
                                ->toArray();
                            if ($data->inventory現有庫存 === null) {
                                $data->inventory現有庫存 = 0;
                            }
                            //dd($astock2, $position2);
                            ?>

                            <tr id="datab{{ $loop->index }}" class="isnRows2">
                                <td>{!! __('callpageLang.notmonth') !!}</td>
                                <td>{{ $data->料號 }}</td>
                                <input type="hidden" id="numberb{{ $loop->index }}" value="{{ $data->料號 }}">
                                <td>{{ $data->品名 }}</td>
                                <td>{{ $data->規格 }}</td>
                                <td id="stockc{{ $loop->index }}">{{ $data->inventory現有庫存 }}</td>
                                <td id="safec{{ $loop->index }}">{{ $data->安全庫存 }}</td>
                                @if ($data->inventory現有庫存 === 0)
                                    <td>{!! __('callpageLang.nostock') !!}</td>
                                @else
                                    <td>
                                        @foreach ($astock2 as $k => $a)
                                            @if ($a->現有庫存 > 0)
                                                {!! __('callpageLang.loc') !!} : {{ $position2[$k]->儲位 }}
                                                {!! __('callpageLang.nowstock') !!} : {{ round($a->現有庫存) }}<br>
                                            @else
                                                {!! __('callpageLang.nostock') !!}
                                            @break
                                        @endif
                                    @endforeach
                                </td>
                            @endif
                            <td><input class="form-control form-control-lg" type="text" style="width:100px"
                                    id="remarkb{{ $loop->index }}" value="{{ $data->備註 }}"></td>
                        </tr>

                        <input type="hidden" id="count2" name="count2" value="{{ $loop->count }}">
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
@endsection
