@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
    <!--for this page's sepcified js -->
    <script src="{{ asset('/js/month/buylist.js?v=') . env('APP_VERSION') }}"></script>
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.monthly') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                {{-- <vue-bread-crumb></vue-bread-crumb> --}}
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3>{!! __('monthlyPRpageLang.PR') !!}</h3>
        </div>
        <div class="card-body">
            <form method="POST" id="buylist">
                @csrf
                <input type="hidden" id="titlename" name="titlename" value="請購單">
                <div class="table-responsive text-nowrap">
                    <table class="table">
                        <tr>
                            <td><input type="hidden" id="title2" name="title2" value="料號">{!! __('monthlyPRpageLang.isn') !!}
                            </td>
                            <td><input type="hidden" id="title3" name="title3" value="品名">{!! __('monthlyPRpageLang.pName') !!}
                            </td>
                            <td><input type="hidden" id="title4" name="title4" value="規格">{!! __('monthlyPRpageLang.format') !!}
                            </td>
                            <td><input type="hidden" id="title5" name="title5" value="單價">{!! __('monthlyPRpageLang.price') !!}
                            </td>
                            <td><input type="hidden" id="title6" name="title6" value="幣別">{!! __('monthlyPRpageLang.money') !!}
                            </td>
                            <td><input type="hidden" id="title7" name="title7" value="當月需求">{!! __('monthlyPRpageLang.nowneed') !!}
                            </td>
                            <td><input type="hidden" id="title8" name="title8" value="下月需求">{!! __('monthlyPRpageLang.nextneed') !!}
                            </td>
                            <td><input type="hidden" id="title9" name="title9" value="現有庫存">{!! __('monthlyPRpageLang.nowstock') !!}
                            </td>
                            <td><input type="hidden" id="title10" name="title10" value="在途數量">{!! __('monthlyPRpageLang.transit') !!}
                            </td>
                            <td><input type="hidden" id="title11" name="title11" value="本次請購數量">{!! __('monthlyPRpageLang.buyamount') !!}
                            </td>
                            <td><input type="hidden" id="title12" name="title12" value="請購金額">{!! __('monthlyPRpageLang.buyprice') !!}
                            </td>
                            <td><input type="hidden" id="title13" name="title13" value="匯率">{!! __('monthlyPRpageLang.rate') !!}
                            </td>
                            <td><input type="hidden" id="title14" name="title14" value="MOQ">{!! __('monthlyPRpageLang.moq') !!}
                            </td>
                            <input type="hidden" id="titlecount" name="titlecount" value="14">
                        </tr>
                        @foreach ($data1 as $data)
                            <?php
                            $amounta = DB::table('在途量')
                                ->where('料號', $data->料號)
                                ->sum('請購數量');
                            $stocka = DB::table('inventory')
                                ->where('料號', $data->料號)
                                ->sum('現有庫存');
                            $amounta = round($amounta, 0);
                            $nextneeda = ($data->下月MPS * $data->單耗) / 1000;
                            $nowneeda = ($data->本月MPS * $data->單耗) / 1000;
                            $realneeda = $nextneeda + $nowneeda - $stocka - $amounta;
                            $realneeda = ceil($realneeda);
                            $real = $realneeda <= 0 ? 0 : $realneeda;
                            ?>
                            <tr>
                                <td><input type="hidden" id="numbera{{ $loop->index }}" name="numbera{{ $loop->index }}"
                                        value="{{ $data->料號 }}">{{ $data->料號 }}</td>
                                <td><input type="hidden" id="namea{{ $loop->index }}" name="namea{{ $loop->index }}"
                                        value="{{ $data->品名 }}">{{ $data->品名 }}</td>
                                <td><input type="hidden" id="formata{{ $loop->index }}" name="formata{{ $loop->index }}"
                                        value="{{ $data->規格 }}">{{ $data->規格 }}
                                </td>
                                <td><input type="hidden" id="pricea{{ $loop->index }}" name="pricea{{ $loop->index }}"
                                        value="{{ (float) $data->單價 }}">{{ (float) $data->單價 }}</td>
                                <td><input type="hidden" id="moneya{{ $loop->index }}" name="moneya{{ $loop->index }}"
                                        value="{{ $data->幣別 }}">{{ $data->幣別 }}</td>
                                <td><input type="hidden" id="nowneeda{{ $loop->index }}"
                                        name="nowneeda{{ $loop->index }}"
                                        value="{{ $nowneeda }}">{{ $nowneeda }}</td>
                                <td><input type="hidden" id="nextneeda{{ $loop->index }}"
                                        name="nextneeda{{ $loop->index }}"
                                        value="{{ $nextneeda }}">{{ $nextneeda }}</td>
                                <td><input type="hidden" id="stocka{{ $loop->index }}"
                                        name="stocka{{ $loop->index }}"
                                        value="{{ $stocka }}">{{ $stocka }}</td>
                                <td><input type="hidden" id="amounta{{ $loop->index }}"
                                        name="amounta{{ $loop->index }}"
                                        value="{{ $amounta }}">{{ $amounta }}</td>
                                <td><input class="form-control form-control-lg" type="number"
                                        id="buyamounta{{ $loop->index }}" name="buyamounta{{ $loop->index }}" required
                                        value="{{ $real }}" min="0" max="{{ $real }}"
                                        style="width:100px" disabled></td>
                                <td><input class="form-control form-control-lg" id="buymoneya{{ $loop->index }}"
                                        name="buymoneya{{ $loop->index }}" style="width:100px" readonly></td>
                                <td><input type="hidden" id="ratea{{ $loop->index }}" name="ratea{{ $loop->index }}"
                                        value="{{ $rate1[$loop->index] }}">{{ $rate1[$loop->index] }}</td>
                                <td><input type="hidden" id="moqa{{ $loop->index }}" name="moqa{{ $loop->index }}"
                                        value="{{ $data->MOQ }}">{{ $data->MOQ }}</td>
                            </tr>
                            <input type="hidden" id="counta" name="counta" value="{{ $loop->count }}">
                        @endforeach
                        @foreach ($data2 as $data)
                            <?php
                            $amountb = DB::table('在途量')
                                ->where('料號', $data->料號)
                                ->sum('請購數量');
                            $stockb = DB::table('inventory')
                                ->where('料號', $data->料號)
                                ->sum('現有庫存');
                            ?>
                            <tr>
                                <td><input type="hidden" id="numberb{{ $loop->index }}"
                                        name="numberb{{ $loop->index }}"
                                        value="{{ $data->料號 }}">{{ $data->料號 }}</td>
                                <td><input type="hidden" id="nameb{{ $loop->index }}" name="nameb{{ $loop->index }}"
                                        value="{{ $data->品名 }}">{{ $data->品名 }}</td>
                                <td><input type="hidden" id="formatb{{ $loop->index }}"
                                        name="formatb{{ $loop->index }}"
                                        value="{{ $data->規格 }}">{{ $data->規格 }}
                                </td>
                                <td><input type="hidden" id="priceb{{ $loop->index }}"
                                        name="priceb{{ $loop->index }}"
                                        value="{{ (float) $data->單價 }}">{{ (float) $data->單價 }}</td>
                                <td><input type="hidden" id="moneyb{{ $loop->index }}"
                                        name="moneyb{{ $loop->index }}"
                                        value="{{ $data->幣別 }}">{{ $data->幣別 }}</td>
                                <td><input type="hidden" id="nowneedb{{ $loop->index }}"
                                        name="nowneedb{{ $loop->index }}" value="/">/</td>
                                <td><input type="hidden" id="nextneedb{{ $loop->index }}"
                                        name="nextneedb{{ $loop->index }}" value="/">/</td>
                                <td><input type="hidden" id="stockb{{ $loop->index }}"
                                        name="stockb{{ $loop->index }}"
                                        value="{{ $stockb }}">{{ round($stockb, 0) }}</td>
                                <td><input type="hidden" id="amountb{{ $loop->index }}"
                                        name="amountb{{ $loop->index }}"
                                        value="{{ $amountb }}">{{ round($amountb, 0) }}</td>
                                <td><input class="form-control form-control-lg" type="number"
                                        id="buyamountb{{ $loop->index }}" name="buyamountb{{ $loop->index }}" required
                                        value="{{ $data->請購數量 }}" min="0" max="{{ $data->請購數量 }}"
                                        style="width:100px" disabled></td>
                                <td><input class="form-control form-control-lg" id="buymoneyb{{ $loop->index }}"
                                        name="buymoneyb{{ $loop->index }}" style="width:100px" readonly></td>
                                <td><input type="hidden" id="rateb{{ $loop->index }}" name="rateb{{ $loop->index }}"
                                        value="{{ $rate2[$loop->index] }}">{{ $rate2[$loop->index] }}</td>
                                <td><input type="hidden" id="moqb{{ $loop->index }}" name="moqb{{ $loop->index }}"
                                        value="{{ $data->MOQ }}">{{ $data->MOQ }}</td>
                            </tr>
                            <input type="hidden" id="countb" name="countb" value="{{ $loop->count }}">
                        @endforeach
                    </table>
                </div>

                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                <span style="color: red;">{!! __('monthlyPRpageLang.exportspan') !!}</span>

                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                <input type="submit" id="download" name="download" class="btn btn-lg btn-primary"
                    value="{!! __('monthlyPRpageLang.export') !!}">
                {{-- &emsp13; --}}
                {{-- <input type="submit" id="download1" name="download1" class="btn btn-lg btn-primary"
                    value="{!! __('monthlyPRpageLang.export1') !!}"> --}}
            </form>

        </div>
    </div>
@endsection
