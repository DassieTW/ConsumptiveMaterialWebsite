@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/month/srmsubmit.js') }}"></script>
@endsection
@section('content')
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>
<h2>{!! __('templateWords.monthly') !!}</h2>
<div class="card">
    <div class="card-header">
        <h3>{!! __('monthlyPRpageLang.SRM') !!}</h3>
    </div>
    <div class="card-body">

        <form id = "srmsubmit" method="POST">
            @csrf
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th>{!! __('monthlyPRpageLang.sxb') !!}</th>
                        <th>{!! __('monthlyPRpageLang.srm') !!}</th>
                        <th>{!! __('monthlyPRpageLang.client') !!}</th>
                        <th>{!! __('monthlyPRpageLang.isn') !!}</th>
                        <th>{!! __('monthlyPRpageLang.pName') !!}</th>
                        <th>{!! __('monthlyPRpageLang.buyamount') !!}</th>
                        <th>{!! __('monthlyPRpageLang.sxbamount') !!}</th>
                        <th>{!! __('monthlyPRpageLang.moq') !!}</th>
                        <th>{!! __('monthlyPRpageLang.nextneed') !!}</th>
                        <th>{!! __('monthlyPRpageLang.nowneed') !!}</th>
                        <th>{!! __('monthlyPRpageLang.safe') !!}</th>
                        <th>{!! __('monthlyPRpageLang.price') !!}</th>
                        <th>{!! __('monthlyPRpageLang.money') !!}</th>
                        <th>{!! __('monthlyPRpageLang.rate') !!}</th>
                        <th>{!! __('monthlyPRpageLang.transit') !!}</th>
                        <th>{!! __('monthlyPRpageLang.nowstock') !!}</th>
                        <th>{!! __('monthlyPRpageLang.realneed') !!}</th>
                        <th>{!! __('monthlyPRpageLang.buyprice') !!}</th>
                        <th>{!! __('monthlyPRpageLang.needprice') !!}</th>
                        <th>{!! __('monthlyPRpageLang.buytime') !!}</th>
                        <th>{!! __('monthlyPRpageLang.needper') !!}</th>
                        <th>{!! __('monthlyPRpageLang.buyper') !!}</th>
                    </tr>
                    @foreach($data as $data)
                    <tr>
                        <?php
                            $data->本次請購數量 = round($data->本次請購數量, 0);
                        ?>
                        <td><input style="width:150px" type="text" id="sxbnumber{{$loop->index}}"
                                name="sxbnumber{{$loop->index}}" required placeholder="請輸入SXB單號"></td>
                        <td><input type="hidden" id="srmnumber{{$loop->index}}" name="srmnumber{{$loop->index}}"
                                value="{{$data->SRM單號}}">{{$data->SRM單號}}</td>
                        <td><input type="hidden" id="client{{$loop->index}}" name="client{{$loop->index}}"
                                value="{{$data->客戶}}">{{$data->客戶}}</td>
                        <td><input type="hidden" id="number{{$loop->index}}" name="number{{$loop->index}}"
                                value="{{$data->料號}}">{{$data->料號}}</td>
                        <td>{{$data->品名}}</td>
                        <td><input type="hidden" id="buyamount{{$loop->index}}" name="buyamount{{$loop->index}}"
                                value="{{$data->本次請購數量}}">{{$data->本次請購數量}}</td>
                        <td><input style="width:100px" type="number" id="sxbamount{{$loop->index}}"
                                name="sxbamount{{$loop->index}}" required value="{{$data->本次請購數量}}" min = "0"></td>
                        <td>{{$data->MOQ}}</td>
                        <td>{{$data->下月需求}}</td>
                        <td>{{$data->當月需求}}</td>
                        <td>{{$data->安全庫存}}</td>
                        <td>{{$data->單價}}</td>
                        <td>{{$data->幣別}}</td>
                        <td>{{$data->匯率}}</td>
                        <td>{{$data->在途數量}}</td>
                        <td>{{$data->現有庫存}}</td>
                        <td>{{$data->實際需求}}</td>
                        <td>{{$data->請購金額}}</td>
                        <td>{{$data->需求金額}}</td>
                        <td>{{$data->請購時間}}</td>
                        <td>{{$data->需求占比}}</td>
                        <td>{{$data->請購占比}}</td>
                    </tr>
                    <input type="hidden" id="count" name="count" value="{{$loop->count}}">
                    @endforeach
                </table>
            </div>
            <br>
            <input type="submit" id="submit" name="submit" class="btn btn-lg btn-primary"
                value="{!! __('monthlyPRpageLang.submit') !!}">
        </form>

        <br>
        <button class="btn btn-lg btn-primary" onclick="location.href='{{route('month.srm')}}'">{!!
            __('monthlyPRpageLang.return') !!}</button>
    </div>
</div>

</html>
@endsection
