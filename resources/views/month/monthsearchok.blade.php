@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('/js/month/monthsearch.js') }}"></script>
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
        <h3>{!! __('monthlyPRpageLang.importMonthlyData') !!}</h3>
    </div>
    <div class="card-body">
        <form id="monthsearch" method="POST">
            @csrf
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <td>{!! __('monthlyPRpageLang.delete') !!}</td>
                        <td>{!! __('monthlyPRpageLang.client') !!}</td>
                        <td>{!! __('monthlyPRpageLang.machine') !!}</td>
                        <td>{!! __('monthlyPRpageLang.process') !!}</td>
                        <td>{!! __('monthlyPRpageLang.nextmps') !!}</td>
                        <td>{!! __('monthlyPRpageLang.nextday') !!}</td>
                        <td>{!! __('monthlyPRpageLang.nowmps') !!}</td>
                        <td>{!! __('monthlyPRpageLang.nowday') !!}</td>
                        <td>{!! __('monthlyPRpageLang.writetime') !!}</td>
                    </tr>
                    @foreach($data as $data)
                    <tr>
                        <?php
                            $data->本月MPS = round($data->本月MPS , 5);
                            $data->本月生產天數 = round($data->本月生產天數 , 5);
                            $data->下月MPS = round($data->下月MPS , 5);
                            $data->下月生產天數 = round($data->下月生產天數 , 5);
                        ?>
                        <td><input class="innumber" type="checkbox" id="innumber" name="innumber"
                                style="width:20px;height:20px;" value="{{$loop->index}}"></td>
                        <td><input type="hidden" id="client{{$loop->index}}" name="client{{$loop->index}}"
                                value="{{$data->客戶別}}">{{$data->客戶別}}</td>
                        <td><input type="hidden" id="machine{{$loop->index}}" name="machine{{$loop->index}}"
                                value="{{$data->機種}}">{{$data->機種}}</td>
                        <td><input type="hidden" id="production{{$loop->index}}" name="production{{$loop->index}}"
                                value="{{$data->製程}}">{{$data->製程}}</td>
                        <td>{{$data->本月MPS}}</td>
                        <td>{{$data->本月生產天數}}</td>
                        <td>{{$data->下月MPS}}</td>
                        <td>{{$data->下月生產天數}}</td>
                        <td>{{$data->填寫時間}}</td>
                    </tr>
                    <input type="hidden" id="count" name="count" value="{{$loop->count}}">
                    @endforeach
                </table>
            </div>
            <br>
            <input type="submit" id="delete" name="delete" class="btn btn-lg btn-primary"
                value="{!! __('monthlyPRpageLang.delete') !!}">
        </form>
        <br>
        <button class="btn btn-lg btn-primary" onclick="location.href='{{route('month.importmonth')}}'">{!!
            __('monthlyPRpageLang.return') !!}</button>
    </div>
</div>

</html>
@endsection
