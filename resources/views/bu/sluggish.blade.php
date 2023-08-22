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
    <script src="{{ asset('/js/bu/sluggish.js?v=') . env('APP_VERSION') }}"></script>
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
            <h3>{!! __('bupagelang.sluggish') !!}</h3>
            <input class="form-control form-control-lg " type="text" id="numbersearch" name="numbersearch"
                placeholder="{!! __('basicInfoLang.enterisn') !!}" oninput="if(value.length>12)value=value.slice(0,12)"
                style="width: 200px">
        </div>
        <div class="card-body">
            <form id="sluggish" method="POST">
                @csrf
                {{-- <input type="submit" id="submit" name="submit" class="btn btn-lg btn-primary"
                value="{!! __('bupagelang.submit') !!}"> --}}
                <input type="submit" id="download" name="download" class="btn btn-lg btn-primary"
                    value="{!! __('bupagelang.download') !!}">
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                <input type="hidden" id="title" name="title" value="廠區呆滯庫存">
                <div class="table-responsive">

                    <table class="table" id="test">
                        <thead>
                            <tr>
                                <th>{!! __('bupagelang.check') !!}</th>
                                <th><input type="hidden" id="title0" name="title0"
                                        value="廠區">{!! __('bupagelang.factory') !!}
                                </th>
                                <th><input type="hidden" id="title1" name="title1"
                                        value="料號">{!! __('bupagelang.isn') !!}
                                </th>
                                <th><input type="hidden" id="title2" name="title2"
                                        value="品名">{!! __('bupagelang.pName') !!}
                                </th>
                                <th><input type="hidden" id="title3" name="title3"
                                        value="規格">{!! __('bupagelang.format') !!}
                                </th>
                                <th><input type="hidden" id="title4" name="title4"
                                        value="單位">{!! __('bupagelang.unit') !!}
                                </th>
                                <th><input type="hidden" id="title5" name="title5"
                                        value="呆滯天數">{!! __('bupagelang.days') !!}
                                </th>
                                <th><input type="hidden" id="title6" name="title6"
                                        value="庫存">{!! __('bupagelang.stock') !!}
                                </th>
                                <th><input type="hidden" id="title7" name="title7"
                                        value="撥出數量">{!! __('bupagelang.transamount') !!}</th>
                                <th><input type="hidden" id="title8" name="title8"
                                        value="近期請購紀錄">{!! __('bupagelang.buyrecord') !!}</th>
                                <th><input type="hidden" id="title9" name="title9"
                                        value="接收廠區">{!! __('bupagelang.receivefac') !!}</th>
                            </tr>
                        </thead>
                        <tbody>

                            {{-- loop thru every database, except for the default Consumables management, so starting at 1 --}}
                            @for ($i = 1; $i < count(config('database_list.databases')); $i++)
                                @foreach ($test[$i] as $data)
                                    <?php
                                    $maxtime = date_create(date('Y-m-d', strtotime($data->inventory最後更新時間)));
                                    $nowtime = date_create(date('Y-m-d', strtotime(\Carbon\Carbon::now())));
                                    $interval = date_diff($maxtime, $nowtime);
                                    $interval = $interval->format('%R%a');
                                    $stayday = (int) $interval;
                                    $buytime = [];
                                    $buytime1 = [];
                                    $buytimeco = [];
                                    $buytimeco1 = [];
                                    $database = config('database_list.databases');
                                    foreach ($database as $key => $value) {
                                        if ($value !== $database[$i] && $database[$i] !== 'Consumables management') {
                                            \Config::set('database.connections.' . env('DB_CONNECTION') . '.database', $value);
                                            \DB::purge(env('DB_CONNECTION'));
                                            $buytime[$key][0] = $value;
                                            $buytime[$key][1] = DB::table('consumptive_material')
                                                ->where('料號', $data->料號)
                                                ->value('發料部門');
                                            $buytime1[$key][0] = $value;
                                            $buytime1[$key][1] = DB::table('consumptive_material')
                                                ->where('料號', $data->料號)
                                                ->value('發料部門');
                                            $buytime[$key][2] = DB::table('請購單')
                                                ->where('料號', $data->料號)
                                                ->max('請購時間');
                                            $buytime1[$key][2] = DB::table('非月請購')
                                                ->where('料號', $data->料號)
                                                ->max('上傳時間');
                                            $buytimeco[$key][0] = $value;
                                            $buytimeco[$key][1] = DB::table('consumptive_material')
                                                ->where('料號', $data->料號)
                                                ->value('發料部門');
                                            $buytimeco1[$key][0] = $value;
                                            $buytimeco1[$key][1] = DB::table('consumptive_material')
                                                ->where('料號', $data->料號)
                                                ->value('發料部門');
                                            $buytimeco[$key][2] = DB::table('請購單')
                                                ->where('料號', $data->料號)
                                                ->max('請購時間');
                                            $buytimeco1[$key][2] = DB::table('非月請購')
                                                ->where('料號', $data->料號)
                                                ->max('上傳時間');
                                        }
                                    }
                                    ?> <tr class="isnRows">
                                        <td>
                                            <button class="basic btn btn-info btn-lg m-0 p-0 rounded-circle"
                                                id="submit{{ $i }}{{ $loop->index }}"
                                                value="{{ $i }}{{ $loop->index }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23"
                                                    fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                                                    <path
                                                        d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z" />
                                                </svg></button>
                                        </td>
                                        <td><input type="hidden" id="dataa{{ $i }}{{ $loop->index }}"
                                                name="dataa{{ $i }}{{ $loop->index }}"
                                                value="{{ $database[$i] }}">{{ $database[$i] }}
                                        </td>
                                        <td><input type="hidden" id="datab{{ $i }}{{ $loop->index }}"
                                                name="datab{{ $i }}{{ $loop->index }}"
                                                value={{ $data->料號 }}>{{ $data->料號 }}</td>
                                        <td><input type="hidden" id="datac{{ $i }}{{ $loop->index }}"
                                                name="datac{{ $i }}{{ $loop->index }}"
                                                value={{ $data->品名 }}>{{ $data->品名 }}</td>
                                        <td><input type="hidden" id="datad{{ $i }}{{ $loop->index }}"
                                                name="datad{{ $i }}{{ $loop->index }}"
                                                value={{ $data->規格 }}>{{ $data->規格 }}</td>
                                        <td><input type="hidden" id="datae{{ $i }}{{ $loop->index }}"
                                                name="datae{{ $i }}{{ $loop->index }}"
                                                value={{ $data->單位 }}>{{ $data->單位 }}</td>
                                        <td><input type="hidden" id="dataf{{ $i }}{{ $loop->index }}"
                                                name="dataf{{ $i }}{{ $loop->index }}"
                                                value={{ $stayday }}>{{ $stayday }}</td>
                                        <td><input type="hidden" id="datag{{ $i }}{{ $loop->index }}"
                                                name="datag{{ $i }}{{ $loop->index }}"
                                                value={{ round($data->inventory現有庫存) }}>{{ round($data->inventory現有庫存) }}
                                        </td>
                                        <td><input type="number" id="datah{{ $i }}{{ $loop->index }}"
                                                name="datah{{ $i }}{{ $loop->index }}" value="1"
                                                min="1" class="form-control formcontrol-lg" style="width:90px;">
                                        </td>
                                        <td id="datai{{ $i }}{{ $loop->index }}"
                                            name="datai{{ $i }}{{ $loop->index }}">
                                            @foreach ($buytime as $buytime)
                                                @if ($buytime[2] !== null)
                                                    <span style="white-space: pre-line">{!! __('bupagelang.factory') !!} :
                                                        {{ explode('Consumables management', $buytime[0])[0] }}
                                                        {!! __('bupagelang.senddep') !!} : {{ $buytime[1] }}
                                                        {!! __('bupagelang.buytime') !!}
                                                        : {{ $buytime[2] }}</span><br>
                                                @endif
                                            @endforeach
                                            @foreach ($buytime1 as $buytime1)
                                                @if ($buytime1[2] !== null)
                                                    <span style="white-space: pre-line">{!! __('bupagelang.factory') !!} :
                                                        {{ explode('Consumables management', $buytime1[0])[0] }}
                                                        {!! __('bupagelang.senddep') !!} : {{ $buytime1[1] }}
                                                        {!! __('bupagelang.buytime') !!}
                                                        : {{ $buytime1[2] }}</span><br>
                                                @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            <select class="form-select form-select-lg"
                                                id="dataj{{ $i }}{{ $loop->index }}"
                                                name="dataj{{ $i }}{{ $loop->index }}" style="width: 90px">
                                                <option style="display: none" disabled selected>{!! __('bupagelang.enterfactory') !!}
                                                </option>
                                                @foreach ($buytimeco as $buytime)
                                                    @if ($buytime[2] !== null)
                                                        <option>
                                                            {{ str_replace(' Consumables management', ' ', $buytime[0]) }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                                @foreach ($buytimeco1 as $buytime1)
                                                    @if ($buytime1[2] !== null)
                                                        <option>
                                                            {{ str_replace(' Consumables management', ' ', $buytime1[0]) }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            @endfor
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
@endsection
