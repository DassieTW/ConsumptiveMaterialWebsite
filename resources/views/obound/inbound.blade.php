@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')

<script src="{{ asset('js/obound/inbound.js') }}"></script>
<!--for notifications pop up -->
@endsection
@section('content')
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>
<h2>{!! __('templateWords.obound') !!}</h2>
<div class="card w-100">
    <div class="card-header">
        <h3>{!! __('oboundpageLang.inbound') !!}</h3>
    </div>
    <div class="card-body">
        <form id="add" class="row gx-6 gy-1 align-items-center">
            @csrf
            <div class="col-auto">
                <label class="col col-lg-12 form-label">{!! __('oboundpageLang.client') !!}</label>
                <select class="form-select form-select-lg" id="client" name="client" required>
                    <option style="display: none" disabled selected value="">{!!
                        __('oboundpageLang.enterclient') !!}</option>
                    @foreach($client as $client)
                    <option>{{ $client->客戶 }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-auto">
                <label class="col col-lg-12 form-label">{!! __('oboundpageLang.inreason') !!}</label>
                <select class="form-select form-select-lg " id="inreason" name="inreason" required>
                    <option style="display: none" disabled selected value="">{!!
                        __('oboundpageLang.enterinreason') !!}</option>
                    @foreach($inreason as $inreason)
                    <option>{{ $inreason->入庫原因 }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-auto">

                <label class="col col-lg-12 form-label">{!! __('oboundpageLang.oisn') !!}</label>

                <input class="form-control form-control-lg " type="text" id="number" name="number" required placeholder="{!!
                                __('oboundpageLang.enterisn') !!}">
                <div id="numbererror" style="display:none; color:red;">{!! __('oboundpageLang.noisn') !!}
                </div>
            </div>

            <div class="col-auto">
                <label class="col col-auto form-label"></label>
                <input type="submit" class="btn btn-lg btn-primary" value="{!! __('oboundpageLang.add') !!}">
            </div>
        </form>
    </div>
</div>

<div style="display: none" id="showposition">
    <select id="copybound" name="copybound">
        <option style="display: none" disabled selected value="">{!!
            __('oboundpageLang.enterbound') !!}</option>
        @foreach($bounds as $bound)
        <option>{{ $bound->O庫 }}</option>
        @endforeach
    </select>
</div>

<div class="card w-100">
    <div class="card-body">
        <form id="inboundaddform" style="display: none;">
            @csrf
            <div class="table-responsive">
                <table class="table" id="inboundaddtable">
                    <tbody id="inboundaddbody">
                        <tr>
                            <th>{!! __('oboundpageLang.delete') !!}</th>
                            <th>{!! __('oboundpageLang.client') !!}</th>
                            <th>{!! __('oboundpageLang.isn') !!}</th>
                            <th>{!! __('oboundpageLang.pName') !!}</th>
                            <th>{!! __('oboundpageLang.format') !!}</th>
                            <th>{!! __('oboundpageLang.inboundnum') !!}</th>
                            <th>{!! __('oboundpageLang.mark') !!}</th>
                            <th>{!! __('oboundpageLang.inreason') !!}</th>
                            <th>{!! __('oboundpageLang.bound') !!}</th>
                        </tr>
                    </tbody>
                </table>
            </div>


            <div style="display: none" id="showname">
                @foreach($checks as $people)
                <input type="hidden" id="checkpeople{{$loop->index}}" name="checkpeople{{$loop->index}}"
                    value="{{$people->工號}}">
                <input type="hidden" id="checkcount" name="checkcount" value="{{$loop->count}}">
                @endforeach
            </div>

            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <div class="mb-3 col-md-6">
                <label class="form-label">{!! __('oboundpageLang.inpeople') !!}</label>
                <input class="form-control form-control-lg" id="inpeople" name="inpeople" required style="width: 250px"
                    placeholder="{!! __('oboundpageLang.enterinpeople') !!}"
                    oninput="if(value.length>9)value=value.slice(0,9)">
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                <ul id="inboundmenu" style="display: none;" class="list-group">
                    @foreach($peoples as $name)
                    <a class="inboundlist list-group-item list-group-item-action" href="#">{{ $name->工號 .' '.
                        $name->姓名 }}</a>
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                    @endforeach
                </ul>


                {{-- rfid --}}
                <input class="form-control form-control-lg rfid" id="rfidinpeople" name="rfidinpeople" width="250"
                    style="width: 250px" placeholder="{!! __('inboundpageLang.rfidinpeople') !!}" type="password">

            </div>
            <div class="row w-100 justify-content-center">
                <div class="col col-auto">
                    <input type="submit" class="btn btn-lg btn-primary" value="{!! __('oboundpageLang.submit') !!}"
                        style="width: 80px">
                </div>
            </div>
        </form>
    </div>
</div>

</html>
@endsection
