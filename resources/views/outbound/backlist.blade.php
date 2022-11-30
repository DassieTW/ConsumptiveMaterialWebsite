@extends('layouts.adminTemplate')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
    <link rel="stylesheet" type="text/css" href="../css/tooltip.css">
@endsection

@section('js')
    <!--for this page's sepcified js -->
    <script src="{{ asset('js/outbound/backlist.js') }}"></script>
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.outbound') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                <vue-bread-crumb></vue-bread-crumb>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('outboundpageLang.backlist') !!}</h3>
            </div>
            <div class="card-body">
                <form id="backlist">
                    @csrf
                    <div class="table-responsive">
                        <table class="table" id="backlisttable">
                            <tbody id="backlistbody">
                                <tr>
                                    <th>{!! __('outboundpageLang.delete') !!}</th>
                                    <th>{!! __('outboundpageLang.add') !!}</th>
                                    <th>{!! __('outboundpageLang.client') !!}</th>
                                    <th>{!! __('outboundpageLang.machine') !!}</th>
                                    <th>{!! __('outboundpageLang.process') !!}</th>
                                    <th>{!! __('outboundpageLang.backreason') !!}</th>
                                    <th>{!! __('outboundpageLang.line') !!}</th>
                                    <th>{!! __('outboundpageLang.isn') !!}</th>
                                    <th>{!! __('outboundpageLang.pName') !!}</th>
                                    <th>{!! __('outboundpageLang.format') !!}</th>
                                    <th>{!! __('outboundpageLang.unit') !!}</th>
                                    <th>{!! __('outboundpageLang.backamount') !!}</th>
                                    <th>{!! __('outboundpageLang.realbackamount') !!}</th>
                                    <th>{!! __('outboundpageLang.mark') !!}</th>
                                    <th>{!! __('outboundpageLang.backdiffreason') !!}</th>
                                    <th>{!! __('outboundpageLang.backlistnum') !!}</th>
                                    <th>{!! __('outboundpageLang.opentime') !!}</th>
                                    <th>{!! __('outboundpageLang.status') !!}</th>
                                    <th>{!! __('outboundpageLang.loc') !!}</th>
                                </tr>
                                @foreach ($data as $data)
                                    <tr>
                                        <?php
                                        $showstock = '';
                                        $nowstock = DB::table('inventory')
                                            ->where('料號', $data->料號)
                                            ->where('客戶別', $data->客戶別)
                                            ->where('現有庫存', '>', 0)
                                            ->pluck('現有庫存')
                                            ->toArray();
                                        $nowloc = DB::table('inventory')
                                            ->where('料號', $data->料號)
                                            ->where('客戶別', $data->客戶別)
                                            ->where('現有庫存', '>', 0)
                                            ->pluck('儲位')
                                            ->toArray();
                                        $test = array_combine($nowloc, $nowstock);
                                        foreach ($test as $k => $a) {
                                            $showstock = $showstock . __('outboundpageLang.loc') . ' : ' . $k . ' ' . __('outboundpageLang.nowstock') . ' : ' . $a . "\n";
                                        }
                                        ?>
                                        <td><a id="deleteBtn{{ $loop->index }}"
                                                href="javascript:deleteBtn({{ $loop->index }})"><svg width="16"
                                                    height="16" fill="#c94466" class="bi bi-x-circle-fill"
                                                    viewBox="0 0 16 16">
                                                    <path
                                                        d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z">
                                                    </path>
                                                </svg></a></td>
                                        <td><a id="addBtn{{ $loop->index }}"
                                                href="javascript:addBtn({{ $loop->index }})"><svg
                                                    xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                    fill="#467fd0" class="bi bi-plus-square" viewBox="0 0 16 16">
                                                    <path
                                                        d="M14 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
                                                    <path
                                                        d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                                                </svg></a></td>
                                        <td><span id="client{{ $loop->index }}">{{ $data->客戶別 }}</span></td>
                                        <td><span id="machine{{ $loop->index }}">{{ $data->機種 }}</span></td>
                                        <td><span id="production{{ $loop->index }}">{{ $data->製程 }}</span></td>
                                        <td><span id="backreason{{ $loop->index }}">{{ $data->退回原因 }}</span></td>
                                        <td><span id="line{{ $loop->index }}">{{ $data->線別 }}</span></td>
                                        <td><span id="number{{ $loop->index }}">{{ $data->料號 }}</span></td>
                                        <td><span id="name{{ $loop->index }}">{{ $data->品名 }}</span></td>
                                        <td><span id="format{{ $loop->index }}">{{ $data->規格 }}</span></td>
                                        <td><span id="unit{{ $loop->index }}">{{ $data->單位 }}</span></td>
                                        <td><span id="advance{{ $loop->index }}">{{ $data->預退數量 }}</span></td>
                                        <td>
                                            <div class="tooltip1">
                                                <input class="form-control amount" style="width:100px" type="number"
                                                    id="amount{{ $loop->index }}" name="amount{{ $loop->index }}"
                                                    required value="{{ $data->實際退回數量 }}" min="0">

                                                <span class="tooltip1text tooltip1-top">{{ $showstock }}</span>
                                            </div>
                                        </td>

                                        <input type="hidden" id="amounttitle{{ $loop->index }}"
                                            value="{{ $showstock }}">
                                        <td><span id="remark{{ $loop->index }}">{{ $data->備註 }}</span></td>
                                        <td><input class="form-control reason" style="width:100px" type="text"
                                                id="reason{{ $loop->index }}" name="reason{{ $loop->index }}"
                                                value="{{ $data->實退差異原因 }}">
                                        </td>
                                        <td><span id="list{{ $loop->index }}">{{ $data->退料單號 }}</span></td>
                                        <td><span id="opentime{{ $loop->index }}">{{ $data->開單時間 }}</span></td>
                                        <td>
                                            <select class="form-select form-select-lg" id="status{{ $loop->index }}"
                                                name="status{{ $loop->index }}" required width="250"
                                                style="width: 150px">
                                                <option style="display: none" disabled selected value="">
                                                    {!! __('outboundpageLang.enterstatus') !!}
                                                </option>
                                                <option>{!! __('outboundpageLang.good') !!}</option>
                                                <option>{!! __('outboundpageLang.nogood') !!}</option>
                                            </select>
                                        </td>
                                        <td>
                                            <select style="width: 150px" class="form-select form-select-lg"
                                                name="position{{ $loop->index }}" id="position{{ $loop->index }}"
                                                required>

                                                <option style="display: none" disabled selected value="">
                                                    {!! __('outboundpageLang.enterloc') !!}</option>
                                                @foreach ($locs as $loc)
                                                    <option>{{ $loc->儲存位置 }}</option>
                                                @endforeach

                                            </select>
                                        </td>
                                        <input type="hidden" id="count" name="count" value="{{ $loop->count }}">
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- show reason error --}}
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                    <div id="reasonerror" style="display:none;">
                        <h3 id="reasonerrrow" style="color: red"></h3>
                        <h3 style="color: red">{!! __('outboundpageLang.enterdiffreason') !!}</h3>
                    </div>

                    {{-- input pickpeople --}}
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                    <label class="form-label">{!! __('outboundpageLang.receivepeople') !!}</label>
                    <input class="form-control form-control-lg" id="pickpeople" name="pickpeople" width="250"
                        style="width: 250px" placeholder="{!! __('outboundpageLang.inputreceivepeople') !!}" {{-- oninput="if(value.length>9)value=value.slice(0,9)"> --}}>
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                    <ul id="receivemenu" style="display: none;" class="list-group">
                        @foreach ($people as $people)
                            <a class="receivelist list-group-item list-group-item-action"
                                href="#">{{ $people->工號 . ' ' . $people->姓名 }}</a>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        @endforeach
                    </ul>

                    {{-- rfid pickpeople
            <input class="form-control form-control-lg rfid" id="rfidpickpeople" name="rfidpickpeople" width="250"
                style="width: 250px" placeholder="{!! __('outboundpageLang.rfidinputreceivepeople') !!}"
                type="password"> --}}

                    {{-- input backpeople --}}
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                    <label class="form-label">{!! __('outboundpageLang.backpeople') !!}</label>
                    <input class="form-control form-control-lg" id="backpeople" name="backpeople" width="250"
                        style="width: 250px" placeholder="{!! __('outboundpageLang.inputbackpeople') !!}" {{-- oninput="if(value.length>9)value=value.slice(0,9)"> --}}>
                    <option style="display: none" disabled selected value="">{!! __('outboundpageLang.enterbackpeople') !!}</option>
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                    <ul id="backmenu" style="display: none;" class="list-group">
                        @foreach ($people1 as $people)
                            <a class="backlist list-group-item list-group-item-action"
                                href="#">{{ $people->工號 . ' ' . $people->姓名 }}</a>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        @endforeach
                    </ul>

                    {{-- rfid backpeople
            <input class="form-control form-control-lg rfid" id="rfidbackpeople" name="rfidbackpeople" width="250"
                style="width: 250px" placeholder="{!! __('outboundpageLang.rfidinputbackpeople') !!}" type="password"> --}}

                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                    {{-- check people --}}
                    @foreach ($check as $people)
                        <input type="hidden" id="checkpeople{{ $loop->index }}" name="checkpeople{{ $loop->index }}"
                            value="{{ $people->工號 }}">
                        <input type="hidden" id="checkcount" name="checkcount" value="{{ $loop->count }}">
                    @endforeach

                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                    <input type="submit" id="submit" name="submit" class="btn btn-lg btn-primary"
                        value="{!! __('outboundpageLang.submit') !!}">
                </form>
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                <button class="btn btn-lg btn-primary"
                    onclick="location.href='{{ route('outbound.backlistpage') }}'">{!! __('outboundpageLang.return') !!}</button>
            </div>
        </div>
    </div>
@endsection
