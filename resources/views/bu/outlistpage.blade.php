@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
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
        <h2>{!! __('bupagelang.bu') !!}</h2>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('bupagelang.outlist') !!}</h3>
            </div>
            <div class="card-body">
                <form  action="{{ route('bu.outlist') }}" method="POST">
                    @csrf
                    <div class="d-flex w-100 h-100">
                        <div class="mb-3">
                            <label class="form-label">{!! __('bupagelang.outlist') !!}</label>
                            <select class="form-control form-control-lg" id = "list" name="list" required>
                            <option style="display: none" disabled selected value = "">{!! __('bupagelang.enteroutlist') !!}</option>
                            @foreach($data as $data)
                            <option>{{  $data->調撥單號 }}</option>
                            @endforeach
                            </select>

                        </div>
                    </div>
                    <input type = "submit" id = "submit" name = "submit" class="btn btn-lg btn-primary" value="{!! __('bupagelang.searchoutlist') !!}">
                </form>
                <br>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('bu.index')}}'">{!! __('bupagelang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
