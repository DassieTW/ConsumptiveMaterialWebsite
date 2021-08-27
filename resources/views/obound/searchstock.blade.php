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
                <h3>{!! __('oboundpageLang.searchstock') !!}</h3>
            </div>
            <div class="card-body">
                <div class="d-flex w-100 h-100">
                    <div class="mb-3">
                        <form action="{{ route('obound.searchstocksubmit') }}" method="POST">
                            @csrf
                            <div class="d-flex w-100 h-100">
                                <div class="mb-3">

                                    <label class="form-label">{!! __('oboundpageLang.bound') !!}</label>
                                    <select class="form-control form-control-lg" id = "bound" name="bound">
                                    <option style="display: none" disabled selected>{!! __('oboundpageLang.enterbound') !!}</option>
                                    @foreach($bound as $bound)
                                    <option>{{  $bound->O庫 }}</option>
                                    @endforeach
                                    </select>

                                    <label class="form-label">{!! __('oboundpageLang.client') !!}</label>
                                    <select class="form-control form-control-lg" id = "client" name="client">
                                    <option style="display: none" disabled selected>{!! __('oboundpageLang.enterclient') !!}</option>
                                    @foreach($client as $client)
                                    <option>{{  $client->客戶 }}</option>
                                    @endforeach
                                    </select>

                                    <label class="form-label">{!! __('oboundpageLang.oisn') !!}</label>
                                    <input class="form-control form-control-lg @error('number') is-invalid @enderror" type="text" id ="number" name="number" >
                                    @error('number')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                    <br>
                                    <input class ="basic" type="checkbox" id="nogood" name="nogood" style="width:20px;height:20px;" value="2">
                                    <label class="form-label">{!! __('oboundpageLang.nogood') !!}</label>

                                </div>
                            </div>
                            <input type = "submit" id = "search" name = "search" class="btn btn-lg btn-primary" value="{!! __('oboundpageLang.search') !!}">
                        </form>
                    </div>
                </div>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.index')}}'">{!! __('oboundpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
