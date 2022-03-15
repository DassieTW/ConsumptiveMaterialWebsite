@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">

@endsection

@section('js')
<script src="{{ asset('js/obound/search.js') }}"></script>
<!-- <script src="{{ asset('/js/popupNotice.js') }}"></script> -->


<!--for notifications pop up -->
@endsection
@section('content')
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>
<h2>{!! __('templateWords.obound') !!}</h2>
<div class="card">
    <div class="card-header">
        <h3>{!! __('oboundpageLang.matsInfo') !!}</h3>
        <input class="form-control form-control-lg " type="text" id="numbersearch" name="numbersearch"
                placeholder="{!! __('basicInfoLang.enterisn') !!}" oninput="if(value.length>12)value=value.slice(0,12)"
                style="width: 200px">
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table" id="material">
                <thead>
                    <tr>

                        <th><input type="hidden" id="title0" name="title0" value="料號">{!! __('oboundpageLang.isn') !!}
                        </th>
                        <th><input type="hidden" id="title1" name="title1" value="品名">{!! __('oboundpageLang.pName') !!}
                        </th>
                        <th><input type="hidden" id="title2" name="title2" value="規格">{!! __('oboundpageLang.format')
                            !!}</th>
                    </tr>
                </thead>
                @foreach($data as $data)
                <tr class="isnRows">
                    <td>{{$data->料號}}</td>
                    <input type="hidden" id="number{{$loop->index}}" value="{{$data->料號}}">
                    <td>{{$data->品名}}</td>
                    <td>{{$data->規格}}</td>
                </tr>
                @endforeach
            </table>
        </div>

        <button class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.material')}}'">{!!
            __('oboundpageLang.return') !!}</button>
    </div>
</div>

</html>
@endsection
