@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/inbound/add.js') }}"></script>
@endsection
@section('content')
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>
<h2>{!! __('templateWords.inbound') !!}</h2>

<div class="card w-100">
    <div class="card-header">
        <h3>{!! __('inboundpageLang.new') !!}</h3>
    </div>
    <div class="card-body">
        <form id="add" class="row gx-6 gy-1 align-items-center">
            @csrf
            <div class="col-auto">
                <label class="col col-lg-12 form-label">{!! __('inboundpageLang.client') !!}</label>

                <select class="form-select form-select-lg" id="client" name="client" required>
                    <option style="display: none" disabled selected value="">{!!
                        __('inboundpageLang.enterclient') !!}</option>
                    @foreach($client as $client)
                    <option>{{ $client->客戶 }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-auto">

                <label class="col col-lg-12 form-label">{!! __('inboundpageLang.inreason') !!}</label>

                <select class="form-select form-select-lg " id="inreason" name="inreason" required>
                    <option style="display: none" disabled selected value="">{!!
                        __('inboundpageLang.enterinreason') !!}</option>
                    @foreach($inreason as $inreason)
                    <option>{{ $inreason->入庫原因 }}</option>
                    @endforeach
                    <option>{!! __('inboundpageLang.other') !!}</option>
                </select>
            </div>

            <div class="col-auto">
                <label class="col col-lg-12 form-label">{!! __('inboundpageLang.isn') !!}</label>
                <input class="form-control form-control-lg " type="text" id="number" name="number"
                    placeholder="{!! __('inboundpageLang.enterisn') !!}">

                <div id="numbererror" style="display:none; color:red;">{!!
                    __('inboundpageLang.isnlength')
                    !!}</div>
                <div id="numbererror1" style="display:none; color:red;">{!! __('inboundpageLang.noisn')
                    !!}
                </div>
                <div id="notransit" style="display:none; color:red;">{!! __('inboundpageLang.notransit')
                    !!}
                </div>
            </div>
            <div class="col-auto">
                <label class="col col-auto form-label"></label>
                <input type="submit" onclick="buttonIndex=0;" id="addto" name="addto" class="btn btn-lg btn-primary"
                    value="{!! __('inboundpageLang.add') !!}">
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                <input type="submit" onclick="buttonIndex=1;" id="addclient" name="addclient"
                    class="btn btn-lg btn-primary" value="{!! __('inboundpageLang.addclient') !!}">
            </div>
            <div class="col-auto">
                <label class="col col-auto form-label"></label>
                <input style="display:none;" class="form-control form-control-lg " type="text" id="reason" name="reason"
                    placeholder="{!! __('inboundpageLang.inputinreason') !!}">
            </div>

        </form>
    </div>
</div>

<div class="card w-100">
    <div class="card-body">
        <form id="testform" style="display: none;">
            @csrf
            <div class="table-responsive">
                <table class="table" id="inboundaddtable">
                    <tbody id="inboundaddbody">
                        <tr>
                            <th>{!! __('inboundpageLang.delete') !!}</th>
                            <th>{!! __('inboundpageLang.client') !!}</th>
                            <th>{!! __('inboundpageLang.isn') !!}</th>
                            <th>{!! __('inboundpageLang.pName') !!}</th>
                            <th>{!! __('inboundpageLang.format') !!}</th>
                            <th>{!! __('inboundpageLang.unit') !!}</th>
                            <th>{!! __('inboundpageLang.transit') !!}</th>
                            <th>{!! __('inboundpageLang.nowstock') !!}</th>
                            <th>{!! __('inboundpageLang.safe') !!}</th>
                            <th>{!! __('inboundpageLang.inboundnum') !!}</th>
                            <th>{!! __('inboundpageLang.inreason') !!}</th>
                            <th>{!! __('inboundpageLang.oldloc') !!}</th>
                            <th>{!! __('inboundpageLang.newloc') !!}</th>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div style="display: none" id="showposition">
                {{ $copylocs = App\Models\儲位::all() }}
                <select class="form-select form-select-lg" id="copyloc" name="copyloc" width="250" style="width: 250px"
                    required>
                    <option style="display: none" disabled selected value="">{!!
                        __('inboundpageLang.enterloc') !!}</option>
                    @foreach($copylocs as $position)
                    <option>{{ $position->儲存位置 }}</option>
                    @endforeach
                </select>
            </div>

            <div style="display: none" id="showname">
                {{ $name = App\Models\人員信息::all() }}
                {{ $check = App\Models\人員信息::all() }}
                @foreach($check as $people)
                <input type="hidden" id="checkpeople{{$loop->index}}" name="checkpeople{{$loop->index}}"
                    value="{{$people->工號}}">
                <input type="hidden" id="checkcount" name="checkcount" value="{{$loop->count}}">
                @endforeach
            </div>

            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <div class="mb-3 col-md-6">
                <label class="form-label">{!! __('inboundpageLang.inpeople') !!}</label>
                <input class="form-control form-control-lg" id="inpeople" name="inpeople" required style="width: 250px"
                    placeholder="{!! __('inboundpageLang.enterinpeople') !!}">
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                <ul id="inboundmenu" style="display: none;" class="list-group">
                    @foreach($name as $name)
                    <a class="inboundlist list-group-item list-group-item-action" href="#">{{ $name->工號 .' '.
                        $name->姓名 }}</a>
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                    @endforeach
                </ul>
            </div>

            <div class="row w-100 justify-content-center">
                <div class="col col-auto">
                    <input type="submit" class="btn btn-lg btn-primary" value="{!! __('inboundpageLang.submit') !!}"
                        style="width: 80px">
                </div>
            </div>
        </form>
    </div>
</div>

</html>
@endsection
