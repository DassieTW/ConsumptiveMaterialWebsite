@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
    <!--for this page's sepcified js -->
    <script src="{{ asset('js/bu/picklist.js?v=') . env('APP_VERSION') }}"></script>
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('bupagelang.bu') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                {{-- <vue-bread-crumb></vue-bread-crumb> --}}
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3>{!! __('bupagelang.picklist') !!}</h3>
        </div>
        <div class="card-body">
            <form id="picklist" methood="POST">
                @csrf
                <div class="table-responsive">

                    <table class="table">
                        <tr id="require">
                            <th>{!! __('bupagelang.dblist') !!}</th>
                            <th>{!! __('bupagelang.isn') !!}</th>
                            <th>{!! __('bupagelang.pName') !!}</th>
                            <th>{!! __('bupagelang.format') !!}</th>
                            <th>{!! __('bupagelang.realamount') !!}</th>
                            <th>{!! __('bupagelang.realpickamount') !!}</th>
                            <th>{!! __('bupagelang.outfactory') !!}</th>
                            <th>{!! __('bupagelang.receivefac') !!}</th>
                            <th>{!! __('bupagelang.remark') !!}</th>
                            <th>{!! __('bupagelang.loc') !!}</th>
                            <th>{!! __('bupagelang.client') !!}</th>
                        </tr>
                        @foreach ($data as $data)
                            <tr id="{{ $data->調撥單號 }}">
                                <?php
                                $database = $data->接收廠區;
                                \Config::set('database.connections.' . env('DB_CONNECTION') . '.database', $database);
                                \DB::purge(env('DB_CONNECTION'));
                                $sure = DB::table('consumptive_material')
                                    ->where('料號', $data->料號)
                                    ->value('品名');
                                
                                $client = DB::table('客戶別')
                                    ->pluck('客戶')
                                    ->toArray();
                                $nowstock = DB::table('inventory')
                                    ->where('料號', $data->料號)
                                    ->where('現有庫存', '>', 0)
                                    ->pluck('現有庫存')
                                    ->toArray();
                                $nowclient = DB::table('inventory')
                                    ->where('料號', $data->料號)
                                    ->where('現有庫存', '>', 0)
                                    ->pluck('客戶別')
                                    ->toArray();
                                $nowloc = DB::table('inventory')
                                    ->where('料號', $data->料號)
                                    ->where('現有庫存', '>', 0)
                                    ->pluck('儲位')
                                    ->toArray();
                                $position = DB::table('儲位')
                                    ->pluck('儲存位置')
                                    ->toArray();
                                
                                $count = count($nowstock);
                                
                                if ($sure !== null) {
                                    if ($nowstock !== []) {
                                        for ($x = 0; $x < $count; $x++) {
                                            $keys[] = 'u' . $x;
                                        }
                                
                                        $result = array_merge_recursive(array_combine($keys, $nowclient), array_combine($keys, $nowstock), array_combine($keys, $nowloc));
                                    } else {
                                        $keys[0] = 'u0';
                                    }
                                } else {
                                    $keys[0] = 'u0';
                                }
                                
                                $jobnumber = DB::table('人員信息')
                                    ->pluck('工號')
                                    ->toArray();
                                $name = DB::table('人員信息')
                                    ->pluck('姓名')
                                    ->toArray();
                                $test = array_combine($jobnumber, $name);
                                ?>

                                <td><input type="hidden" id="data0{{ $loop->index }}" name="data0{{ $loop->index }}"
                                        value="{{ $data->調撥單號 }}">{{ $data->調撥單號 }}</td>
                                <td><input type="hidden" id="data1{{ $loop->index }}" name="data1{{ $loop->index }}"
                                        value="{{ $data->料號 }}">{{ $data->料號 }}</td>
                                <td><input type="hidden" id="data2{{ $loop->index }}" name="data2{{ $loop->index }}"
                                        value="{{ $data->品名 }}">{{ $data->品名 }}</td>
                                <td><input type="hidden" id="data3{{ $loop->index }}" name="data3{{ $loop->index }}"
                                        value="{{ $data->規格 }}">{{ $data->規格 }}</td>
                                <td><input type="hidden" id="data4{{ $loop->index }}" name="data4{{ $loop->index }}"
                                        value="{{ $data->撥出數量 }}">{{ $data->撥出數量 }}</td>
                                <td><input type="number" class="form-control form-control-lg" style="width: 150px"
                                        id="data5{{ $loop->index }}" name="data5{{ $loop->index }}"
                                        value="{{ $data->撥出數量 }}" required min="0"></td>
                                <td><input type="hidden" id="data6{{ $loop->index }}" name="data6{{ $loop->index }}"
                                        value="{{ $data->撥出廠區 }}">{{ $data->撥出廠區 }}</td>
                                <td><input type="hidden" id="data7{{ $loop->index }}" name="data7{{ $loop->index }}"
                                        value="{{ $data->接收廠區 }}">{{ $data->接收廠區 }}</td>
                                <input type="hidden" id="sure" name="sure" value="{{ $sure }}">
                                <td>
                                    @if ($sure !== null)
                                        @if ($nowstock !== [])
                                            @foreach ($result as $k)
                                                {!! __('bupagelang.client') !!} : {{ $k[0] }} {!! __('bupagelang.loc') !!} :
                                                {{ $k[2] }} {!! __('bupagelang.nowstock') !!} : {{ $k[1] }} <div
                                                    class="w-100" style="height: 1ch;"></div>
                                                <!-- </div>breaks cols to a new line-->
                                            @endforeach
                                        @else
                                        @endif
                                    @endif
                                </td>
                                <td>
                                    <select class="form-select form-select-lg" id="position" name="position" required
                                        style="width: 150px">
                                        <option style="display: none" disabled selected value="">
                                            {!! __('bupagelang.enterloc') !!}</option>
                                        @foreach ($position as $x)
                                            <option>{{ $x }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <select class="form-select form-select-lg" id="client" name="client" required
                                        style="width: 150px">
                                        <option style="display: none" disabled selected value="">
                                            {!! __('bupagelang.enterclient') !!}</option>
                                        @foreach ($client as $x)
                                            <option>{{ $x }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        @endforeach

                    </table>
                </div>


                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                {{-- check people --}}
                @foreach ($jobnumber as $check)
                    <input type="hidden" id="checkpeople{{ $loop->index }}" name="checkpeople{{ $loop->index }}"
                        value="{{ $check }}">
                    <input type="hidden" id="checkcount" name="checkcount" value="{{ $loop->count }}">
                @endforeach

                <label class="form-label">{!! __('bupagelang.receivepeople') !!}</label>
                <input class="form-control form-control-lg" id="pickpeople" name="pickpeople" style="width: 250px"
                    placeholder="{!! __('bupagelang.enteroutpeople') !!}" required>
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                <ul id="pickmenu" style="display: none;" class="list-group">
                    @foreach ($test as $k => $a)
                        <a class="picklist list-group-item list-group-item-action" href="#">{{ $k . ' : ' . $a }}</a>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                    @endforeach
                </ul>


                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                <input type="submit" id="submit" name="submit" class="btn btn-lg btn-primary"
                    value="{!! __('bupagelang.submit') !!}">
            </form>
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            {{-- <button class="btn btn-lg btn-primary" onclick="location.href='{{route('bu.picklistpage')}}'">{!!
            __('bupagelang.return') !!}</button> --}}
        </div>
    </div>
@endsection
