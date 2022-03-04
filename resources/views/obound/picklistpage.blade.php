@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')

<!-- <script src="{{ asset('js/popupNotice.js') }}"></script> -->
<!--for notifications pop up -->
@endsection
@section('content')
<!DOCTYPE html>
<html>
@if ($num > 0)
<audio controls autoplay hidden>
    <source id="audio_1" src="/sound/Opicklist.mp3" type="audio/mpeg">
</audio>
@endif

<head>
    <meta charset="utf-8">
</head>
<h2>{!! __('templateWords.obound') !!}</h2>
<div class="row justify-content-center">
    <div class="card w-75">
        <div class="card-header">
            <h3>{!! __('oboundpageLang.picklist') !!}</h3>
        </div>
        <div class="row justify-content-center">
            <div class="card-body">
                <form action="{{ route('obound.picklist') }}" method="POST">
                    @csrf
                    <div class="row w-100 justify-content-center mb-3">

                        <label class="col col-auto form-label">{!! __('oboundpageLang.picklist') !!}</label>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <div class="col-lg-6  col-md-12 col-sm-12">

                            <select class="form-select form-select-lg" id="list" name="list" required>
                                <option style="display: none" disabled selected value="">{!!
                                    __('oboundpageLang.enterpicklist') !!}</option>
                                @foreach($data as $data)
                                <option>{{ $data->領料單號 }}</option>
                                @endforeach
                            </select>

                            @error('list')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row w-100 justify-content-center">
                        <div class="col col-auto">
                            <input type="submit" id="submit" name="submit" class="btn btn-lg btn-primary"
                                value="{!! __('oboundpageLang.searchpicklist') !!}">
                            &emsp;
                            <input type="submit" id="delete" name="delete" class="btn btn-lg btn-primary"
                                value="{!! __('oboundpageLang.deletepicklist') !!}">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</html>
@endsection
