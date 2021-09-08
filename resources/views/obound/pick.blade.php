@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')

<script src="{{ asset('js/obound/pick.js') }}"></script>
<!-- <script src="{{ asset('js/popupNotice.js') }}"></script> -->
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
                <h3>{!! __('oboundpageLang.pick') !!}</h3>
            </div>
            <div class="card-body">
                <form id = "pick" >
                    @csrf
                    <div class="d-flex w-100 h-100">
                        <div class="mb-3">
                            <label class="form-label">{!! __('oboundpageLang.client') !!}</label>
                            <select class="form-control form-control-lg" id = "client" name="client" required>
                            <option style="display: none" disabled selected value = "">{!! __('oboundpageLang.enterclient') !!}</option>
                            @foreach($client as $client)
                            <option>{{  $client->客戶 }}</option>
                            @endforeach
                            </select>

                            <label class="form-label">{!! __('oboundpageLang.machine') !!}</label>
                            <select class="form-control form-control-lg" id = "machine" name="machine" required>
                            <option style="display: none" disabled selected value = "">{!! __('oboundpageLang.entermachine') !!}</option>
                            @foreach($machine as $machine)
                            <option>{{  $machine->機種 }}</option>
                            @endforeach
                            </select>

                            <label class="form-label">{!! __('oboundpageLang.process') !!}</label>
                            <select class="form-control form-control-lg " id = "production" name="production" required>
                            <option style="display: none" disabled selected value = "">{!! __('oboundpageLang.enterprocess') !!}</option>
                            @foreach($production as $production)
                            <option>{{  $production->製程 }}</option>
                            @endforeach
                            </select>

                            <label class="form-label">{!! __('oboundpageLang.line') !!}</label>
                            <select class="form-control form-control-lg " id = "line" name="line" required>
                            <option style="display: none" disabled selected value = "">{!! __('oboundpageLang.enterline') !!}</option>
                            @foreach($line as $line)
                            <option>{{  $line->線別 }}</option>
                            @endforeach
                            </select>

                            <label class="form-label">{!! __('oboundpageLang.usereason') !!}</label>
                            <select class="form-control form-control-lg " id = "usereason" name="usereason" required>
                            <option style="display: none" disabled selected value = "">{!! __('oboundpageLang.enterusereason') !!}</option>
                            @foreach($usereason as $usereason)
                            <option>{{  $usereason->領用原因 }}</option>
                            @endforeach
                            <option>其他</option>
                            </select>
                            <br>
                            <input class="form-control form-control-lg " style="display:none;" type="text" id ="reason" name="reason" placeholder="{!! __('oboundpageLang.inputusereason') !!}">


                            <label class="form-label">{!! __('oboundpageLang.isn') !!}</label>
                            <input class="form-control form-control-lg " type="text" id ="number" name="number" required>
                            <div id = "numbererror1" style="display:none; color:red;">{!! __('oboundpageLang.noisn') !!}</div>
                            <div id = "nostock" style="display:none; color:red;">{!! __('oboundpageLang.nostock') !!}</div>
                        </div>
                    </div>
                    <input type = "submit" id = "submit" name = "submit" class="btn btn-lg btn-primary" value="{!! __('oboundpageLang.submit') !!}">
                </form>
                <br>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.index')}}'">{!! __('oboundpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
