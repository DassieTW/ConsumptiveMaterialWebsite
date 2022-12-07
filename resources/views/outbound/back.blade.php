@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
    <!--for this page's sepcified js -->
    <script src="{{ asset('js/outbound/back.js?v=') . env('APP_VERSION') }}"></script>
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.outbound') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                <vue-bread-crumb></vue-bread-crumb>
            </div>
        </div>
        <div class="card w-100">
            <div class="card-header">
                <h3>{!! __('outboundpageLang.back') !!}</h3>
            </div>
            <div class="card-body">
                <form id="back" class="row gx-6 gy-1 align-items-center">
                    @csrf
                    <div class="col-auto">
                        <label class="col col-lg-12 form-label">{!! __('outboundpageLang.client') !!}</label>
                        <select class="form-select form-select-lg" id="client" name="client">
                            <option style="display: none" disabled selected value="">{!! __('outboundpageLang.enterclient') !!}</option>
                            @foreach ($client as $client)
                                <option>{{ $client->客戶 }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <label class="col col-lg-12 form-label">{!! __('outboundpageLang.machine') !!}</label>
                        <select class="form-select form-select-lg" id="machine" name="machine">
                            <option style="display: none" disabled selected value="">{!! __('outboundpageLang.entermachine') !!}</option>
                            @foreach ($machine as $machine)
                                <option>{{ $machine->機種 }}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="col-auto">
                        <label class="col col-lg-12 form-label">{!! __('outboundpageLang.process') !!}</label>
                        <select class="form-select form-select-lg " id="production" name="production">
                            <option style="display: none" disabled selected value="">{!! __('outboundpageLang.enterprocess') !!}</option>
                            @foreach ($production as $production)
                                <option>{{ $production->制程 }}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="col-auto">
                        <label class="col col-auto form-label">{!! __('outboundpageLang.line') !!}</label>
                        <select class="form-select form-select-lg " id="line" name="line">
                            <option style="display: none" disabled selected value="">{!! __('outboundpageLang.enterline') !!}</option>
                            @foreach ($line as $line)
                                <option>{{ $line->線別 }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <label class="col col-auto form-label">{!! __('outboundpageLang.backreason') !!}</label>
                        <select class="form-select form-select-lg " id="backreason" name="backreason">
                            <option style="display: none" disabled selected value="">{!! __('outboundpageLang.enterbackreason') !!}</option>
                            @foreach ($backreason as $backreason)
                                <option>{{ $backreason->退回原因 }}</option>
                            @endforeach
                            <option>{!! __('outboundpageLang.other') !!}</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <label class="col col-auto form-label">{!! __('outboundpageLang.isn') !!}</label>
                        <input class="form-control form-control-lg " type="text" id="number" name="number"
                            placeholder="{!! __('outboundpageLang.enterisn') !!}" oninput="if(value.length>12)value=value.slice(0,12)">
                        <div id="numbererror" style="display:none; color:red;">{!! __('outboundpageLang.isnlength') !!}</div>
                        <div id="numbererror1" style="display:none; color:red;">{!! __('outboundpageLang.noisn') !!}
                        </div>
                        <div id="nostock" style="display:none; color:red;">{!! __('outboundpageLang.nostock') !!}
                        </div>
                    </div>
                    <div class="col-auto">
                        <label class="col col-auto form-label"></label>
                        <input type="submit" id="add" name="add"
                            class="form-control form-control-lg btn btn-lg btn-primary" value="{!! __('outboundpageLang.add') !!}">
                    </div>
                    <div class="col-auto">
                        <label class="col col-auto form-label"></label>
                        <input style="display:none;" class="form-control form-control-lg " type="text" id="reason"
                            name="reason" placeholder="{!! __('outboundpageLang.inputbackreason') !!}">
                    </div>
                </form>
            </div>
        </div>
        <div class="card w-100">
            <div class="card-body">
                <form id="backadd" style="display: none">
                    @csrf
                    <div class="table-responsive">
                        <table class="table" id="backaddtable">
                            <tbody id="backaddbody">
                                <tr>
                                    <th>{!! __('outboundpageLang.delete') !!}</th>
                                    <th>{!! __('outboundpageLang.isn') !!}</th>
                                    <th>{!! __('outboundpageLang.pName') !!}</th>
                                    <th>{!! __('outboundpageLang.format') !!}</th>
                                    <th>{!! __('outboundpageLang.unit') !!}</th>
                                    <th>{!! __('outboundpageLang.senddep') !!}</th>
                                    <th>{!! __('outboundpageLang.backamount') !!}</th>
                                    <th>{!! __('outboundpageLang.mark') !!}</th>
                                    <th>{!! __('outboundpageLang.client') !!}</th>
                                    <th>{!! __('outboundpageLang.machine') !!}</th>
                                    <th>{!! __('outboundpageLang.process') !!}</th>
                                    <th>{!! __('outboundpageLang.line') !!}</th>
                                    <th>{!! __('outboundpageLang.backreason') !!}</th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                    <div class="row w-100 justify-content-center">
                        <div class="col col-auto">
                            <input type="submit" class="btn btn-lg btn-primary" value="{!! __('outboundpageLang.submit') !!}"
                                style="width: 80px">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
