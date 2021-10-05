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

    <div class="row justify-content-center">
        <div class="card w-75">
            <div class="card-body">
                <form id="basicdata" method="POST">
                    @csrf
                    <ul id="myTab" name="myTab" class="nav nav-tabs justify-content-center">
                        <li class="nav-item" id="FactoryExample">
                            <a data-toggle="tab" class="nav-link active"
                                href="#showfactory">{!!__('basicInfoLang.factory') !!}</a>

                        </li>
                        <li class="nav-item" id="ClientExample">
                            <a data-toggle="tab" class="nav-link" href="#showclient">{!! __('basicInfoLang.client')
                                !!}</a>
                        </li>
                        <li class="nav-item" id="MachineExample">
                            <a data-toggle="tab" class="nav-link" href="#showmachine">{!! __('basicInfoLang.machine')
                                !!}</a>
                        </li>
                        <li class="nav-item" id="ProductionExample">
                            <a data-toggle="tab" class="nav-link" href="#showprocess">{!! __('basicInfoLang.process')
                                !!}</a>
                        </li>
                        <li class="nav-item" id="LineExample">
                            <a data-toggle="tab" class="nav-link" href="#showline">{!! __('basicInfoLang.line')
                                !!}</a>
                        </li>
                        <li class="nav-item" id="UseExample">
                            <a data-toggle="tab" class="nav-link" href="#showusedep">{!! __('basicInfoLang.usedep')
                                !!}</a>
                        </li>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                        <li class="nav-item" id="UseReasonExample">
                            <a data-toggle="tab" class="nav-link" href="#showusereason">{!!
                                __('basicInfoLang.usereason')
                                !!}</a>
                        </li>
                        <li class="nav-item" id="InReasonExample">
                            <a data-toggle="tab" class="nav-link" href="#showinreason">{!! __('basicInfoLang.inreason')
                                !!}</a>
                        </li>
                        <li class="nav-item" id="PositionExample">
                            <a data-toggle="tab" class="nav-link" href="#showloc">{!! __('basicInfoLang.loc') !!}</a>
                        </li>
                        <li class="nav-item" id="SendExample">
                            <a data-toggle="tab" class="nav-link" href="#showsenddep">{!! __('basicInfoLang.senddep')
                                !!}</a>
                        </li>
                        <li class="nav-item" id="OboundExample">
                            <a data-toggle="tab" class="nav-link" href="#showobound">{!! __('basicInfoLang.obound')
                                !!}</a>
                        </li>
                        <li class="nav-item" id="BackReasonExample">
                            <a data-toggle="tab" class="nav-link" href="#showreturnreason">{!!
                                __('basicInfoLang.returnreason') !!}</a>
                        </li>
                    </ul>
            </div>

            <div class="tab-content">
                <div class="tab-pane show active" id="showfactory">
                    <div class="row w-100 justify-content-center">
                        <div class="col col-auto">
                            @foreach($factorys as $factory)
                            <input class="factory" type="checkbox" id="factorycheck" name="factorycheck"
                                style="width:20px;height:20px;" value="{{$loop->index}}">
                            <input class="factory" type="text" id="factory{{ $loop->index }}"
                                name="factory{{ $loop->index }}" value="{{ $factory->廠別 }}">
                            <input type="hidden" id="oldfactory{{ $loop->index }}"
                            name="oldfactory{{ $loop->index }}" value="{{ $factory->廠別 }}">
                            <hr />
                            @endforeach
                            {!! __('basicInfoLang.new') !!} : <input class="factory" type="text" id="factorynew"
                                name="factorynew" value="">
                            <hr />
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="showclient">
                    <div class="row w-100 justify-content-center">
                        <div class="col col-auto">
                            @foreach($clients as $client)
                            <input class="client" type="checkbox" id="clientcheck" name="clientcheck"
                                style="width:20px;height:20px;" value="{{$loop->index}}">
                            <input class="client" type="text" id="client{{ $loop->index }}"
                                name="client{{ $loop->index }}" value="{{ $client->客戶 }}">
                                <input type="hidden" id="oldclient{{ $loop->index }}"
                                name="oldclient{{ $loop->index }}" value="{{ $client->客戶 }}">
                            <hr />
                            @endforeach
                            {!! __('basicInfoLang.new') !!} : <input class="client" type="text" id="clientnew"
                                name="clientnew" value="">
                            <hr />
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="showmachine">
                    <div class="row w-100 justify-content-center">
                        <div class="col col-auto">
                            @foreach($machines as $machine)
                            <input class="machine" type="checkbox" id="machinecheck" name="machinecheck"
                                style="width:20px;height:20px;" value="{{$loop->index}}">
                            <input class="machine" type="text" id="machine{{ $loop->index }}"
                                name="machine{{ $loop->index }}" value="{{ $machine->機種 }}">
                                <input type="hidden" id="oldmachine{{ $loop->index }}"
                                name="oldmachine{{ $loop->index }}" value="{{ $machine->機種 }}">
                            <hr />
                            @endforeach
                            {!! __('basicInfoLang.new') !!} : <input class="machine" type="text" id="machinenew"
                                name="machinenew" value="">
                            <hr />
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="showprocess">
                    <div class="row w-100 justify-content-center">
                        <div class="col col-auto">
                            @foreach($productions as $production)
                            <input class="production" type="checkbox" id="productioncheck" name="productioncheck"
                                style="width:20px;height:20px;" value="{{$loop->index}}">
                            <input class="production" type="text" id="production{{ $loop->index }}"
                                name="production{{ $loop->index }}" value="{{ $production->製程 }}">
                                <input type="hidden" id="oldproduction{{ $loop->index }}"
                                name="oldproduction{{ $loop->index }}" value="{{ $production->製程 }}">
                            <hr />
                            @endforeach
                            {!! __('basicInfoLang.new') !!} : <input class="production" type="text" id="productionnew"
                                name="productionnew" value="">
                            <hr />
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="showline">
                    <div class="row w-100 justify-content-center">
                        <div class="col col-auto">
                            @foreach($lines as $line)
                            <input class="line" type="checkbox" id="linecheck" name="linecheck"
                                style="width:20px;height:20px;" value="{{$loop->index}}">
                            <input class="line" type="text" id="line{{ $loop->index }}" name="line{{ $loop->index }}"
                                value="{{ $line->線別 }}">
                                <input type="hidden" id="oldline{{ $loop->index }}"
                                name="oldline{{ $loop->index }}" value="{{ $line->線別 }}">
                            <hr />
                            @endforeach
                            {!! __('basicInfoLang.new') !!} : <input class="line" type="text" id="linenew"
                                name="linewnew" value="">
                            <hr />
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="showusedep">
                    <div class="row w-100 justify-content-center">
                        <div class="col col-auto">
                            @foreach($uses as $use)
                            <input class="use" type="checkbox" id="usecheck" name="usecheck"
                                style="width:20px;height:20px;" value="{{$loop->index}}">
                            <input class="use" type="text" id="use{{ $loop->index }}" name="use{{ $loop->index }}"
                                value="{{ $use->領用部門 }}">
                                <input type="hidden" id="olduse{{ $loop->index }}"
                                name="olduse{{ $loop->index }}" value="{{ $use->領用部門 }}">
                            <hr />
                            @endforeach
                            {!! __('basicInfoLang.new') !!} : <input class="use" type="text" id="usenew" name="usenew"
                                value="">
                            <hr />
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="showusereason">
                    <div class="row w-100 justify-content-center">
                        <div class="col col-auto">
                            @foreach($usereasons as $usereason)
                            <input class="usereason" type="checkbox" id="usereasoncheck" name="usereasoncheck"
                                style="width:20px;height:20px;" value="{{$loop->index}}">
                            <input class="usereason" type="text" id="usereason{{ $loop->index }}"
                                name="usereason{{ $loop->index }}" value="{{ $usereason->領用原因 }}">
                                <input type="hidden" id="oldusereason{{ $loop->index }}"
                                name="oldusereason{{ $loop->index }}" value="{{ $usereason->領用原因 }}">
                            <hr />
                            @endforeach
                            {!! __('basicInfoLang.new') !!} : <input class="usereason" type="text" id="usereasonnew"
                                name="usereasonnew" value="">
                            <hr />
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="showinreason">
                    <div class="row w-100 justify-content-center">
                        <div class="col col-auto">
                            @foreach($inreasons as $inreason)
                            <input class="inreason" type="checkbox" id="inreasoncheck" name="inreasoncheck"
                                style="width:20px;height:20px;" value="{{$loop->index}}">
                            <input class="inreason" type="text" id="inreason{{ $loop->index }}"
                                name="inreason{{ $loop->index }}" value="{{ $inreason->入庫原因 }}">
                                <input type="hidden" id="oldinreason{{ $loop->index }}"
                                name="oldinreason{{ $loop->index }}" value="{{ $inreason->入庫原因 }}">
                            <hr />
                            @endforeach
                            {!! __('basicInfoLang.new') !!} : <input class="inreason" type="text" id="inreasonnew"
                                name="inreasonnew" value="">
                            <hr />
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="showloc">
                    <div class="row w-100 justify-content-center">
                        <div class="col col-auto">
                            @foreach($positions as $position)
                            <input class="position" type="checkbox" id="positioncheck" name="positioncheck"
                                style="width:20px;height:20px;" value="{{$loop->index}}">
                            <input class="position" type="text" id="position{{ $loop->index }}"
                                name="position{{ $loop->index }}" value="{{ $position->儲存位置 }}">
                                <input type="hidden" id="oldposition{{ $loop->index }}"
                                name="oldposition{{ $loop->index }}" value="{{ $position->儲存位置 }}">
                            <hr />
                            @endforeach
                            {!! __('basicInfoLang.new') !!} : <input class="position" type="text" id="positionnew"
                                name="positionnew" value="">
                            <hr />
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="showsenddep">
                    <div class="row w-100 justify-content-center">
                        <div class="col col-auto">
                            @foreach($sends as $send)
                            <input class="send" type="checkbox" id="sendcheck" name="sendcheck"
                                style="width:20px;height:20px;" value="{{$loop->index}}">
                            <input class="send" type="text" id="send{{ $loop->index }}" name="send{{ $loop->index }}"
                                value="{{ $send->發料部門 }}">
                                <input type="hidden" id="oldsend{{ $loop->index }}"
                                name="oldsend{{ $loop->index }}" value="{{ $send->發料部門 }}">
                            <hr />
                            @endforeach
                            {!! __('basicInfoLang.new') !!} : <input class="send" type="text" id="sendnew"
                                name="sendnew" value="">
                            <hr />
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="showobound">
                    <div class="row w-100 justify-content-center">
                        <div class="col col-auto">
                            @foreach($os as $o)
                            <input class="o" type="checkbox" id="ocheck" name="ocheck" style="width:20px;height:20px;"
                                value="{{$loop->index}}">
                            <input class="o" type="text" id="o{{ $loop->index }}" name="o{{ $loop->index }}"
                                value="{{ $o->O庫 }}">
                                <input type="hidden" id="oldo{{ $loop->index }}"
                                name="oldo{{ $loop->index }}" value="{{ $o->O庫 }}">
                            <hr />
                            @endforeach
                            {!! __('basicInfoLang.new') !!} : <input class="o" type="text" id="onew" name="onew"
                                value="">
                            <hr />
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="showreturnreason">
                    <div class="row w-100 justify-content-center">
                        <div class="col col-auto">
                            @foreach($backs as $back)
                            <input class="back" type="checkbox" id="backcheck" name="backcheck"
                                style="width:20px;height:20px;" value="{{$loop->index}}">
                            <input class="back" type="text" id="back{{ $loop->index }}" name="back{{ $loop->index }}"
                                value="{{ $back->退回原因 }}">
                                <input type="hidden" id="oldback{{ $loop->index }}"
                                name="oldback{{ $loop->index }}" value="{{ $back->退回原因 }}">
                            <hr />
                            @endforeach
                            {!! __('basicInfoLang.new') !!} : <input class="back" type="text" id="backnew"
                                name="backnew" value="">
                            <hr />
                        </div>
                    </div>
                </div>

            </div>

            <div class="row w-100 justify-content-center">
                <div class="col col-auto">
                    <input type="submit" id="change" name="change" class="btn btn-lg btn-primary"
                        value="{!! __('basicInfoLang.change') !!}">
                    &emsp;
                    <input type="submit" id="delete" name="delete" class="btn btn-lg btn-primary"
                        value="{!! __('basicInfoLang.delete') !!}">
                    &emsp;
                </div>
            </div>
            </form>
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
        </div>
    </div>


    <div class="row justify-content-center">
        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

        <div class="card w-75">
            <div class="card-header">
                <h3>{!! __('basicInfoLang.upload') !!}</h3>
            </div>

            <div class="row justify-content-center">
                <div class="card-body">
                    <div class=" w-100">
                        <form method="post" enctype="multipart/form-data" action="{{ route('basic.uploadbasic') }}">
                            @csrf
                            <div class="row w-100 justify-content-center mb-3">
                                <div class="col col-auto ">
                                    <a id="download" href="#" download>{!!
                                        __('basicInfoLang.exampleExcel')
                                        !!}</a>
                                </div>

                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                                <label class="col col-auto form-label">{!! __('basicInfoLang.plz_upload')
                                    !!}</label>
                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                                <div class="col col-auto">
                                    <input class="form-control @error('select_file') is-invalid @enderror" type="file"
                                        name="select_file" />
                                    @error('select_file')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                <div class="row w-100 justify-content-center">
                                    <div class="col col-auto">
                                        <input type="submit" name="upload" class="btn btn-lg btn-primary"
                                            value="{!! __('basicInfoLang.upload') !!}">
                                    </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
@endsection
