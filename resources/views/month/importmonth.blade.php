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
                <h3>{!! __('monthlyPRpageLang.importMonthlyData') !!}</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('month.monthsearchoradd') }}" method="POST" >
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

                            <label class="form-label">{!! __('monthlyPRpageLang.machine') !!}</label>
                            <select class="form-control form-control-lg @error('machine') is-invalid @enderror" id = "machine" name="machine">
                            <option style="display: none" disabled selected>{!! __('monthlyPRpageLang.entermachine') !!}</option>
                            @foreach($machine as $machine)
                            <option>{{  $machine->機種 }}</option>
                            @endforeach
                            </select>
                            @error('machine')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror

                            <label class="form-label">{!! __('monthlyPRpageLang.nowmps') !!}</label>
                            <input class="form-control form-control-lg" type="number" id ="nowmps" name="nowmps" value="0">
                            <label class="form-label">{!! __('monthlyPRpageLang.nowday') !!}</label>
                            <input class="form-control form-control-lg" type="number" id ="nowday" name="nowday" value="0">
                            <label class="form-label">{!! __('monthlyPRpageLang.nextmps') !!}</label>
                            <input class="form-control form-control-lg" type="number" id ="nextmps" name="nextmps" value="0">
                            <label class="form-label">{!! __('monthlyPRpageLang.nextday') !!}</label>
                            <input class="form-control form-control-lg" type="number" id ="nextday" name="nextday" value="0">
                        </div>
                        &emsp;
                        <div class="mb-3">
                            <label class="form-label">{!! __('monthlyPRpageLang.process') !!}</label>
                            <table class="table table-borderless">
                                @foreach($production as $production)
                                    <tr>
                                        <td><input class ="innumber" type="checkbox" id="innumber{{$loop->index}}" name="innumber{{$loop->index}}" style="width:20px;height:20px;" ></td>
                                        <td><h4>{{$production->製程}}</td>
                                    </tr>
                                    <input type = "hidden" id="count" name = "count" value="{{$loop->count}}">
                                @endforeach
                                @error('production')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </table>
                        </div>
                    </div>
                    <input type = "submit" id = "search" name = "search" class="btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.search') !!}">
                    <input type = "submit" id = "add" name ="add" class="btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.add') !!}">
                </form>
                <br>
                <a class="btn btn-lg btn-primary" href="{{asset('download/ImportMonthExample.xlsx')}}" download>{!! __('monthlyPRpageLang.exampleExcel') !!}</a>
                <br><br>


                <form method="post" enctype="multipart/form-data" action = "{{ route('month.uploadmonth') }}">
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
