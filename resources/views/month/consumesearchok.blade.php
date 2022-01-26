@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/month/consumechange.js') }}"></script>
@endsection
@section('content')
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>
<h2>{!! __('templateWords.monthly') !!}</h2>
<div id="url"></div>
<div class="card">
    <div class="card-header">
        <h3>{!! __('monthlyPRpageLang.isnConsumeUpdate') !!}</h3>
    </div>
    <div class="card-body" id="consumebody">
        <form id="consume" method="POST">
            @csrf
            <div class="table-responsive">
                <table class="table">
                    <tr>
                        <th>{!! __('monthlyPRpageLang.check') !!}</th>
                        <th>{!! __('monthlyPRpageLang.client') !!}</th>
                        <th>{!! __('monthlyPRpageLang.machine') !!}</th>
                        <th>{!! __('monthlyPRpageLang.process') !!}</th>
                        <th>{!! __('monthlyPRpageLang.isn') !!}</th>
                        <th>{!! __('monthlyPRpageLang.pName') !!}</th>
                        <th>{!! __('monthlyPRpageLang.format') !!}</th>
                        <th>{!! __('monthlyPRpageLang.consume') !!}</th>
                        <th>{!! __('monthlyPRpageLang.remark') !!}</th>
                    </tr>
                    @foreach($data as $data)
                    <?php
                        $name = DB::table('consumptive_material')->where('料號',$data->料號)->value('品名');
                        $format = DB::table('consumptive_material')->where('料號',$data->料號)->value('規格');
                        $data->單耗 = round($data->單耗 , 10);
                    ?>
                    <tr id="{{$loop->index}}">
                        <td><input class="innumber" type="checkbox" id="innumber" name="innumber"
                                style="width:20px;height:20px;" value="{{$loop->index}}"></td>
                        <td><input type="hidden" id="client{{$loop->index}}" name="client{{$loop->index}}"
                                value="{{$data->客戶別}}">{{$data->客戶別}}</td>
                        <td><input type="hidden" id="machine{{$loop->index}}" name="machine{{$loop->index}}"
                                value="{{$data->機種}}">{{$data->機種}}</td>
                        <td><input type="hidden" id="production{{$loop->index}}" name="production{{$loop->index}}"
                                value="{{$data->製程}}">{{$data->製程}}</td>
                        <td><input type="hidden" id="number{{$loop->index}}" name="number{{$loop->index}}"
                                value="{{$data->料號}}">{{$data->料號}}</td>
                        <td>{{$name}}</td>
                        <td>{{$format}}</td>
                        <td><input style="width: 200px;" class="form-control form-control-lg " type="number"
                                id="amount{{$loop->index}}" name="amount{{$loop->index}}" value="{{$data->單耗}}"
                                step="0.0000000001" oninput="if(value.length>12)value=value.slice(0,12)" min="0"></td>
                        <td>{{$data->狀態}}</td>
                    </tr>
                    @endforeach

                </table>
            </div>

            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <label class="form-label">{!! __('monthlyPRpageLang.surepeople') !!}</label>
            <input type="text" id="jobnumber" name="jobnumber" class="form-control form-control" style="width: 250px">
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <label class="form-label">{!! __('monthlyPRpageLang.surepeopleemail') !!}</label>
            <input type="email" id="email" name="email" pattern=".+@pegatroncorp\.com"
                placeholder="xxx@pegartoncorp.com" class="form-control form-control" style="width: 250px">

            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <input type="submit" id="delete" name="delete" class="btn btn-lg btn-primary"
                value="{!! __('monthlyPRpageLang.delete') !!}">
            &emsp;
            <input type="submit" id="change" name="change" class="btn btn-lg btn-primary"
                value="{!! __('monthlyPRpageLang.change') !!}">
        </form>
        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
        <button class="btn btn-lg btn-primary" onclick="location.href='{{route('month.consume')}}'">{!!
            __('monthlyPRpageLang.return') !!}</button>
    </div>
</div>

</html>
@endsection
