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
        <h2>{!! __('templateWords.monthly') !!}</h2>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('monthlyPRpageLang.importNonMonthlyData') !!}</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('month.notmonthsearchoradd') }}" method="POST" >
                    @csrf
                    <div class="d-flex w-100 h-100">
                        <div class="mb-3">
                            <label class="form-label">{!! __('monthlyPRpageLang.client') !!}</label>
                            <select class="form-control form-control-lg @error('client') is-invalid @enderror" id = "client" name="client">
                            <option style="display: none" disabled selected>{!! __('monthlyPRpageLang.enterclient') !!}</option>
                            @foreach($client as $client)
                            <option>{{  $client->客戶 }}</option>
                            @endforeach
                            </select>
                            @error('client')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                            <label class="form-label">{!! __('monthlyPRpageLang.isn') !!}</label>
                            <input class="form-control form-control-lg @error('number') is-invalid @enderror" type="text" id ="number" name="number">
                            @error('number')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <input type = "submit" id = "search" name = "search" class="btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.search') !!}">
                    <input type = "submit" id = "add" name ="add" class="btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.add') !!}">
                </form>
                <br>
                <a class="btn btn-lg btn-primary" href="{{asset('download/ImportNotmonthExample.xlsx')}}" download>{!! __('monthlyPRpageLang.exampleExcel') !!}</a>
                <br>



                <form method="post" enctype="multipart/form-data" action = "{{ route('month.uploadnotmonth') }}">
                    @csrf
                    <div class="col-6 col-sm-3">
                        <label>{!! __('monthlyPRpageLang.plz_upload') !!}</label>
                        <input  class="form-control @error('select_file') is-invalid @enderror"  type="file" name="select_file" />
                        @error('select_file')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <br>
                        <input type="submit" name="upload" class="btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.upload') !!}">
                    </div>
                </form>


                <br>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('month.index')}}'">{!! __('monthlyPRpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
