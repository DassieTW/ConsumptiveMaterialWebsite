@extends('layouts.adminTemplate')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
    <!--for this page's sepcified js -->
    <script src="{{ asset('js/bu/outlist.js') }}"></script>
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
            <h3>{!! __('bupagelang.outlist') !!}</h3>
        </div>
        <div class="card-body">
            <form id="outlist" methood="POST">
                @csrf
                <div class="table-responsive">

                    <table class="table">
                        <tr>
                            <th>{!! __('bupagelang.dblist') !!}</th>
                            <th>{!! __('bupagelang.client') !!}</th>
                            <th>{!! __('bupagelang.isn') !!}</th>
                            <th>{!! __('bupagelang.pName') !!}</th>
                            <th>{!! __('bupagelang.format') !!}</th>
                            <th>{!! __('bupagelang.preamount') !!}</th>
                            <th>{!! __('bupagelang.nowstock') !!}</th>
                            <th>{!! __('bupagelang.realamount') !!}</th>
                            <th>{!! __('bupagelang.loc') !!}</th>
                            <th>{!! __('bupagelang.outfactory') !!}</th>
                            <th>{!! __('bupagelang.receivefac') !!}</th>
                        </tr>
                        @foreach ($data as $data)
                            <tr id="{{ $data->調撥單號 }}">
                                <?php
                                $database = $data->撥出廠區;
                                \Config::set('database.connections.' . env('DB_CONNECTION') . '.database', $database);
                                \DB::purge(env('DB_CONNECTION'));
                                $client = DB::table('inventory')
                                    ->where('料號', $data->料號)
                                    ->where('現有庫存', '>', 0)
                                    ->pluck('客戶別')
                                    ->toArray();
                                $nowstock = DB::table('inventory')
                                    ->where('料號', $data->料號)
                                    ->where('現有庫存', '>', 0)
                                    ->pluck('現有庫存')
                                    ->toArray();
                                $position = DB::table('inventory')
                                    ->where('料號', $data->料號)
                                    ->where('現有庫存', '>', 0)
                                    ->pluck('儲位')
                                    ->toArray();

                                $count = count($client);
                                for ($x = 0; $x < $count; $x++) {
                                    $keys[] = 'u' . $x;
                                }
                                $jobnumber = DB::table('人員信息')
                                    ->pluck('工號')
                                    ->toArray();
                                $name = DB::table('人員信息')
                                    ->pluck('姓名')
                                    ->toArray();
                                $test = array_combine($jobnumber, $name);
                                $result = array_merge_recursive(array_combine($keys, $client), array_combine($keys, $nowstock), array_combine($keys, $position));
                                ?>
                                @foreach ($result as $k)
                                    <td><input type="hidden" id="dataa{{ $loop->index }}" name="dataa{{ $loop->index }}"
                                            value="{{ $data->調撥單號 }}">{{ $data->調撥單號 }}</td>
                                    <td><input type="hidden" id="datab{{ $loop->index }}" name="datab{{ $loop->index }}"
                                            value="{{ $k[0] }}">{{ $k[0] }}</td>
                                    <td><input type="hidden" id="datac{{ $loop->index }}"
                                            name="datac{{ $loop->index }}"
                                            value="{{ $data->料號 }}">{{ $data->料號 }}</td>
                                    <td><input type="hidden" id="datad{{ $loop->index }}"
                                            name="datad{{ $loop->index }}"
                                            value="{{ $data->品名 }}">{{ $data->品名 }}</td>
                                    <td><input type="hidden" id="datae{{ $loop->index }}"
                                            name="datae{{ $loop->index }}"
                                            value="{{ $data->規格 }}">{{ $data->規格 }}</td>
                                    <td><input type="hidden" id="dataf{{ $loop->index }}"
                                            name="dataf{{ $loop->index }}"
                                            value="{{ $data->調撥數量 }}">{{ $data->調撥數量 }}</td>
                                    <td><input type="hidden" id="datag{{ $loop->index }}"
                                            name="datag{{ $loop->index }}"
                                            value="{{ $k[1] }}">{{ $k[1] }}</td>
                                    <td><input type="number" class="form-control form-control-lg" style="width: 150px"
                                            id="datah{{ $loop->index }}" name="datah{{ $loop->index }}" min="0"
                                            value="{{ $k[1] }}" required></td>
                                    <td><input type="hidden" id="datai{{ $loop->index }}"
                                            name="datai{{ $loop->index }}"
                                            value="{{ $k[2] }}">{{ $k[2] }}</td>
                                    <td><input type="hidden" id="dataj{{ $loop->index }}"
                                            name="dataj{{ $loop->index }}"
                                            value="{{ $data->撥出廠區 }}">{{ $data->撥出廠區 }}</td>
                                    <td><input type="hidden" id="datak{{ $loop->index }}"
                                            name="datak{{ $loop->index }}"
                                            value="{{ $data->接收廠區 }}">{{ $data->接收廠區 }}</td>
                            </tr>
                            <input type="hidden" id="count" name="count" value="{{ $loop->count }}">
                        @endforeach
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

                <label class="form-label">{!! __('bupagelang.outpeople') !!}</label>
                <input class="form-control form-control-lg" id="outpeople" name="outpeople" width="250"
                    style="width: 250px" placeholder="{!! __('bupagelang.enteroutpeople') !!}" required>
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                <ul id="outmenu" style="display: none;" class="list-group">
                    @foreach ($test as $k => $a)
                        <a class="outlist list-group-item list-group-item-action" href="#">{{ $k . ' : ' . $a }}</a>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                    @endforeach
                </ul>



                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                <input type="submit" id="submit" name="submit" class="btn btn-lg btn-primary"
                    value="{!! __('bupagelang.submit') !!}">
            </form>
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <button class="btn btn-lg btn-primary"
                onclick="location.href='{{ route('bu.outlistpage') }}'">{!! __('bupagelang.return') !!}</button>
        </div>
    </div>
@endsection
