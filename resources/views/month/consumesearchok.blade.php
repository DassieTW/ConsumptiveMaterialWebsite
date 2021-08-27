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
                <h3>{!! __('monthlyPRpageLang.isnConsumeUpdate') !!}</h3>
            </div>
            <div class="card-body">
                <div class="d-flex w-100 h-100">
                    <div class="mb-3">
                        <form action="{{ route('month.consumechangeordelete') }}" method="POST">
                            @csrf
                        <table class="table">
                            <tr>
                                <th>{!! __('monthlyPRpageLang.delete') !!}</th>
                                <th>{!! __('monthlyPRpageLang.client') !!}</th>
                                <th>{!! __('monthlyPRpageLang.machine') !!}</th>
                                <th>{!! __('monthlyPRpageLang.process') !!}</th>
                                <th>{!! __('monthlyPRpageLang.isn') !!}</th>
                                <th>{!! __('monthlyPRpageLang.pName') !!}</th>
                                <th>{!! __('monthlyPRpageLang.format') !!}</th>
                                <th>{!! __('monthlyPRpageLang.consume') !!}</th>
                            </tr>
                                @foreach($data as $data)
                                <?php
                                    $name = DB::table('consumptive_material')->where('料號',$data->料號)->value('品名');
                                    $format = DB::table('consumptive_material')->where('料號',$data->料號)->value('規格');
                                ?>
                                <tr id= "{{$loop->index}}">
                                    <td><input class ="innumber" type="checkbox" id="innumber{{$loop->index}}" name="innumber{{$loop->index}}" style="width:20px;height:20px;" ></td>
                                    <td><input type = "hidden" id = "client{{$loop->index}}" name = "client{{$loop->index}}" value = "{{$data->客戶別}}">{{$data->客戶別}}</td>
                                    <td><input type = "hidden" id = "machine{{$loop->index}}" name = "machine{{$loop->index}}" value = "{{$data->機種}}">{{$data->機種}}</td>
                                    <td><input type = "hidden" id = "production{{$loop->index}}" name = "production{{$loop->index}}" value = "{{$data->製程}}">{{$data->製程}}</td>
                                    <td><input type = "hidden" id = "number{{$loop->index}}" name = "number{{$loop->index}}" value = "{{$data->料號}}">{{$data->料號}}</td>
                                    <td>{{$name}}</td>
                                    <td>{{$format}}</td>
                                    <td><input type = "number" id = "amount{{$loop->index}}" name = "amount{{$loop->index}}" value = "{{$data->單耗}}" step="0.01"></td>
                                </tr>
                                <input type = "hidden" id="count" name = "count" value="{{$loop->count}}">
                                @endforeach

                            </table>
                            <input type = "submit" id = "delete" name = "delete" class="btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.delete') !!}">
                            <input type = "submit" id = "change" name = "change" class="btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.change') !!}">
                        </form>

                    </div>
                </div>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('month.consume')}}'">{!! __('monthlyPRpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
