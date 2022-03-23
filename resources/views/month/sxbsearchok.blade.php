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
    <h2>{!! __('templateWords.monthly') !!}</h2>
    <div class="card">
        <div class="card-header">
            <h3>{!! __('monthlyPRpageLang.SXB_search') !!}</h3>
            <input class="form-control form-control-lg " type="text" id="numbersearch" name="numbersearch"
                placeholder="{!! __('basicInfoLang.enterisn') !!}" oninput="if(value.length>12)value=value.slice(0,12)"
                style="width: 200px">
        </div>
        <div class="card-body">
            <div class="table-responsive">

                <table class="table">
                    <thead>
                    <tr>
                        <th>{!! __('monthlyPRpageLang.sxb') !!}</th>
                        <th>{!! __('monthlyPRpageLang.srm') !!}</th>
                        <th>{!! __('monthlyPRpageLang.client') !!}</th>
                        <th>{!! __('monthlyPRpageLang.isn') !!}</th>
                        <th>{!! __('monthlyPRpageLang.pName') !!}</th>
                        <th>{!! __('monthlyPRpageLang.buyamount') !!}</th>
                        <th>{!! __('monthlyPRpageLang.buytime') !!}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($data as $data)
                        <tr class="isnRows">
                            <?php $data->本次請購數量 = round($data->本次請購數量); ?>
                            <td>{{ $data->SXB單號 }}</td>
                            <td>{{ $data->SRM單號 }}</td>
                            <td>{{ $data->客戶 }}</td>
                            <td>{{ $data->料號 }}</td>
                            <input type="hidden" id="dataa{{ $loop->index }}" value="{{ $data->料號 }}">
                            <td>{{ $data->品名 }}</td>
                            <td>{{ $data->本次請購數量 }}</td>
                            <td>{{ $data->請購時間 }}</td>
                        </tr>
                    @endforeach

                    @foreach ($data1 as $data)
                        <?php
                        $name = DB::table('consumptive_material')
                        ->where('料號', $data->料號)
                        ->value('品名');
                        $data->請購數量 = round($data->請購數量);
                        ?>
                        <tr class="isnRows1">
                            <td>{{ $data->SXB單號 }}</td>
                            <td>{!! __('monthlyPRpageLang.notmonth') !!}</td>
                            <td>{{ $data->客戶別 }}</td>
                            <td>{{ $data->料號 }}</td>
                            <input type="hidden" id="dataa1{{ $loop->index }}" value="{{ $data->料號 }}">
                            <td>{{ $name }}</td>
                            <td>{{ $data->請購數量 }}</td>
                            <td>{{ $data->上傳時間 }}</td>
                        </tr>
                    @endforeach
                </tbody>
                </table>
            </div>
            <button class="btn btn-lg btn-primary"
                onclick="location.href='{{ route('month.sxb') }}'">{!! __('monthlyPRpageLang.return') !!}</button>
        </div>
    </div>

    </html>
@endsection
