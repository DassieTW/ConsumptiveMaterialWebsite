@extends('layouts.adminTemplate')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')

<script src="{{ asset('js/obound/inboundnew.js') }}"></script>
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
                <h3>{!! __('oboundpageLang.inbound') !!}{!! __('oboundpageLang.new') !!}</h3>
            </div>

            <div class="card-body">
                <div class="d-flex w-100 h-100">
                    <div class="mb-3">

                        <form  id='inboundnew'>
                            @csrf
                            <table class="table" id = "test">
                                <tr>
                                    <th><input type = "hidden" id = "title0" name = "title0" value = "客戶別">{!! __('oboundpageLang.client') !!}</th>
                                    <th><input type = "hidden" id = "title1" name = "title1" value = "料號">{!! __('oboundpageLang.isn') !!}</th>
                                    <th><input type = "hidden" id = "title2" name = "title2" value = "品名">{!! __('oboundpageLang.pName') !!}</th>
                                    <th><input type = "hidden" id = "title3" name = "title3" value = "規格">{!! __('oboundpageLang.format') !!}</th>
                                    <th><input type = "hidden" id = "title4" name = "title4" value = "數量">{!! __('oboundpageLang.inboundnum') !!}</th>
                                    <th><input type = "hidden" id = "title5" name = "title5" value = "備註">{!! __('oboundpageLang.mark') !!}</th>
                                    <th><input type = "hidden" id = "title6" name = "title6" value = "入庫原因">{!! __('oboundpageLang.inreason') !!}</th>
                                    <th><input type = "hidden" id = "title7" name = "title7" value = "庫別">{!! __('oboundpageLang.bound') !!}</th>
                                </tr>
                                <tr>
                                    <?php
                                        $positions = DB::table('O庫')->pluck('O庫');
                                        $names =  App\Models\人員信息::all();
                                    ?>
                                    <td><input type = "hidden" id = "client" name = "client" value = "{{ Session::get('client') }}">{{ Session::get('client') }}</td>
                                    <td><input type = "hidden" id = "number" name = "number" value = "{{ Session::get('number') }}">{{ Session::get('number') }}</td>
                                    <td><input type = "hidden" id = "name" name = "name" value = "{{ Session::get('name') }}">{{ Session::get('name') }}</td>
                                    <td><input type = "hidden" id = "format" name = "format" value = "{{ Session::get('format') }}">{{ Session::get('format') }}</td>
                                    <td><input type = "number"  id = "amount" name = "amount" value = "" required></td>
                                    <td><input type = "text"  id = "remark" name = "remark" value = "" ></td>
                                    <td><input type = "hidden" id = "inreason" name = "inreason" value = "{{ Session::get('inreason') }}">{{ Session::get('inreason') }}</td>
                                    <td>
                                    <select class="form-control form-control-lg " id = "bound" name="bound" required>
                                        <option style="display: none" disabled selected value = "">{!! __('oboundpageLang.enterbound') !!}</option>
                                        @foreach($positions as $position)
                                        <option>{{  $position }}</option>
                                        @endforeach
                                    </select>
                                    </td>
                                </tr>

                            </table>

                            <label class="form-label">{!! __('oboundpageLang.inpeople') !!}</label>
                            <select class="form-control form-control-lg" id = "inpeople" name="inpeople" required width="250" style="width: 250px">
                            <option style="display: none" disabled selected value = "">{!! __('oboundpageLang.enterinpeople') !!}</option>
                            @foreach($names as $name)
                            <option>{{  $name->工號 .'  '. $name->姓名 }}</option>
                            @endforeach
                            </select>
                            <br>
                            <div id = "amounterror">{!! __('oboundpageLang.amountzero') !!}</div>
                            <input type = "submit" class="btn btn-lg btn-primary" value="{!! __('oboundpageLang.addtodatabase') !!}">
                        </form>
                    </div>
                </div>
                <button class="btn btn-lg btn-primary" onclick="location.href='{{route('obound.inbound')}}'">{!! __('oboundpageLang.return') !!}</button>
            </div>
        </div>
</html>
@endsection
