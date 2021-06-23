@extends('layouts.adminTemplate')

@section('content')
@section('css')
<link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js"></script>
@endsection

@section('js')
<script src="{{ asset('js/basic.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('/admin/js/app.js') }}"></script>
<!-- <script src="{{ asset('js/popupNotice.js') }}"></script> -->
<!--for notifications pop up -->
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
                @foreach($factorys as $factory)
                <input class ="factory" type="checkbox" id="factorycheck{{ $loop->index }}" name="factorycheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="factory" type="text" id="factory{{ $loop->index }}" name="factory{{ $loop->index }}" value = "{{ $factory->廠別 }}" >
                <hr/>
                @endforeach
            </div>

            <div class="client">
                <label for="client" style="font-size:20px;">客戶</label><br>
                @foreach($clients as $client)
                <input class ="client" type="checkbox" id="clientcheck{{ $loop->index }}" name="clientcheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="client" type="text" id="client{{ $loop->index }}" name="client{{ $loop->index }}" value = "{{ $client->客戶 }}" >
                <hr/>
                @endforeach
            </div>

            <div class="machine">
                <label for="machine" style="font-size:20px;">機種</label><br>
                @foreach($machines as $machine)
                <input class ="machine" type="checkbox" id="machinecheck{{ $loop->index }}" name="machinecheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="machine" type="text" id="machine{{ $loop->index }}" name="machine{{ $loop->index }}" value = "{{ $machine->機種 }}" >
                <hr/>
                @endforeach
            </div>

            <div class="production">
                <label for="production" style="font-size:20px;">製程</label><br>
                @foreach($productions as $production)
                <input class ="production" type="checkbox" id="productioncheck{{ $loop->index }}" name="productioncheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="production" type="text" id="production{{ $loop->index }}" name="production{{ $loop->index }}" value = "{{ $production->製程 }}" >
                <hr/>
                @endforeach
            </div>

            <div class="line">
                <label for="line" style="font-size:20px;">線別</label><br>
                @foreach($lines as $line)
                <input class ="line" type="checkbox" id="linecheck{{ $loop->index }}" name="linecheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="line" type="text" id="line{{ $loop->index }}" name="line{{ $loop->index }}" value = "{{ $line->線別 }}" >
                <hr/>
                @endforeach
            </div>

            <div class="use">
                <label for="use" style="font-size:20px;">領用部門</label><br>
                @foreach($uses as $use)
                <input class ="use" type="checkbox" id="usecheck{{ $loop->index }}" name="usecheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="use" type="text" id="use{{ $loop->index }}" name="use{{ $loop->index }}" value = "{{ $use->領用部門 }}" >
                <hr/>
                @endforeach
            </div>

            <div class="usereason">
                <label for="usereason" style="font-size:20px;">領用原因</label><br>
                @foreach($usereasons as $usereason)
                <input class ="usereason" type="checkbox" id="usereasoncheck{{ $loop->index }}" name="usereasoncheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="usereason" type="text" id="usereason{{ $loop->index }}" name="usereason{{ $loop->index }}" value = "{{ $usereason->領用原因 }}" >
                <hr/>
                @endforeach
            </div>

            <div class="inreason">
                <label for="inreason" style="font-size:20px;">入庫原因</label><br>
                @foreach($inreasons as $inreason)
                <input class ="inreason" type="checkbox" id="inreasoncheck{{ $loop->index }}" name="inreasoncheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="inreason" type="text" id="inreason{{ $loop->index }}" name="inreason{{ $loop->index }}" value = "{{ $inreason->入庫原因 }}" >
                <hr/>
                @endforeach
            </div>

            <div class="position">
                <label for="position" style="font-size:20px;">儲位</label><br>
                @foreach($positions as $position)
                <input class ="position" type="checkbox" id="positioncheck{{ $loop->index }}" name="positioncheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="position" type="text" id="position{{ $loop->index }}" name="position{{ $loop->index }}" value = "{{ $position->儲存位置 }}" >
                <hr/>
                @endforeach
            </div>

            <div class="send">
                <label for="send" style="font-size:20px;">發料部門</label><br>
                @foreach($sends as $send)
                <input class ="send" type="checkbox" id="sendcheck{{ $loop->index }}" name="sendcheck{{ $loop->index }}" style="width:20px;height:20px;" >
                <input class ="send" type="text" id="send{{ $loop->index }}" name="send{{ $loop->index }}" value = "{{ $send->發料部門 }}" >
                <hr/>
                @endforeach
            </div>

            <div class="">
                <input type = "submit" id = "change" name = "change" class="btn btn-lg btn-primary" value="change">
                <input type = "submit" id = "delete" name = "delete" class="btn btn-lg btn-primary" value="delete">
                <!-- <button type="submit" class="btn btn-lg btn-primary">Sign in</button> -->
            </div>
        </form>
        <br>
        <button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('basic.searchmaterial')}}'">料號條碼查詢</button>
        <br><br>
        <button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('basic.searchposition')}}'">儲位條碼查詢</button>
        <br><br>
        <button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('basic.new')}}'">新增料件</button>
        <br><br>
        <button type = "submit" class="btn btn-lg btn-primary" onclick="location.href='{{route('member.index')}}'">Home</button>
    </body>
</html>
@endsection
