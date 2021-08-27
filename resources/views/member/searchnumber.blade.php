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
        <h2>{!! __('templateWords.userManage') !!}</h2>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('templateWords.PInfo') !!}</h3>
            </div>
            <div class="card-body">
                <div class="d-flex w-100 h-100">
                    <div class="mb-3">
                        <form action="{{ route('member.searchnumber') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">{!! __('loginPageLang.jobsearch') !!}</label>
                            <input class="form-control form-control-lg @error('number') is-invalid @enderror"
                                type="text" id="number" name="number" placeholder="{!! __('loginPageLang.enterjobsearch') !!}" />
                            @error('number')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                        <div class="text-center mt-3">
                            <input type = "submit" class="btn btn-lg btn-primary" value="{!! __('loginPageLang.search') !!}">
                        </div>
                    </form>
                    <div class="text-center mt-3">
                        <button class="btn btn-lg btn-primary" onclick="location.href='{{route('member.index')}}'">{!! __('loginPageLang.return') !!}</button>
                    </div>
                    </div>
                </div>
            </div>
        </div>
</html>
@endsection
