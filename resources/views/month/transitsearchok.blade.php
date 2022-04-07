@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
<style>
    /* for single line table with over-flow , SAP style as asked */
    table {
        table-layout: fixed;
        /* width: 900px; */
    }

    .table-responsive {
        height: 600px;
        overflow: scroll;
    }

    thead tr:nth-child(1) th {
        background: white;
        position: sticky;
        top: 0;
        z-index: 10;
    }

</style>
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/month/sxbsearch.js') }}"></script>

@endsection
@section('content')
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>
<div id="mountingPoint">
    <div class="row mb-2 mb-xl-3 justify-content-between">
        <h2 class="col-auto">{!! __('templateWords.monthly') !!}</h2>
        <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
            <vue-bread-crumb></vue-bread-crumb>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3>{!! __('monthlyPRpageLang.on_the_way_search') !!}</h3>
            <input class="form-control form-control-lg " type="text" id="numbersearch" name="numbersearch"
                placeholder="{!! __('basicInfoLang.enterisn') !!}" oninput="if(value.length>12)value=value.slice(0,12)"
                style="width: 200px">

        </div>
        <div class="card-body">
            <div class="table-responsive">

                <table class="table">
                    <thead>
                        <tr>
                            <th>{!! __('monthlyPRpageLang.client') !!}</th>
                            <th>{!! __('monthlyPRpageLang.isn') !!}</th>
                            <th>{!! __('monthlyPRpageLang.pName') !!}</th>
                            <th>{!! __('monthlyPRpageLang.unit') !!}</th>
                            <th>{!! __('monthlyPRpageLang.buyamount1') !!}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $data)
                        <tr class="isnRows">
                            <?php
                    $data->請購數量 = round($data->請購數量);
                    ?>
                            <td>{{$data->客戶}}</td>
                            <td>{{$data->料號}}</td>
                            <input type="hidden" id="dataa{{$loop->index}}" value="{{$data->料號}}">
                            <td>{{$data->品名}}</td>
                            <td>{{$data->單位}}</td>
                            <td>{{$data->請購數量}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{-- <button class="btn btn-lg btn-primary" onclick="location.href='{{route('month.transit')}}'">{!!
            __('monthlyPRpageLang.return') !!}</button> --}}
        </div>
    </div>
</div>

</html>
@endsection
