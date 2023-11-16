@extends('layouts.adminTemplate')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/tooltip.css?v=') . env('APP_VERSION') }}">
@endsection

@section('js')
    <!--for this page's sepcified js -->
    <script src="{{ asset('js/inbound/addclient.js?v=') . env('APP_VERSION') }}"></script>
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">

            <h2 class="col-auto">{!! __('templateWords.inbound') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                {{-- <vue-bread-crumb></vue-bread-crumb> --}}
            </div>
        </div>
        <div class="card w-100">
            <div class="card-header">
                <h3>{!! __('inboundpageLang.addclient') !!}</h3>
            </div>
            <div class="card-body">
                <form id="add" class="row gx-6 gy-1 align-items-center">
                    @csrf
                    <div style="display: none" id="showname">{{ $clients = App\Models\客戶別::all() }}
                        {{ $showreason = App\Models\入庫原因::all() }}</div>
                    <div class="col-auto">
                        <label class="col col-lg-12 form-label">{!! __('inboundpageLang.client') !!}</label>

                        <select class="form-select form-select-lg" id="client" name="client">
                            <option style="display: none" disabled selected value="">{!! __('inboundpageLang.enterclient') !!}</option>
                            @foreach ($clients as $client)
                                <option>{{ $client->客戶 }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="clienterror" style="display:none; color:red;">
                            {!! __('inboundpageLang.enterclient') !!}</div>
                    </div>

                    <div class="col-auto">

                        <label class="col col-lg-12 form-label">{!! __('inboundpageLang.inreason') !!}</label>

                        <select class="form-select form-select-lg " id="inreason" name="inreason">
                            <option style="display: none" disabled selected value="">{!! __('inboundpageLang.enterinreason') !!}</option>
                            @foreach ($showreason as $showreason)
                                <option>{{ $showreason->入庫原因 }}</option>
                            @endforeach
                            <option>{!! __('inboundpageLang.other') !!}</option>
                        </select>
                        <label class="col col-lg-12 form-label" style="display:none;" id="reasonlabel"></label>

                        <input style="display:none;" class="form-control form-control-lg " type="text" id="reason"
                            name="reason" placeholder="{!! __('inboundpageLang.inputinreason') !!}">

                        <div class="invalid-feedback" id="inreasonerror" style="display:none; color:red;">
                            {!! __('inboundpageLang.enterinreason') !!}</div>
                    </div>

                    <div class="col-auto">
                        <label class="col col-lg-12 form-label">{!! __('inboundpageLang.isn') !!}</label>
                        <input class="form-control form-control-lg " type="text" id="number" name="number"
                            placeholder="{!! __('inboundpageLang.enterisn') !!}" oninput="if(value.length>12)value=value.slice(0,12)">

                        <div class="invalid-feedback" id="numbererror" style="display:none; color:red;">
                            {!! __('inboundpageLang.isnlength') !!}</div>
                        <div class="invalid-feedback" id="numbererror1" style="display:none; color:red;">
                            {!! __('inboundpageLang.noisn') !!}
                        </div>
                        <div class="invalid-feedback" id="notransit" style="display:none; color:red;">
                            {!! __('inboundpageLang.notransit') !!}
                        </div>

                    </div>

                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                    <div class="">
                        <label class="col col-auto form-label"></label>
                        <input type="submit" onclick="buttonIndex=0;" id="addto" name="addto"
                            class="btn btn-lg btn-primary" value="{!! __('inboundpageLang.add') !!}">
                    </div>


                </form>
            </div>
        </div>

        <div style="display: none" id="showposition">
            <select id="copyloc" name="copyloc">
                <option style="display: none" disabled selected value="">{!! __('inboundpageLang.enterloc') !!}</option>
                @foreach ($positions as $position)
                    <option>{{ $position->儲存位置 }}</option>
                @endforeach
            </select>
        </div>

        <div class="card w-100">
            <div class="card-body">
                <form id="inboundaddclient">
                    @csrf
                    <div class="table-responsive">
                        <table class="table" id="addclienttable">
                            <tbody id="addclientbody">
                                <tr>
                                    <th>{!! __('inboundpageLang.delete') !!}</th>
                                    <th>{!! __('inboundpageLang.client') !!}</th>
                                    <th>{!! __('inboundpageLang.isn') !!}</th>
                                    <th>{!! __('inboundpageLang.pName') !!}</th>
                                    <th>{!! __('inboundpageLang.format') !!}</th>
                                    <th>{!! __('inboundpageLang.unit') !!}</th>
                                    <th>{!! __('inboundpageLang.transit') !!}</th>
                                    <th>{!! __('inboundpageLang.nowstock') !!}</th>
                                    <th>{!! __('inboundpageLang.inboundnum') !!}</th>
                                    <th>{!! __('inboundpageLang.inreason') !!}</th>
                                    <th>{!! __('inboundpageLang.oldloc') !!}</th>
                                    <th>{!! __('inboundpageLang.newloc') !!}</th>
                                </tr>
                                <tr>
                                    @foreach ($data as $data)
                                        <?php
                                        $data->請購數量 = round($data->請購數量, 0);
                                        $stock = DB::table('inventory')
                                            ->where('料號', $data->料號)
                                            ->where('客戶別', $data->客戶)
                                            ->sum('現有庫存');
                                        $posit = DB::table('inventory')
                                            ->where('料號', $data->料號)
                                            ->where('客戶別', $data->客戶)
                                            ->where('現有庫存', '>', 0)
                                            ->pluck('儲位');
                                        $name = DB::table('consumptive_material')
                                            ->where('料號', $data->料號)
                                            ->value('品名');
                                        $format = DB::table('consumptive_material')
                                            ->where('料號', $data->料號)
                                            ->value('規格');
                                        $unit = DB::table('consumptive_material')
                                            ->where('料號', $data->料號)
                                            ->value('單位');
                                        $showstock = '';
                                        $nowstock = DB::table('inventory')
                                            ->where('料號', $data->料號)
                                            ->where('客戶別', $data->客戶)
                                            ->where('現有庫存', '>', 0)
                                            ->pluck('現有庫存')
                                            ->toArray();
                                        $nowloc = DB::table('inventory')
                                            ->where('料號', $data->料號)
                                            ->where('客戶別', $data->客戶)
                                            ->where('現有庫存', '>', 0)
                                            ->pluck('儲位')
                                            ->toArray();
                                        $test = array_combine($nowloc, $nowstock);
                                        foreach ($test as $k => $a) {
                                            $showstock = $showstock . __('outboundpageLang.loc') . ' : ' . $k . ' ' . __('outboundpageLang.nowstock') . ' : ' . $a . "\n";
                                        }
                                        
                                        ?>
                                        <td><a id="deleteBtn{{ $loop->index }}"
                                                href="javascript:deleteBtn({{ $loop->index }})"><svg width="30"
                                                    height="30" fill="#c94466" class="bi bi-x-circle-fill"
                                                    viewBox="0 0 20 20">
                                                    <path
                                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z">
                                                    </path>
                                                </svg></a></td>
                                        <td><span id="client{{ $loop->index }}">{{ $data->客戶 }}</span></td>
                                        <td><span id="number{{ $loop->index }}">{{ $data->料號 }}</span></td>
                                        <td><span id="name{{ $loop->index }}">{{ $name }}</span></td>
                                        <td><span id="format{{ $loop->index }}">{{ $format }}</td>
                                        <td><span id="unit{{ $loop->index }}">{{ $unit }}</td>
                                        <td><span id="transit{{ $loop->index }}">{{ $data->請購數量 }}</td>
                                        <td><span id="stock{{ $loop->index }}">{{ $stock }}</td>
                                        <td>

                                            <div class="tooltip1">
                                                <input class="form-control amount" style="width:100px" type="number"
                                                    id="amount{{ $loop->index }}" name="amount{{ $loop->index }}"
                                                    required value="1" min="1">

                                                <span class="tooltip1text tooltip1-top">{{ $showstock }}</span>
                                            </div>
                                        </td>


                                        <td><span id="inreason{{ $loop->index }}">{{ $inreason }}</td>
                                        <td>
                                            <input type="hidden" id="oldposition{{ $loop->index }}"
                                                name="oldposition{{ $loop->index }}" value="{{ $posit }}">
                                            @foreach ($posit as $oldloc)
                                                <span style="width: 100px; display: inline-block;">
                                                    {{ $oldloc }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <select class="form-select form-select-lg"
                                                id="newposition{{ $loop->index }}"
                                                name="newposition{{ $loop->index }}" style="width: 150px">
                                                <option style="display: none" disabled selected value="">
                                                    {!! __('inboundpageLang.enterloc') !!}</option>
                                                @foreach ($positions as $position)
                                                    <option>{{ $position->儲存位置 }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback" id="positionerror{{ $loop->index }}"
                                                style="display:none; color:red;">
                                                {!! __('inboundpageLang.enterloc') !!}
                                            </div>
                                        </td>
                                </tr>
                                <input type="hidden" id="count" name="count" value="{{ $loop->count }}">
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                    {{-- check people --}}
                    <div style="display: none" id="showname">
                        @foreach ($checks as $people)
                            <input type="hidden" id="checkpeople{{ $loop->index }}"
                                name="checkpeople{{ $loop->index }}" value="{{ $people->工號 }}">
                            <input type="hidden" id="checkcount" name="checkcount" value="{{ $loop->count }}">
                        @endforeach
                    </div>

                    <div class="mb-3 col-md-6">
                        <label class="form-label">{!! __('inboundpageLang.inpeople') !!}</label>
                        <input class="form-control form-control-lg" id="inpeople" name="inpeople" width="250"
                            style="width: 250px" placeholder="{!! __('inboundpageLang.enterinpeople') !!}">
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        <ul id="inboundmenu" style="display: none;" class="list-group">
                            @foreach ($peoples as $peopleinf)
                                <a class="inboundlist list-group-item list-group-item-action"
                                    href="#">{{ $peopleinf->工號 . ' : ' . $peopleinf->姓名 }}</a>
                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            @endforeach
                        </ul>
                    </div>

                    {{-- rfid --}}
                    {{-- <input class="form-control form-control-lg rfid" id="rfidinpeople" name="rfidinpeople" width="250"
                style="width: 250px" placeholder="{!! __('inboundpageLang.rfidinpeople') !!}" type="password"> --}}


                    <div class="row w-100 justify-content-center">
                        <div class="col col-auto">
                            <input type="submit" id="submit" name="submit" class="btn btn-lg btn-primary"
                                value="{!! __('inboundpageLang.submit') !!}">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
