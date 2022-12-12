@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
    <!--for this page's sepcified js -->
    <script src="{{ asset('/js/month/notmonthadd.js?v=') . env('APP_VERSION') }}"></script>
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.monthly') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                <vue-bread-crumb></vue-bread-crumb>
            </div>
        </div>
    </div>
    <div class="card w-100">
        <div class="card-header">
            <h3>{!! __('monthlyPRpageLang.importNonMonthlyData') !!}</h3>
        </div>
        <div class="card-body">
            <form id="notmonth" class="row gx-6 gy-1 align-items-center">
                @csrf
                <div class="col-auto">
                    <label class="col col-lg-12 form-label">{!! __('outboundpageLang.client') !!}</label>
                    <select class="form-select form-select-lg" id="client" name="client">
                        <option style="display: none" disabled selected value="">{!! __('outboundpageLang.enterclient') !!}</option>
                        @foreach ($showclient as $cli)
                            <option>{{ $cli->客戶 }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback" id="clienterror" style="display:none; color:red;">
                        {!! __('monthlyPRpageLang.enterclient') !!}</div>
                </div>
                <div class="col-auto">
                    <label class="col col-auto form-label">{!! __('outboundpageLang.isn') !!}</label>
                    <input class="form-control form-control-lg " type="text" id="number" name="number"
                        placeholder="{!! __('outboundpageLang.enterisn') !!}" oninput="if(value.length>12)value=value.slice(0,12)">
                    <div class="invalid-feedback" id="numbererror" style="display:none; color:red;">
                        {!! __('monthlyPRpageLang.enterisn') !!}</div>
                    <div class="invalid-feedback" id="numbererror1" style="display:none; color:red;">
                        {!! __('monthlyPRpageLang.isnlength') !!}
                    </div>
                    <div class="invalid-feedback" id="numbererror2" style="display:none; color:red;">
                        {!! __('monthlyPRpageLang.noisn') !!}
                    </div>
                </div>

                <div class="col-auto">
                    <label class="col col-auto form-label"></label>
                    <input type="submit" id="add" name="add"
                        class="form-control form-control-lg btn btn-lg btn-primary" value="{!! __('outboundpageLang.add') !!}">
                </div>
            </form>
        </div>

        <div class="card-body">
            <div style="display: none">
                <select style="width: 150px;" class="form-select form-select-lg " id="reason" name="reason">
                    <option style="display: none" disabled selected value="">{!! __('monthlyPRpageLang.entercontrol') !!}</option>
                    <option>品質問題</option>
                    <option>MPS上升</option>
                    <option>其他</option>
                </select>
            </div>
            <form id="notmonthadd">
                @csrf
                <div class="table-responsive">
                    <table class="table" id="notmonthtable">
                        <tbody id="notmonthbody">
                            <tr>
                                <td>{!! __('monthlyPRpageLang.delete') !!}</td>
                                <td>{!! __('monthlyPRpageLang.client') !!}</td>
                                <td>{!! __('monthlyPRpageLang.isn') !!}</td>
                                <td>{!! __('monthlyPRpageLang.buyamount') !!}</td>
                                <td>{!! __('monthlyPRpageLang.sxb') !!}</td>
                                <td>{!! __('monthlyPRpageLang.description') !!}</td>
                                <td>{!! __('monthlyPRpageLang.pName') !!}</td>
                                <td>{!! __('monthlyPRpageLang.unit') !!}</td>
                                <td>{!! __('templateWords.monthly') !!}</td>
                                <td>{!! __('monthlyPRpageLang.control') !!}</td>
                            </tr>
                            <tr>
                                <td><a id="deleteBtn0"></a></td>
                                <td><span id="client0">{{ $client }}</span></td>
                                <td><span id="number0">{{ $number }}</span></td>
                                <td><input type="number" class="form-control form-control-lg" id="amount0" name="amount0"
                                        placeholder="{!! __('monthlyPRpageLang.enteramount') !!}" min="1" value="1"></td>
                                <td><input type="text" class="form-control form-control-lg" id="sxb0" name="sxb0"
                                        placeholder="{!! __('monthlyPRpageLang.entersxb') !!}">
                                    <div class="invalid-feedback" id="sxberror0" style="display:none; color:red;">
                                        {!! __('monthlyPRpageLang.entersxb') !!}
                                    </div>
                                </td>
                                <td><input type="text" class="form-control form-control-lg" id="say0" name="say0"
                                        placeholder="{!! __('monthlyPRpageLang.enterdesc') !!}">
                                    <div class="invalid-feedback" id="sayerror0" style="display:none; color:red;">
                                        {!! __('monthlyPRpageLang.entersay') !!}
                                    </div>
                                </td>
                                <td><span id="name0">{{ $name }}</span></td>
                                <td><span id="unit0">{{ $unit }}</span></td>
                                <td><span id="month0">{{ $month }}</span></td>
                                <td>
                                    <select class="form-select form-select-lg " id="reason0" name="reason0">
                                        <option style="display: none" disabled selected value="">
                                            {!! __('monthlyPRpageLang.entercontrol') !!}</option>
                                        <option>品質問題</option>
                                        <option>MPS上升</option>
                                        <option>其他</option>
                                    </select>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                <div class="invalid-feedback" id="error" style="display:none;">
                    <h3 id="errorrow" style="color: red"></h3>
                    <h3 style="color: red">{!! __('monthlyPRpageLang.errormonth') !!}</h3>
                </div>

                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                <input type="submit" id="submit" name="submit" class="btn btn-lg btn-primary"
                    value="{!! __('monthlyPRpageLang.submit') !!}">
            </form>

            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            {{-- <button class="btn btn-lg btn-primary" onclick="location.href='{{route('month.importnotmonth')}}'">{!!
            __('monthlyPRpageLang.return') !!}</button> --}}
        </div>
    </div>
@endsection
