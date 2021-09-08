@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/month/consumeadd.js') }}"></script>
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
                <h3>{!! __('monthlyPRpageLang.isnConsumeAdd') !!}</h3>
            </div>
            <div class="card-body">
                <form id = "consumeadd" >
                    @csrf
                    <div class="d-flex w-100 h-100">
                        <div class="mb-3">
                            <label class="form-label">{!! __('monthlyPRpageLang.client') !!}</label>
                            <select class="form-control form-control-lg" id = "client" name="client" required>
                            <option style="display: none" disabled selected value = "">{!! __('monthlyPRpageLang.enterclient') !!}</option>
                            @foreach($client as $client)
                            <option>{{  $client->客戶 }}</option>
                            @endforeach
                            </select>

                            <label class="form-label">{!! __('monthlyPRpageLang.machine') !!}</label>
                            <select class="form-control form-control-lg" id = "machine" name="machine" required>
                            <option style="display: none" disabled selected value = "">{!! __('monthlyPRpageLang.entermachine') !!}</option>
                            @foreach($machine as $machine)
                            <option>{{  $machine->機種 }}</option>
                            @endforeach
                            </select>

                            <label class="form-label">{!! __('monthlyPRpageLang.process') !!}</label>
                            <select class="form-control form-control-lg " id = "production" name="production" required>
                            <option style="display: none" disabled selected value = "">{!! __('monthlyPRpageLang.enterprocess') !!}</option>
                            @foreach($production as $production)
                            <option>{{  $production->製程 }}</option>
                            @endforeach
                            </select>

                            <label class="form-label">{!! __('monthlyPRpageLang.isn') !!}</label>
                            <input class="form-control form-control-lg " type="text" id ="number" name="number" required>
                            <div id = "numbererror" style="display:none; color:red;">{!! __('monthlyPRpageLang.isnlength') !!}</div>
                            <div id = "numbererror1" style="display:none; color:red;">{!! __('monthlyPRpageLang.noisn') !!}</div>


                        </div>
                    </div>
                    <input type = "submit" id = "submit" name = "submit" class="btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.add') !!}">
                </form>
                <br>
                <a class="btn btn-lg btn-primary" href="{{asset('download/ConsumeExample.xlsx')}}" download>{!! __('monthlyPRpageLang.exampleExcel') !!}</a>
                <br>

                <form method="post" enctype="multipart/form-data" action = "{{ route('month.uploadconsume') }}">
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
