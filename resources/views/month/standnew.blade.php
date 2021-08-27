@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/month/standnew.js') }}"></script>
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
                <h3>{!! __('monthlyPRpageLang.standAdd') !!}</h3>
            </div>
            <div class="card-body">
                <form id = "standnew">
                    @csrf
                    <div class="row">
                        <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('monthlyPRpageLang.isn') !!}</label>
                            <input class="form-control form-control-lg" type="text" id ="number" name="number" readonly value = "{{ Session::get('number') }}">
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('monthlyPRpageLang.pName') !!}</label>
                            <input class="form-control form-control-lg " type="text" id ="name" name="name" readonly value = "{{ Session::get('name') }}">
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('monthlyPRpageLang.unit') !!}</label>
                            <input class="form-control form-control-lg " type="text" id ="unit" name="unit" readonly value = "{{ Session::get('unit') }}">
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('monthlyPRpageLang.mpq') !!}</label>
                            <input class="form-control form-control-lg " type="text" id ="mpq" name="mpq" readonly value = "{{ Session::get('mpq') }}">
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('monthlyPRpageLang.client') !!}</label>
                            <input class="form-control form-control-lg " type="text" id ="client" name="client" readonly value = "{{ Session::get('client') }}">
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('monthlyPRpageLang.machine') !!}</label>
                            <input class="form-control form-control-lg " type="text" id ="machine" name="machine" readonly value = "{{ Session::get('machine') }}">
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('monthlyPRpageLang.nowpeople') !!}</label>
                            <input class="form-control form-control-lg " type="number" id ="nowpeople" name="nowpeople" value = "0">
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('monthlyPRpageLang.nowline') !!}</label>
                            <input class="form-control form-control-lg " type="number" id ="nowline" name="nowline" value = "0" >
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('monthlyPRpageLang.nowclass') !!}</label>
                            <input class="form-control form-control-lg " type="number" id ="nowclass" name="nowclass" value = "0" >
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('monthlyPRpageLang.nowuse') !!}</label>
                            <input class="form-control form-control-lg " type="number" id ="nowuse" name="nowuse" value = "0" >
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('monthlyPRpageLang.nowchange') !!}</label>
                            <input class="form-control form-control-lg " type="number" id ="nowchange" name="nowchange" value = "0" >
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('monthlyPRpageLang.nowdayneed') !!}</label>
                            <input class="form-control form-control-lg " type="number" id ="nowneed" name="nowneed" readonly>
                        </div>

			            <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('monthlyPRpageLang.nextpeople') !!}</label>
                            <input class="form-control form-control-lg " type="number" id ="nextpeople" name="nextpeople" value = "0" >
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('monthlyPRpageLang.nextline') !!}</label>
                            <input class="form-control form-control-lg " type="number" id ="nextline" name="nextline" value = "0" >
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('monthlyPRpageLang.nextclass') !!}</label>
                            <input class="form-control form-control-lg " type="number" id ="nextclass" name="nextclass" value = "0"  >
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('monthlyPRpageLang.nextuse') !!}</label>
                            <input class="form-control form-control-lg " type="number" id ="nextuse" name="nextuse" value = "0" >
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('monthlyPRpageLang.nextchange') !!}</label>
                            <input class="form-control form-control-lg " type="number" id ="nextchange" name="nextchange" value = "0" >
                        </div>


                        <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('monthlyPRpageLang.nextdayneed') !!}</label>
                            <input class="form-control form-control-lg " type="number" id ="nextneed" name="nextneed" readonly >
                        </div>


			            <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('monthlyPRpageLang.lt') !!}</label>
                            <input class="form-control form-control-lg " type="text" id ="lt" name="lt" readonly value = "{{ Session::get('lt') }}">
                        </div>

			            <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('monthlyPRpageLang.safestock') !!}</label>
                            <input class="form-control form-control-lg " type="number" id ="safe" name="safe" readonly value="0">
                        </div>

                        <div class="mb-3 col-md-4">
                            <label class="form-label">{!! __('monthlyPRpageLang.process') !!}</label>
                            <input class="form-control form-control-lg " type="text" id ="production" name="production" readonly value = "{{ Session::get('production') }}">
                        </div>

                    </div>
                    <input type = "submit" class="btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.submit') !!}">
                </form>
                <br>
                <button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('month.standadd')}}'">{!! __('monthlyPRpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
