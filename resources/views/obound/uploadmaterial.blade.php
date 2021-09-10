@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')

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
                <h3>{!! __('oboundpageLang.newMats') !!}{!! __('oboundpageLang.upload') !!}</h3>
            </div>

            <div class="card-body">

                        <form method="post" enctype="multipart/form-data" action = "{{ route('obound.uploadmaterial') }}">
                            @csrf
                            <div class="col-6 ">
                                <label>{!! __('oboundpageLang.plz_upload') !!}</label>
                                <input  class="form-control @error('select_file') is-invalid @enderror"  type="file" name="select_file" />
                                @error('select_file')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <br>
                                <input type="submit" name="upload" class="btn btn-lg btn-primary" value="{!! __('oboundpageLang.upload') !!}">
                            </div>
                        </form>

                        <form  action = "{{ route('obound.insertuploadmaterial') }}"method="POST">
                            @csrf
                            <div class="table-responsive">
                            <table class="table" id = "test">
                                <tr>
                                    <th><input type = "hidden" id = "title1" name = "title1" value = "料號">{!! __('oboundpageLang.isn') !!}</th>
                                    <th><input type = "hidden" id = "title1" name = "title1" value = "品名">{!! __('oboundpageLang.pName') !!}</th>
                                    <th><input type = "hidden" id = "title1" name = "title1" value = "規格">{!! __('oboundpageLang.format') !!}</th>
                                </tr>
                                @foreach($data as $row)
                                <tr>

                                    <td><input type = "text"  name = "data0{{$loop->index}}" value = "{{$row[0]}}"></td>
                                    <td><input type = "text"  name = "data1{{$loop->index}}" value = "{{$row[1]}}"></td>
                                    <td><input type = "text"  name = "data2{{$loop->index}}" value = "{{$row[2]}}"></td>

                                </tr>
                                <input type = "hidden" id="count" name = "count" value="{{$loop->count}}">
                                @endforeach

                            </table>
                            </div>
                            <br>
                            <input type = "submit" id = "delete" name = "delete" class="btn btn-lg btn-primary" value="{!! __('oboundpageLang.addtodatabase') !!}">
                        </form>
                        <br>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.new')}}'">{!! __('oboundpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
