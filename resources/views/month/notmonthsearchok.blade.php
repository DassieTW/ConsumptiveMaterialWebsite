@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
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
                <h3>{!! __('monthlyPRpageLang.importNonMonthlyData') !!}</h3>
            </div>
            <div class="card-body">
                <div class="d-flex w-100 h-100">
                    <div class="mb-3">
                        <table class="table">
                            <tr>
                                <td>{!! __('monthlyPRpageLang.client') !!}</td>
                                <td>{!! __('monthlyPRpageLang.isn') !!}</td>
                                <td>{!! __('monthlyPRpageLang.buyamount') !!}</td>
                                <td>{!! __('monthlyPRpageLang.uploadtime') !!}</td>
                                <td>{!! __('monthlyPRpageLang.description') !!}</td>
                                <td>{!! __('monthlyPRpageLang.sxb') !!}</td>
                            </tr>
                            @foreach($data as $data)
                            <tr>
                                <td>{{$data->客戶別}}</td>
                                <td>{{$data->料號}}</td>
                                <td>{{$data->請購數量}}</td>
                                <td>{{$data->上傳時間}}</td>
                                <td>{{$data->說明}}</td>
                                <td>{{$data->SXB單號}}</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
                <br>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('month.importnotmonth')}}'">{!! __('monthlyPRpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
