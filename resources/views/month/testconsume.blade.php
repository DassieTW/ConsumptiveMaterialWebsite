@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/month/testconsume.js') }}"></script>
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
                <h3>TestConsume</h3>
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
                                <th>{!! __('monthlyPRpageLang.consume') !!}</th>
                            </tr>
                                @foreach($data as $data)
                                <tr>
                                    <td><input type = "hidden" id = "number{{$loop->index}}" name = "number{{$loop->index}}" value = "{{$data->料號}}">{{$data->料號}}</td>
                                    <td><input type = "hidden" id = "client{{$loop->index}}" name = "client{{$loop->index}}" value = "{{$data->客戶別}}">{{$data->客戶別}}</td>
                                    <td><input type = "hidden" id = "machine{{$loop->index}}" name = "machine{{$loop->index}}" value = "{{$data->機種}}">{{$data->機種}}</td>
                                    <td><input type = "hidden" id = "production{{$loop->index}}" name = "production{{$loop->index}}" value = "{{$data->製程}}">{{$data->製程}}</td>
                                    <td><input style = "width: 200px;" class="form-control form-control-lg " type = "number" id = "amount{{$loop->index}}" name = "amount{{$loop->index}}" value = "{{$data->單耗}}" step="0.000000000000001"></td>
                                    <td><input type = "hidden" id = "compare{{$loop->index}}" name = "compare{{$loop->index}}" value = "{{$data->單耗}}"></td>
                                </tr>
                                <input type = "hidden" id = "count" name = "count" value = "{{$loop->count}}"></td>
                                @endforeach

                            </table>
                        </div>
                        <label class="form-label">{!! __('monthlyPRpageLang.surepeople') !!}</label>
                        <input type = "text" id = "jobnumber" name = "jobnumber" required>
                        <br>
                        <label class="form-label">{!! __('monthlyPRpageLang.surepeopleemail') !!}</label>
                        <input type="email" id="email" name = "email" pattern=".+@pegatroncorp\.com" required placeholder="xxx@pegartoncorp.com">
                        <br><br>
                        <input type = "submit" id = "submit" name = "submit" class="btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.submit') !!}">
                    </form>
                    <br>
            </div>
        </div>
</html>
@endsection
