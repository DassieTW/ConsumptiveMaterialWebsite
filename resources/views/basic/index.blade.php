@extends('layouts.adminTemplate')

@section('content')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/basic/basic.js') }}"></script>
@endsection
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
    </head>
    <body>
        <form action="{{ route('basic.changeordelete') }}" method="POST">
            @csrf
            <input class ="basic" type="checkbox" id="factory" name="factory" style="width:25px;height:25px;" checked>
            <label for="factory" style="font-size:35px;">{!! __('basicInfoLang.factory') !!}</label>&emsp;

            <input class ="basic"type="checkbox" id="client" name="client" style="width:25px;height:25px;">
            <label for="client" style="font-size:35px;">{!! __('basicInfoLang.client') !!}</label>&emsp;

            <input class ="basic"type="checkbox" id="machine" name="machine" style="width:25px;height:25px;">
            <label for="machine" style="font-size:35px;">{!! __('basicInfoLang.machine') !!}</label>&emsp;

            <input class ="basic"type="checkbox" id="production" name="production" style="width:25px;height:25px;">
            <label for="production" style="font-size:35px;">{!! __('basicInfoLang.process') !!}</label>&emsp;

            <input class ="basic" type="checkbox" id="line" name="line" style="width:25px;height:25px;">
            <label for="line" style="font-size:35px;">{!! __('basicInfoLang.line') !!}</label>&emsp;

            <input class ="basic" type="checkbox" id="use" name="use" style="width:25px;height:25px;">
            <label for="use" style="font-size:35px;">{!! __('basicInfoLang.usedep') !!}</label>&emsp;

            <input class ="basic" type="checkbox" id="usereason" name="usereason" style="width:25px;height:25px;" >
            <label for="usereason" style="font-size:35px;">{!! __('basicInfoLang.usereason') !!}</label>&emsp;

            <input class ="basic" type="checkbox" id="inreason" name="inreason" style="width:25px;height:25px;" >
            <label for="inreason" style="font-size:35px;">{!! __('basicInfoLang.inreason') !!}</label>&emsp;

            <input class ="basic" type="checkbox" id="position" name="position" style="width:25px;height:25px;" >
            <label for="position" style="font-size:35px;">{!! __('basicInfoLang.loc') !!}</label>&emsp;

            <input class ="basic" type="checkbox" id="send" name="send" style="width:25px;height:25px;" >
            <label for="send" style="font-size:35px;">{!! __('basicInfoLang.senddep') !!}</label>&emsp;

            <input class ="basic" type="checkbox" id="o" name="o" style="width:25px;height:25px;">
            <label for="o" style="font-size:35px;">{!! __('basicInfoLang.obound') !!}</label>&emsp;

            <input class ="basic" type="checkbox" id="back" name="back" style="width:25px;height:25px;">
            <label for="back" style="font-size:35px;">{!! __('basicInfoLang.returnreason') !!}</label>&emsp;


            <div class="factory">
                <label for="factory" style="font-size:20px;">{!! __('basicInfoLang.factory') !!}</label><br>
                @foreach($factorys as $factory)
                <input class ="factory" type="checkbox" id="factorycheck{{ $loop->index }}" name="factorycheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="factory" type="text" id="factory{{ $loop->index }}" name="factory{{ $loop->index }}" value = "{{ $factory->廠別 }}" >
                <input type =  "hidden" value="{{$loop->count}}" name = "factorycount">
                <hr/>
                @endforeach
                {!! __('basicInfoLang.new') !!} <input class ="factory" type="text" id="factorynew" name="factorynew" value = "" >
                <hr/>

            </div>

            <div class="client">
                <label for="client" style="font-size:20px;">{!! __('basicInfoLang.client') !!}</label><br>
                @foreach($clients as $client)
                <input class ="client" type="checkbox" id="clientcheck{{ $loop->index }}" name="clientcheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="client" type="text" id="client{{ $loop->index }}" name="client{{ $loop->index }}" value = "{{ $client->客戶 }}" >
                <input type =  "hidden" value="{{$loop->count}}" name = "clientcount">
                <hr/>
                @endforeach
                {!! __('basicInfoLang.new') !!} <input class ="client" type="text" id="clientnew" name="clientnew" value = "" >
                <hr/>
            </div>

            <div class="machine">
                <label for="machine" style="font-size:20px;">{!! __('basicInfoLang.machine') !!}</label><br>
                @foreach($machines as $machine)
                <input class ="machine" type="checkbox" id="machinecheck{{ $loop->index }}" name="machinecheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="machine" type="text" id="machine{{ $loop->index }}" name="machine{{ $loop->index }}" value = "{{ $machine->機種 }}" >
                <input type =  "hidden" value="{{$loop->count}}" name = "machinecount">
                <hr/>
                @endforeach
                {!! __('basicInfoLang.new') !!} <input class ="machine" type="text" id="machinenew" name="machinenew" value = "" >
                <hr/>
            </div>

            <div class="production">
                <label for="production" style="font-size:20px;">{!! __('basicInfoLang.process') !!}</label><br>
                @foreach($productions as $production)
                <input class ="production" type="checkbox" id="productioncheck{{ $loop->index }}" name="productioncheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="production" type="text" id="production{{ $loop->index }}" name="production{{ $loop->index }}" value = "{{ $production->製程 }}" >
                <input type =  "hidden" value="{{$loop->count}}" name = "productioncount">
                <hr/>
                @endforeach
                {!! __('basicInfoLang.new') !!} <input class ="production" type="text" id="productionnew" name="productionnew" value = "" >
                <hr/>
            </div>

            <div class="line">
                <label for="line" style="font-size:20px;">{!! __('basicInfoLang.line') !!}</label><br>
                @foreach($lines as $line)
                <input class ="line" type="checkbox" id="linecheck{{ $loop->index }}" name="linecheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="line" type="text" id="line{{ $loop->index }}" name="line{{ $loop->index }}" value = "{{ $line->線別 }}" >
                <input type =  "hidden" value="{{$loop->count}}" name = "linecount">
                <hr/>
                @endforeach
                {!! __('basicInfoLang.new') !!} <input class ="line" type="text" id="linenew" name="linewnew" value = "" >
                <hr/>
            </div>

            <div class="use">
                <label for="use" style="font-size:20px;">{!! __('basicInfoLang.usedep') !!}</label><br>
                @foreach($uses as $use)
                <input class ="use" type="checkbox" id="usecheck{{ $loop->index }}" name="usecheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="use" type="text" id="use{{ $loop->index }}" name="use{{ $loop->index }}" value = "{{ $use->領用部門 }}" >
                <input type =  "hidden" value="{{$loop->count}}" name = "usecount">
                <hr/>
                @endforeach
                {!! __('basicInfoLang.new') !!} <input class ="use" type="text" id="usenew" name="usenew" value = "" >
                <hr/>
            </div>

            <div class="usereason">
                <label for="usereason" style="font-size:20px;">{!! __('basicInfoLang.usereason') !!}</label><br>
                @foreach($usereasons as $usereason)
                <input class ="usereason" type="checkbox" id="usereasoncheck{{ $loop->index }}" name="usereasoncheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="usereason" type="text" id="usereason{{ $loop->index }}" name="usereason{{ $loop->index }}" value = "{{ $usereason->領用原因 }}" >
                <input type =  "hidden" value="{{$loop->count}}" name = "usereasoncount">
                <hr/>
                @endforeach
                {!! __('basicInfoLang.new') !!} <input class ="usereason" type="text" id="usereasonnew" name="usereasonnew" value = "" >
                <hr/>
            </div>

            <div class="inreason">
                <label for="inreason" style="font-size:20px;">{!! __('basicInfoLang.inreason') !!}</label><br>
                @foreach($inreasons as $inreason)
                <input class ="inreason" type="checkbox" id="inreasoncheck{{ $loop->index }}" name="inreasoncheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="inreason" type="text" id="inreason{{ $loop->index }}" name="inreason{{ $loop->index }}" value = "{{ $inreason->入庫原因 }}" >
                <input type =  "hidden" value="{{$loop->count}}" name = "inreasoncount">
                <hr/>
                @endforeach
                {!! __('basicInfoLang.new') !!} <input class ="inreason" type="text" id="inreasonnew" name="inreasonnew" value = "" >
                <hr/>
            </div>

            <div class="position">
                <label for="position" style="font-size:20px;">{!! __('basicInfoLang.loc') !!}</label><br>
                @foreach($positions as $position)
                <input class ="position" type="checkbox" id="positioncheck{{ $loop->index }}" name="positioncheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="position" type="text" id="position{{ $loop->index }}" name="position{{ $loop->index }}" value = "{{ $position->儲存位置 }}" >
                <input type =  "hidden" value="{{$loop->count}}" name = "positioncount">
                <hr/>
                @endforeach
                {!! __('basicInfoLang.new') !!} <input class ="position" type="text" id="positionnew" name="positionnew" value = "" >
                <hr/>
            </div>

            <div class="send">
                <label for="send" style="font-size:20px;">{!! __('basicInfoLang.senddep') !!}</label><br>
                @foreach($sends as $send)
                <input class ="send" type="checkbox" id="sendcheck{{ $loop->index }}" name="sendcheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="send" type="text" id="send{{ $loop->index }}" name="send{{ $loop->index }}" value = "{{ $send->發料部門 }}" >
                <input type =  "hidden" value="{{$loop->count}}" name = "sendcount">
                <hr/>
                @endforeach
                {!! __('basicInfoLang.new') !!} <input class ="send" type="text" id="sendnew" name="sendnew" value = "" >
                <hr/>
            </div>

            <div class="o">
                <label for="o" style="font-size:20px;">{!! __('basicInfoLang.obound') !!}</label><br>
                @foreach($os as $o)
                <input class ="o" type="checkbox" id="ocheck{{ $loop->index }}" name="ocheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="o" type="text" id="o{{ $loop->index }}" name="o{{ $loop->index }}" value = "{{ $o->O庫 }}" >
                <input type =  "hidden" value="{{$loop->count}}" name = "ocount">
                <hr/>
                @endforeach
                {!! __('basicInfoLang.new') !!} :  <input class ="0" type="text" id="onew" name="onew" value = "" >
                <hr/>
            </div>

            <div class="back">
                <label for="back" style="font-size:20px;">{!! __('basicInfoLang.returnreason') !!}</label><br>
                @foreach($backs as $back)
                <input class ="back" type="checkbox" id="backcheck{{ $loop->index }}" name="backcheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="back" type="text" id="back{{ $loop->index }}" name="back{{ $loop->index }}" value = "{{ $back->退回原因 }}" >
                <input type =  "hidden" value="{{$loop->count}}" name = "backcount">
                <hr/>
                @endforeach
                {!! __('basicInfoLang.new') !!} : <input class ="back" type="text" id="backnew" name="backnew" value = "" >
                <hr/>
            </div>

            <div class="">
                <input type = "submit" id = "change" name = "change" class="btn btn-lg btn-primary" value="{!! __('basicInfoLang.change') !!}">
                &emsp;
                <input type = "submit" id = "delete" name = "delete" class="btn btn-lg btn-primary" value="{!! __('basicInfoLang.delete') !!}">
                &emsp;

                <a class="btn btn-lg btn-primary" id = "download" href = "#" download>{!! __('basicInfoLang.exampleExcel') !!}</a>
                <!-- <button type="submit" class="btn btn-lg btn-primary">Sign in</button> -->
            </div>
        </form>
        <br>
        @if(count($errors) > 0)
        <div class="alert alert-danger">
            Upload Validation Error<br><br>
            <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
        @endif

        <form method="post" enctype="multipart/form-data" action = "{{ route('basic.uploadbasic') }}">
            @csrf
            <div class="col-6 col-sm-3">
                <label>{!! __('basicInfoLang.plz_upload') !!}</label>
                <input  class="form-control @error('select_file') is-invalid @enderror"  type="file" name="select_file" />
                @error('select_file')
                    <span class="invalid-feedback d-block" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <br>
                <input type="submit" name="upload" class="btn btn-lg btn-primary" value="{!! __('basicInfoLang.upload') !!}">
            </div>
        </form>
        <br>
        <button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('basic.new')}}'">{!! __('basicInfoLang.newMats') !!}</button>
        &emsp;
        <button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('basic.material')}}'">{!! __('basicInfoLang.matsInfo') !!}</button>

    </body>
</html>
@endsection
