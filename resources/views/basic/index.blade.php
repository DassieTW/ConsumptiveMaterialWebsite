@extends('layouts.adminTemplate')

@section('content')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
<<<<<<< HEAD
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
@endsection

@section('js')
<script src="{{ asset('js/basic.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('/admin/js/app.js') }}"></script>
<!-- <script src="{{ asset('js/popupNotice.js') }}"></script> -->
<!--for notifications pop up -->
=======
@endsection

@section('js')
<!--for this page's sepcified js -->
<script src="{{ asset('js/basic/basic.js') }}"></script>
>>>>>>> 0827tony
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
<<<<<<< HEAD
            <label for="factory" style="font-size:35px;">廠別</label>&nbsp

            <input class ="basic"type="checkbox" id="client" name="client" style="width:25px;height:25px;">
            <label for="client" style="font-size:35px;">客戶別</label>&nbsp

            <input class ="basic"type="checkbox" id="machine" name="machine" style="width:25px;height:25px;" >
            <label for="machine" style="font-size:35px;">機種</label>&nbsp

            <input class ="basic"type="checkbox" id="production" name="production" style="width:25px;height:25px;" >
            <label for="production" style="font-size:35px;">製程</label>&nbsp

            <input class ="basic" type="checkbox" id="line" name="line" style="width:25px;height:25px;" >
            <label for="line" style="font-size:35px;">線別</label>&nbsp

            <input class ="basic" type="checkbox" id="use" name="use" style="width:25px;height:25px;" >
            <label for="use" style="font-size:35px;">領用部門</label>&nbsp

            <input class ="basic" type="checkbox" id="usereason" name="usereason" style="width:25px;height:25px;" >
            <label for="usereason" style="font-size:35px;">領用原因</label>&nbsp

            <input class ="basic" type="checkbox" id="inreason" name="inreason" style="width:25px;height:25px;" >
            <label for="inreason" style="font-size:35px;">入庫原因</label>&nbsp

            <input class ="basic" type="checkbox" id="position" name="position" style="width:25px;height:25px;" >
            <label for="position" style="font-size:35px;">儲位</label>&nbsp

            <input class ="basic" type="checkbox" id="send" name="send" style="width:25px;height:25px;" >
            <label for="send" style="font-size:35px;">發料部門</label>&nbsp


            <div class="factory">
                <label for="factory" style="font-size:20px;">廠別</label><br>
=======
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
>>>>>>> 0827tony
                @foreach($factorys as $factory)
                <input class ="factory" type="checkbox" id="factorycheck{{ $loop->index }}" name="factorycheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="factory" type="text" id="factory{{ $loop->index }}" name="factory{{ $loop->index }}" value = "{{ $factory->廠別 }}" >
                <hr/>
                @endforeach
<<<<<<< HEAD
            </div>

            <div class="client">
                <label for="client" style="font-size:20px;">客戶</label><br>
=======
                {!! __('basicInfoLang.new') !!} <input class ="factory" type="text" id="factorynew" name="factorynew" value = "" >
                <hr/>

            </div>

            <div class="client">
                <label for="client" style="font-size:20px;">{!! __('basicInfoLang.client') !!}</label><br>
>>>>>>> 0827tony
                @foreach($clients as $client)
                <input class ="client" type="checkbox" id="clientcheck{{ $loop->index }}" name="clientcheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="client" type="text" id="client{{ $loop->index }}" name="client{{ $loop->index }}" value = "{{ $client->客戶 }}" >
                <hr/>
                @endforeach
<<<<<<< HEAD
            </div>

            <div class="machine">
                <label for="machine" style="font-size:20px;">機種</label><br>
=======
                {!! __('basicInfoLang.new') !!} <input class ="client" type="text" id="clientnew" name="clientnew" value = "" >
                <hr/>
            </div>

            <div class="machine">
                <label for="machine" style="font-size:20px;">{!! __('basicInfoLang.machine') !!}</label><br>
>>>>>>> 0827tony
                @foreach($machines as $machine)
                <input class ="machine" type="checkbox" id="machinecheck{{ $loop->index }}" name="machinecheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="machine" type="text" id="machine{{ $loop->index }}" name="machine{{ $loop->index }}" value = "{{ $machine->機種 }}" >
                <hr/>
                @endforeach
<<<<<<< HEAD
            </div>

            <div class="production">
                <label for="production" style="font-size:20px;">製程</label><br>
=======
                {!! __('basicInfoLang.new') !!} <input class ="machine" type="text" id="machinenew" name="machinenew" value = "" >
                <hr/>
            </div>

            <div class="production">
                <label for="production" style="font-size:20px;">{!! __('basicInfoLang.process') !!}</label><br>
>>>>>>> 0827tony
                @foreach($productions as $production)
                <input class ="production" type="checkbox" id="productioncheck{{ $loop->index }}" name="productioncheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="production" type="text" id="production{{ $loop->index }}" name="production{{ $loop->index }}" value = "{{ $production->製程 }}" >
                <hr/>
                @endforeach
<<<<<<< HEAD
            </div>

            <div class="line">
                <label for="line" style="font-size:20px;">線別</label><br>
=======
                {!! __('basicInfoLang.new') !!} <input class ="production" type="text" id="productionnew" name="productionnew" value = "" >
                <hr/>
            </div>

            <div class="line">
                <label for="line" style="font-size:20px;">{!! __('basicInfoLang.line') !!}</label><br>
>>>>>>> 0827tony
                @foreach($lines as $line)
                <input class ="line" type="checkbox" id="linecheck{{ $loop->index }}" name="linecheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="line" type="text" id="line{{ $loop->index }}" name="line{{ $loop->index }}" value = "{{ $line->線別 }}" >
                <hr/>
                @endforeach
<<<<<<< HEAD
            </div>

            <div class="use">
                <label for="use" style="font-size:20px;">領用部門</label><br>
=======
                {!! __('basicInfoLang.new') !!} <input class ="line" type="text" id="linenew" name="linewnew" value = "" >
                <hr/>
            </div>

            <div class="use">
                <label for="use" style="font-size:20px;">{!! __('basicInfoLang.usedep') !!}</label><br>
>>>>>>> 0827tony
                @foreach($uses as $use)
                <input class ="use" type="checkbox" id="usecheck{{ $loop->index }}" name="usecheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="use" type="text" id="use{{ $loop->index }}" name="use{{ $loop->index }}" value = "{{ $use->領用部門 }}" >
                <hr/>
                @endforeach
<<<<<<< HEAD
            </div>

            <div class="usereason">
                <label for="usereason" style="font-size:20px;">領用原因</label><br>
=======
                {!! __('basicInfoLang.new') !!} <input class ="use" type="text" id="usenew" name="usenew" value = "" >
                <hr/>
            </div>

            <div class="usereason">
                <label for="usereason" style="font-size:20px;">{!! __('basicInfoLang.usereason') !!}</label><br>
>>>>>>> 0827tony
                @foreach($usereasons as $usereason)
                <input class ="usereason" type="checkbox" id="usereasoncheck{{ $loop->index }}" name="usereasoncheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="usereason" type="text" id="usereason{{ $loop->index }}" name="usereason{{ $loop->index }}" value = "{{ $usereason->領用原因 }}" >
                <hr/>
                @endforeach
<<<<<<< HEAD
            </div>

            <div class="inreason">
                <label for="inreason" style="font-size:20px;">入庫原因</label><br>
=======
                {!! __('basicInfoLang.new') !!} <input class ="usereason" type="text" id="usereasonnew" name="usereasonnew" value = "" >
                <hr/>
            </div>

            <div class="inreason">
                <label for="inreason" style="font-size:20px;">{!! __('basicInfoLang.inreason') !!}</label><br>
>>>>>>> 0827tony
                @foreach($inreasons as $inreason)
                <input class ="inreason" type="checkbox" id="inreasoncheck{{ $loop->index }}" name="inreasoncheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="inreason" type="text" id="inreason{{ $loop->index }}" name="inreason{{ $loop->index }}" value = "{{ $inreason->入庫原因 }}" >
                <hr/>
                @endforeach
<<<<<<< HEAD
            </div>

            <div class="position">
                <label for="position" style="font-size:20px;">儲位</label><br>
=======
                {!! __('basicInfoLang.new') !!} <input class ="inreason" type="text" id="inreasonnew" name="inreasonnew" value = "" >
                <hr/>
            </div>

            <div class="position">
                <label for="position" style="font-size:20px;">{!! __('basicInfoLang.loc') !!}</label><br>
>>>>>>> 0827tony
                @foreach($positions as $position)
                <input class ="position" type="checkbox" id="positioncheck{{ $loop->index }}" name="positioncheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="position" type="text" id="position{{ $loop->index }}" name="position{{ $loop->index }}" value = "{{ $position->儲存位置 }}" >
                <hr/>
                @endforeach
<<<<<<< HEAD
            </div>

            <div class="send">
                <label for="send" style="font-size:20px;">發料部門</label><br>
=======
                {!! __('basicInfoLang.new') !!} <input class ="position" type="text" id="positionnew" name="positionnew" value = "" >
                <hr/>
            </div>

            <div class="send">
                <label for="send" style="font-size:20px;">{!! __('basicInfoLang.senddep') !!}</label><br>
>>>>>>> 0827tony
                @foreach($sends as $send)
                <input class ="send" type="checkbox" id="sendcheck{{ $loop->index }}" name="sendcheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="send" type="text" id="send{{ $loop->index }}" name="send{{ $loop->index }}" value = "{{ $send->發料部門 }}" >
                <hr/>
                @endforeach
<<<<<<< HEAD
            </div>

            <div class="">
                <input type = "submit" id = "change" name = "change" class="btn btn-lg btn-primary" value="change">
                <input type = "submit" id = "delete" name = "delete" class="btn btn-lg btn-primary" value="delete">
=======
                {!! __('basicInfoLang.new') !!} <input class ="send" type="text" id="sendnew" name="sendnew" value = "" >
                <hr/>
            </div>

            <div class="o">
                <label for="o" style="font-size:20px;">{!! __('basicInfoLang.obound') !!}</label><br>
                @foreach($os as $o)
                <input class ="o" type="checkbox" id="ocheck{{ $loop->index }}" name="ocheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="o" type="text" id="o{{ $loop->index }}" name="o{{ $loop->index }}" value = "{{ $o->O庫 }}" >
                <hr/>
                @endforeach
                {!! __('basicInfoLang.new') !!} <input class ="0" type="text" id="onew" name="onew" value = "" >
                <hr/>
            </div>

            <div class="back">
                <label for="back" style="font-size:20px;">{!! __('basicInfoLang.returnreason') !!}</label><br>
                @foreach($backs as $back)
                <input class ="back" type="checkbox" id="backcheck{{ $loop->index }}" name="backcheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="back" type="text" id="back{{ $loop->index }}" name="back{{ $loop->index }}" value = "{{ $back->退回原因 }}" >
                <hr/>
                @endforeach
                {!! __('basicInfoLang.new') !!} <input class ="back" type="text" id="backnew" name="backnew" value = "" >
                <hr/>
            </div>

            <div class="">
                <input type = "submit" id = "change" name = "change" class="btn btn-lg btn-primary" value="{!! __('basicInfoLang.change') !!}">
                &emsp;
                <input type = "submit" id = "delete" name = "delete" class="btn btn-lg btn-primary" value="{!! __('basicInfoLang.delete') !!}">
                &emsp;

                <a class="btn btn-lg btn-primary" id = "download" href = "#" download>{!! __('basicInfoLang.exampleExcel') !!}</a>
>>>>>>> 0827tony
                <!-- <button type="submit" class="btn btn-lg btn-primary">Sign in</button> -->
            </div>
        </form>
        <br>
<<<<<<< HEAD
        <button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('basic.searchmaterial')}}'">料號條碼查詢</button>
        <br><br>
        <button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('basic.searchposition')}}'">儲位條碼查詢</button>
        <br><br>
        <button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('basic.new')}}'">新增料件</button>
        <br><br>
        <button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('member.index')}}'">Home</button>
=======
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

        @if($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
                <strong>{{ $message }}</strong>
        </div>
        @endif
        <form method="post" enctype="multipart/form-data" action = "{{ route('basic.uploadbasic') }}">
            @csrf
            <div class="col-6 col-sm-3">
                <label>{!! __('basicInfoLang.plz_upload') !!}</label>
                <input  class="form-control"  type="file" name="select_file" />
                <br>
                <input type="submit" name="upload" class="btn btn-lg btn-primary" value="{!! __('basicInfoLang.upload') !!}">
            </div>
        </form>
        <br>
        <button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('basic.new')}}'">{!! __('basicInfoLang.newMats') !!}</button>
        &emsp;
        <button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('basic.material')}}'">{!! __('basicInfoLang.matsInfo') !!}</button>

>>>>>>> 0827tony
    </body>
</html>
@endsection
