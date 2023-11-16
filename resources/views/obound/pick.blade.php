@extends('layouts.adminTemplate')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/tooltip.css?v=') . env('APP_VERSION') }}">
@endsection

@section('js')
    <script src="{{ asset('js/obound/pick.js?v=') . env('APP_VERSION') }}"></script>
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.obound') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                {{-- <vue-bread-crumb></vue-bread-crumb> --}}
            </div>
        </div>
    </div>
    <div class="card w-100">
        <div class="card-header">
            <h3>{!! __('oboundpageLang.pick') !!}</h3>
        </div>
        <div class="card-body">
            <form id="pick" class="row gx-6 gy-1 align-items-center">
                @csrf
                <div class="col-auto">
                    <label class="col col-lg-12 form-label">{!! __('oboundpageLang.client') !!}</label>
                    <select class="form-select form-select-lg" id="client" name="client">
                        <option style="display: none" disabled selected value="">{!! __('oboundpageLang.enterclient') !!}</option>
                        @foreach ($client as $client)
                            <option>{{ $client->客戶 }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback" id="clienterror" style="display:none; color:red;">
                        {!! __('oboundpageLang.enterclient') !!}</div>
                </div>
                <div class="col-auto">
                    <label class="col col-lg-12 form-label">{!! __('oboundpageLang.machine') !!}</label>
                    <select class="form-select form-select-lg" id="machine" name="machine">
                        <option style="display: none" disabled selected value="">{!! __('oboundpageLang.entermachine') !!}</option>
                        @foreach ($machine as $machine)
                            <option>{{ $machine->機種 }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback" id="machineerror" style="display:none; color:red;">
                        {!! __('oboundpageLang.entermachine') !!}</div>
                </div>
                <div class="col-auto">
                    <label class="col col-lg-12 form-label">{!! __('oboundpageLang.process') !!}</label>
                    <select class="form-select form-select-lg " id="production" name="production">
                        <option style="display: none" disabled selected value="">{!! __('oboundpageLang.enterprocess') !!}</option>
                        @foreach ($production as $production)
                            <option>{{ $production->制程 }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback" id="productionerror" style="display:none; color:red;">
                        {!! __('oboundpageLang.enterprocess') !!}</div>
                </div>
                <div class="col-auto">
                    <label class="col col-auto form-label">{!! __('oboundpageLang.line') !!}</label>
                    <select class="form-select form-select-lg " id="line" name="line">
                        <option style="display: none" disabled selected value="">{!! __('oboundpageLang.enterline') !!}</option>
                        @foreach ($line as $line)
                            <option>{{ $line->線別 }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback" id="lineerror" style="display:none; color:red;">
                        {!! __('oboundpageLang.enterline') !!}</div>
                </div>
                <div class="col-auto">
                    <label class="col col-auto form-label">{!! __('oboundpageLang.usereason') !!}</label>
                    <select class="form-select form-select-lg " id="usereason" name="usereason">
                        <option style="display: none" disabled selected value="">{!! __('oboundpageLang.enterusereason') !!}</option>
                        @foreach ($usereason as $usereason)
                            <option>{{ $usereason->領用原因 }}</option>
                        @endforeach
                        <option value="其他">{!! __('oboundpageLang.other') !!}</option>
                    </select>
                    <div class="invalid-feedback" id="usereasonerror" style="display:none; color:red;">
                        {!! __('oboundpageLang.enterusereason') !!}</div>
                    <div id="inputreason" style="display:none;">
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <input class="form-control form-control-lg " type="text" id="reason" name="reason"
                            placeholder="{!! __('oboundpageLang.inputusereason') !!}">
                    </div>
                    <div class="invalid-feedback" id="inputreasonerror" style="display:none; color:red;">
                        {!! __('oboundpageLang.inputusereason') !!}</div>
                </div>

                <div class="col-auto">
                    <label class="col col-auto form-label">{!! __('oboundpageLang.isn') !!}</label>
                    <input class="form-control form-control-lg " type="text" id="number" name="number"
                        placeholder="{!! __('oboundpageLang.enterisn') !!}">
                    <div class="invalid-feedback" id="numbererror" style="display:none; color:red;">
                        {!! __('oboundpageLang.enterisn') !!}</div>
                    <div class="invalid-feedback" id="numbererror1" style="display:none; color:red;">
                        {!! __('oboundpageLang.noisn') !!}
                    </div>
                    <div class="invalid-feedback" id="nostock" style="display:none; color:red;">
                        {!! __('oboundpageLang.nostock') !!}
                    </div>
                </div>
                <div class="col-auto">
                    <label class="col col-auto form-label"></label>
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                    <input type="submit" id="add" name="add"
                        class="form-control form-control-lg btn btn-lg btn-primary" value="{!! __('oboundpageLang.add') !!}">
                </div>
            </form>
        </div>
    </div>
    <div class="card w-100">

        <div class="card-body">
            <form id="pickadd" style="display: none">
                @csrf
                <div class="table-responsive">
                    <table class="table" id="pickaddtable">
                        <tbody id="pickaddbody">
                            <tr>
                                <th>{!! __('oboundpageLang.delete') !!}</th>
                                <th>{!! __('oboundpageLang.isn') !!}</th>
                                <th>{!! __('oboundpageLang.pName') !!}</th>
                                <th>{!! __('oboundpageLang.format') !!}</th>
                                <th>{!! __('oboundpageLang.pickamount') !!}</th>
                                <th>{!! __('oboundpageLang.mark') !!}</th>
                                <th>{!! __('oboundpageLang.client') !!}</th>
                                <th>{!! __('oboundpageLang.machine') !!}</th>
                                <th>{!! __('oboundpageLang.process') !!}</th>
                                <th>{!! __('oboundpageLang.line') !!}</th>
                                <th>{!! __('oboundpageLang.usereason') !!}</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                <div class="row w-100 justify-content-center">
                    <div class="col col-auto">
                        <input type="submit" class="btn btn-lg btn-primary" value="{!! __('oboundpageLang.submit') !!}"
                            style="width: 80px">
                    </div>
                </div>
            </form>
        </div>
    </div>
    </div>
@endsection
