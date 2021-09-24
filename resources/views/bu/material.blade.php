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
        <h2>{!! __('bupagelang.bu') !!}</h2>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('bupagelang.factorychange') !!}</h3>
            </div>
            <div class="card-body">
                <div class="d-flex w-100 h-100">
                    <div class="mb-3">
                        <form  action="{{ route('bu.sluggishmaterial') }}" method="POST">
                            @csrf
                            <div class="d-flex w-100 h-100">
                                <div class="mb-3">
                                    <label class="form-label">{!! __('bupagelang.outfactory') !!}</label>
                                    <select class="form-control form-control-lg" id = "table" name="table">
                                    <option style="display: none" disabled selected value = "">{!! __('bupagelang.enterfactory') !!}</option>
                                    @foreach($factory as $factory)
                                    <option>{{  $factory->廠別 }}</option>
                                    @endforeach
                                    </select>
                                    <label class="form-label">{!! __('bupagelang.isn') !!}</label>
                                    <input class="form-control form-control-lg @error('number') is-invalid @enderror" type="text" id ="number" name="number" placeholder="{!! __('bupagelang.enterisn') !!}" required>
                                    @error('number')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                </div>
                            </div>
                            <input type = "submit" id = "search" name = "search" class="btn btn-lg btn-primary" value="{!! __('bupagelang.search') !!}">
                        </form>
                    </div>
                </div>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('bu.index')}}'">{!! __('bupagelang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
