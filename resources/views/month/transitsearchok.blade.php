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
                <h3>{!! __('monthlyPRpageLang.on_the_way_search') !!}</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">

                        <table class="table">
                            <tr>
                                <th>{!! __('monthlyPRpageLang.client') !!}</th>
                                <th>{!! __('monthlyPRpageLang.isn') !!}</th>
                                <th>{!! __('monthlyPRpageLang.pName') !!}</th>
                                <th>{!! __('monthlyPRpageLang.unit') !!}</th>
                                <th>{!! __('monthlyPRpageLang.buyamount1') !!}</th>
                            </tr>
                                @foreach($data as $data)
                                <tr>
                                    <td>{{$data->客戶}}</td>
                                    <td>{{$data->料號}}</td>
                                    <td>{{$data->品名}}</td>
                                    <td>{{$data->規格}}</td>
                                    <td>{{$data->請購數量}}</td>
                                </tr>
                                @endforeach

                            </table>
                    </div>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('month.transit')}}'">{!! __('monthlyPRpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
