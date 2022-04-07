@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')

<!-- <script src="{{ asset('/js/popupNotice.js') }}"></script> -->
<script src="{{ asset('js/obound/uploadnew.js') }}"></script>

<!--for notifications pop up -->
@endsection
@section('content')
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
</head>
<div id="mountingPoint">
    <div class="row mb-2 mb-xl-3 justify-content-between">
        <h2 class="col-auto">{!! __('templateWords.obound') !!}</h2>
        <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
            <vue-bread-crumb></vue-bread-crumb>
        </div>
    </div>
</div><div class="card">
    <div class="card-header">
        <h3>{!! __('oboundpageLang.newMats') !!}{!! __('oboundpageLang.upload') !!}</h3>
    </div>

    <div class="card-body">



        <form id = "uploadnew" method="POST">
            @csrf
            <div class="table-responsive">
                <table class="table" id="test">
                    <tr>
                        <th><input type="hidden" id="title0" name="title0" value="料號">{!! __('oboundpageLang.isn') !!}
                        </th>
                        <th><input type="hidden" id="title1" name="title1" value="品名">{!! __('oboundpageLang.pName') !!}
                        </th>
                        <th><input type="hidden" id="title2" name="title2" value="規格">{!! __('oboundpageLang.format')
                            !!}</th>
                    </tr>
                    @foreach($data as $row)
                    <tr id = "row{{$loop->index}}">
                        <td><input class = "form-control form-control-lg" type="text" id = "data0{{$loop->index}}" name="data0{{$loop->index}}" value="{{$row[0]}}"></td>
                        <td><input class = "form-control form-control-lg" type="text" id = "data1{{$loop->index}}" name="data1{{$loop->index}}" value="{{$row[1]}}"></td>
                        <td><input class = "form-control form-control-lg" type="text" id = "data2{{$loop->index}}" name="data2{{$loop->index}}" value="{{$row[2]}}"></td>
                    </tr>
                    <input type="hidden" id="count" name="count" value="{{$loop->count}}">
                    @endforeach

                </table>
            </div>
            <br>
            <input type="submit" id="delete" name="delete" class="btn btn-lg btn-primary"
                value="{!! __('oboundpageLang.addtodatabase') !!}">
        </form>
        <br>
        <button class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.new')}}'">{!!
            __('oboundpageLang.return') !!}</button>
    </div>
</div>

</html>
@endsection
