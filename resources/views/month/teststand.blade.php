@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/month/teststand.js') }}"></script>
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
                <h3>TestStand</h3>
            </div>
            <div class="card-body">
                <form id = "test" method="POST">
                    @csrf
                <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th>{!! __('monthlyPRpageLang.isn') !!}</th>
                                <th>{!! __('monthlyPRpageLang.client') !!}</th>
                                <th>{!! __('monthlyPRpageLang.machine') !!}</th>
                                <th>{!! __('monthlyPRpageLang.process') !!}</th>
                                <th>{!! __('monthlyPRpageLang.nowpeople') !!}</th>
                                <th>{!! __('monthlyPRpageLang.nowline') !!}</th>
                                <th>{!! __('monthlyPRpageLang.nowclass') !!}</th>
                                <th>{!! __('monthlyPRpageLang.nowuse') !!}</th>
                                <th>{!! __('monthlyPRpageLang.nowchange') !!}</th>
                                <th>{!! __('monthlyPRpageLang.nextpeople') !!}</th>
                                <th>{!! __('monthlyPRpageLang.nextline') !!}</th>
                                <th>{!! __('monthlyPRpageLang.nextclass') !!}</th>
                                <th>{!! __('monthlyPRpageLang.nextuse') !!}</th>
                                <th>{!! __('monthlyPRpageLang.nextchange') !!}</th>
                            </tr>
                                @foreach($data as $data)
                                <tr>
                                    <td><input type = "hidden" id = "number{{$loop->index}}" name = "number{{$loop->index}}" value = "{{$data->料號}}">{{$data->料號}}</td>
                                    <td><input type = "hidden" id = "client{{$loop->index}}" name = "client{{$loop->index}}" value = "{{$data->客戶別}}">{{$data->客戶別}}</td>
                                    <td><input type = "hidden" id = "machine{{$loop->index}}" name = "machine{{$loop->index}}" value = "{{$data->機種}}">{{$data->機種}}</td>
                                    <td><input type = "hidden" id = "production{{$loop->index}}" name = "production{{$loop->index}}" value = "{{$data->制程}}">{{$data->制程}}</td>
                                    <td><input style="width: 80px" type = "number" id = "nowpeople{{$loop->index}}" name = "nowpeople{{$loop->index}}" value = "{{$data->當月站位人數}}"></td>
                                    <td><input style="width: 80px" type = "number" id = "nowline{{$loop->index}}" name = "nowline{{$loop->index}}" value = "{{$data->當月開線數}}"></td>
                                    <td><input style="width: 80px" type = "number" id = "nowclass{{$loop->index}}" name = "nowclass{{$loop->index}}" value = "{{$data->當月開班數}}"></td>
                                    <td><input style="width: 80px" type = "number" id = "nowuse{{$loop->index}}" name = "nowuse{{$loop->index}}" value = "{{$data->當月每人每日需求量}}"></td>
                                    <td><input style="width: 80px" type = "number" id = "nowchange{{$loop->index}}" name = "nowchange{{$loop->index}}" value = "{{$data->當月每日更換頻率}}"></td>
                                    <td><input style="width: 80px" type = "number" id = "nextpeople{{$loop->index}}" name = "nextpeople{{$loop->index}}" value = "{{$data->下月站位人數}}"></td>
                                    <td><input style="width: 80px" type = "number" id = "nextline{{$loop->index}}" name = "nextline{{$loop->index}}" value = "{{$data->下月開線數}}"></td>
                                    <td><input style="width: 80px" type = "number" id = "nextclass{{$loop->index}}" name = "nextclass{{$loop->index}}" value = "{{$data->下月開班數}}"></td>
                                    <td><input style="width: 80px" type = "number" id = "nextuse{{$loop->index}}" name = "nextuse{{$loop->index}}" value = "{{$data->下月每人每日需求量}}"></td>
                                    <td><input style="width: 80px" type = "number" id = "nextchange{{$loop->index}}" name = "nextchange{{$loop->index}}" value = "{{$data->下月每日更換頻率}}"></td>

                                    <input  type = "hidden" id = "comnowpeople{{$loop->index}}" name = "comnowpeople{{$loop->index}}" value = "{{$data->當月站位人數}}">
                                    <input  type = "hidden" id = "comnowline{{$loop->index}}" name = "comnowline{{$loop->index}}" value = "{{$data->當月開線數}}">
                                    <input  type = "hidden" id = "comnowclass{{$loop->index}}" name = "comnowclass{{$loop->index}}" value = "{{$data->當月開班數}}">
                                    <input  type = "hidden" id = "comnowuse{{$loop->index}}" name = "comnowuse{{$loop->index}}" value = "{{$data->當月每人每日需求量}}">
                                    <input  type = "hidden" id = "comnowchange{{$loop->index}}" name = "comnowchange{{$loop->index}}" value = "{{$data->當月每日更換頻率}}">
                                    <input  type = "hidden" id = "comnextpeople{{$loop->index}}" name = "comnextpeople{{$loop->index}}" value = "{{$data->下月站位人數}}">
                                    <input  type = "hidden" id = "comnextline{{$loop->index}}" name = "comnextline{{$loop->index}}" value = "{{$data->下月開線數}}">
                                    <input  type = "hidden" id = "comnextclass{{$loop->index}}" name = "comnextclass{{$loop->index}}" value = "{{$data->下月開班數}}">
                                    <input  type = "hidden" id = "comnextuse{{$loop->index}}" name = "comnextuse{{$loop->index}}" value = "{{$data->下月每人每日需求量}}">
                                    <input  type = "hidden" id = "comnextchange{{$loop->index}}" name = "comnextchange{{$loop->index}}" value = "{{$data->下月每日更換頻率}}">
                                </tr>
                                <input type = "hidden" id = "count" name = "count" value = "{{$loop->count}}"></td>
                                @endforeach

                            </table>
                        </div>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <label class="form-label">{!! __('monthlyPRpageLang.surepeople') !!}:</label>
                        <input type = "text" id = "jobnumber" name = "jobnumber" required>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <label class="form-label">{!! __('monthlyPRpageLang.surepeopleemail') !!}:</label>
                        <input type="email" id="email" name = "email" pattern=".+@pegatroncorp\.com" required placeholder="xxx@pegartoncorp.com">
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line--><div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <input type = "submit" id = "submit" name = "submit" class="btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.submit') !!}">
                    </form>
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            </div>
        </div>
</html>
@endsection
