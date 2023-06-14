@extends('layouts.adminTemplate')


@section('css')
    <style>
        :root {
            --form-control-color: rgb(13, 56, 186);
        }

        *:after {
            box-sizing: border-box;
        }

        input[type="checkbox"] {
            /* Add if not using autoprefixer */
            -webkit-appearance: none;
            /* Remove most all native input styles */
            appearance: none;
            /* For iOS < 15 */
            background-color: var(--form-background);
            /* Not removed via appearance */
            margin: 0;
            font: inherit;
            width: 1.2em;
            height: 1.2em;
            border: 0.15em solid #000000;
            border-radius: 0.15em;
            transform: translateY(-0.075em);
            display: grid;
            place-content: center;
        }

        input[type="checkbox"]::before {
            content: "";
            width: 1.0em;
            height: 1.0em;
            color: #713de0;
            clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%);
            transform: scale(0);
            transform-origin: bottom left;
            transition: 120ms transform ease-in-out;
            box-shadow: inset 1em 1em var(--form-control-color);
            /* Windows High Contrast Mode */
            background-color: CanvasText;
        }

        input[type="checkbox"]:hover {
            border-color: #078328;
        }

        input[type="checkbox"]:checked::before {
            transform: scale(1);
        }

        .arrow {
            position: absolute;
            z-index: 2;
            top: calc(30% - 1px);
            right: calc(0% - 1px);
            width: 3px;
            height: 16px;
            /* background: white; */

            animation: bounce 1s cubic-bezier(.19, .38, 0, 1) infinite;

            &::after {
                content: '';
                position: absolute;
                width: 8px;
                height: 8px;
                bottom: 0;
                left: -4px;
                border-bottom: solid darkgray 3px;
                border-right: solid darkgray 3px;
            }
        }

        @keyframes bounce {

            0%,
            100% {
                transform: translateX(-4px);
            }

            50% {
                transform: translateX(4px);
            }
        }

        .arrow.arrowR {
            &::after {
                transform: rotate(-45deg);
            }
        }

        .arrow.arrowL {
            &::after {
                transform: rotate(135deg);
            }
        }
    </style>
@endsection

@section('js')
    <!--for this page's sepcified js -->
    <script src="{{ asset('js/basic/basic.js?v=') . env('APP_VERSION') }}"></script>
@endsection

@section('content')
    <div class="row justify-content-center">
        {{-- <div class="arrow arrowL"></div> --}}
        <div class="arrow arrowL"></div>
        <div class="card w-100">
            <form id="basicdata" class="p-3 justify-content-center">
                @csrf
                <ul id="myTab" name="myTab" class="nav nav-tabs text-nowrap flex-nowrap" style="overflow-x: auto;">
                    <li class="nav-item" id="FactoryExample">
                        <a data-toggle="tab" class="nav-link" id="FactoryExamplea"
                            href="#showfactory">{!! __('basicInfoLang.factory') !!}</a>
                    </li>
                    <li class="nav-item" id="ClientExample">
                        <a id="ClientExamplea" data-toggle="tab" class="nav-link"
                            href="#showclient">{!! __('basicInfoLang.client') !!}</a>
                    </li>
                    <li class="nav-item" id="MachineExample">
                        <a id="MachineExamplea" data-toggle="tab" class="nav-link"
                            href="#showmachine">{!! __('basicInfoLang.machine') !!}</a>
                    </li>
                    <li class="nav-item" id="ProductionExample">
                        <a id="ProductionExamplea" data-toggle="pill" class="nav-link"
                            href="#showprocess">{!! __('basicInfoLang.process') !!}</a>
                    </li>
                    <li class="nav-item" id="LineExample">
                        <a id="LineExamplea" data-toggle="tab" class="nav-link" href="#showline">{!! __('basicInfoLang.line') !!}</a>
                    </li>
                    <li class="nav-item" id="UseExample">
                        <a id="UseExamplea" data-toggle="tab" class="nav-link"
                            href="#showusedep">{!! __('basicInfoLang.usedep') !!}</a>
                    </li>
                    <li class="nav-item" id="UseReasonExample">
                        <a id="UseReasonExamplea" data-toggle="tab" class="nav-link"
                            href="#showusereason">{!! __('basicInfoLang.usereason') !!}</a>
                    </li>
                    <li class="nav-item" id="InReasonExample">
                        <a id="InReasonExamplea" data-toggle="tab" class="nav-link"
                            href="#showinreason">{!! __('basicInfoLang.inreason') !!}</a>
                    </li>
                    <li class="nav-item" id="PositionExample">
                        <a id="PositionExamplea" data-toggle="tab" class="nav-link"
                            href="#showloc">{!! __('basicInfoLang.loc') !!}</a>
                    </li>
                    <li class="nav-item" id="SendExample">
                        <a id="SendExamplea" data-toggle="tab" class="nav-link"
                            href="#showsenddep">{!! __('basicInfoLang.senddep') !!}</a>
                    </li>
                    <li class="nav-item" id="OboundExample">
                        <a id="OboundExamplea" data-toggle="tab" class="nav-link"
                            href="#showobound">{!! __('basicInfoLang.obound') !!}</a>
                    </li>
                    <li class="nav-item" id="BackReasonExample">
                        <a id="BackReasonExamplea" data-toggle="tab" class="nav-link"
                            href="#showreturnreason">{!! __('basicInfoLang.returnreason') !!}</a>
                    </li>
                </ul>

                <div class="w-100" style="height: 2ch;"></div><!-- </div>breaks cols to a new line-->

                <div class="tab-content">
                    <div class="tab-pane fade" id="showfactory">
                        <div class="row w-100 justify-content-center">
                            <div class="col col-auto">
                                @foreach ($factorys as $factory)
                                    <label>
                                        <input type="checkbox" name="factorycheck" value="{{ $loop->index }}">
                                    </label>
                                    <label>
                                        <input class="form-control-lg" type="text" id="factory{{ $loop->index }}"
                                            name="factory{{ $loop->index }}" value="{{ $factory->廠別 }}">
                                    </label>
                                    <input type="hidden" id="oldfactory{{ $loop->index }}"
                                        name="oldfactory{{ $loop->index }}" value="{{ $factory->廠別 }}">
                                    <hr />
                                @endforeach
                                <label>{!! __('basicInfoLang.new') !!} : <input class="form-control-lg" type="text"
                                        id="factorynew" name="factorynew" value=""></label>
                                <hr />
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="showclient">
                        <div class="row w-100 justify-content-center">
                            <div class="col col-auto">
                                @foreach ($clients as $client)
                                    <label>
                                        <input type="checkbox" name="clientcheck" value="{{ $loop->index }}">
                                    </label>
                                    <label>
                                        <input class="form-control-lg" type="text" id="client{{ $loop->index }}"
                                            name="client{{ $loop->index }}" value="{{ $client->客戶 }}">
                                    </label>
                                    <input type="hidden" id="oldclient{{ $loop->index }}"
                                        name="oldclient{{ $loop->index }}" value="{{ $client->客戶 }}">
                                    <hr />
                                @endforeach
                                <label>{!! __('basicInfoLang.new') !!} : <input class="form-control-lg" type="text"
                                        id="clientnew" name="clientnew" value=""></label>
                                <hr />
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="showmachine">
                        <div class="row w-100 justify-content-center">
                            <div class="col col-auto">
                                @foreach ($machines as $machine)
                                    <label>
                                        <input type="checkbox" name="machinecheck" value="{{ $loop->index }}">
                                    </label>
                                    <label>
                                        <input class="form-control-lg" type="text" id="machine{{ $loop->index }}"
                                            name="machine{{ $loop->index }}" value="{{ $machine->機種 }}"></label>
                                    <input type="hidden" id="oldmachine{{ $loop->index }}"
                                        name="oldmachine{{ $loop->index }}" value="{{ $machine->機種 }}">
                                    <hr />
                                @endforeach
                                <label>{!! __('basicInfoLang.new') !!} : <input class="form-control-lg" type="text"
                                        id="machinenew" name="machinenew" value=""></label>
                                <hr />
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="showprocess">
                        <div class="row w-100 justify-content-center">
                            <div class="col col-auto">
                                @foreach ($productions as $production)
                                    <label><input type="checkbox" name="productioncheck"
                                            value="{{ $loop->index }}"></label>
                                    <label><input class="form-control-lg" type="text"
                                            id="production{{ $loop->index }}" name="production{{ $loop->index }}"
                                            value="{{ $production->制程 }}"></label>
                                    <input type="hidden" id="oldproduction{{ $loop->index }}"
                                        name="oldproduction{{ $loop->index }}" value="{{ $production->制程 }}">
                                    <hr />
                                @endforeach
                                <label>{!! __('basicInfoLang.new') !!} : <input class="form-control-lg" type="text"
                                        id="productionnew" name="productionnew" value=""></label>
                                <hr />
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="showline">
                        <div class="row w-100 justify-content-center">
                            <div class="col col-auto">
                                @foreach ($lines as $line)
                                    <label><input type="checkbox" name="linecheck" value="{{ $loop->index }}"></label>
                                    <label><input class="form-control-lg" type="text" id="line{{ $loop->index }}"
                                            name="line{{ $loop->index }}" value="{{ $line->線別 }}"></label>
                                    <input type="hidden" id="oldline{{ $loop->index }}"
                                        name="oldline{{ $loop->index }}" value="{{ $line->線別 }}">
                                    <hr />
                                @endforeach
                                <label>{!! __('basicInfoLang.new') !!} : <input class="form-control-lg" type="text"
                                        id="linenew" name="linewnew" value=""></label>
                                <hr />
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="showusedep">
                        <div class="row w-100 justify-content-center">
                            <div class="col col-auto">
                                @foreach ($uses as $use)
                                    <label><input type="checkbox" name="usecheck" value="{{ $loop->index }}"></label>
                                    <label><input class="form-control-lg" type="text" id="use{{ $loop->index }}"
                                            name="use{{ $loop->index }}" value="{{ $use->領用部門 }}"></label>
                                    <input type="hidden" id="olduse{{ $loop->index }}"
                                        name="olduse{{ $loop->index }}" value="{{ $use->領用部門 }}">
                                    <hr />
                                @endforeach
                                <label>{!! __('basicInfoLang.new') !!} : <input class="form-control-lg" type="text"
                                        id="usenew" name="usenew" value=""></label>
                                <hr />
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="showusereason">
                        <div class="row w-100 justify-content-center">
                            <div class="col col-auto">
                                @foreach ($usereasons as $usereason)
                                    <label><input type="checkbox" name="usereasoncheck"
                                            value="{{ $loop->index }}"></label>
                                    <label><input class="form-control-lg" type="text"
                                            id="usereason{{ $loop->index }}" name="usereason{{ $loop->index }}"
                                            value="{{ $usereason->領用原因 }}"></label>
                                    <input type="hidden" id="oldusereason{{ $loop->index }}"
                                        name="oldusereason{{ $loop->index }}" value="{{ $usereason->領用原因 }}">
                                    <hr />
                                @endforeach
                                <label>{!! __('basicInfoLang.new') !!} : <input class="form-control-lg" type="text"
                                        id="usereasonnew" name="usereasonnew" value=""></label>
                                <hr />
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="showinreason">
                        <div class="row w-100 justify-content-center">
                            <div class="col col-auto">
                                @foreach ($inreasons as $inreason)
                                    <label><input type="checkbox" name="inreasoncheck"
                                            value="{{ $loop->index }}"></label>
                                    <label><input class="form-control-lg" type="text"
                                            id="inreason{{ $loop->index }}" name="inreason{{ $loop->index }}"
                                            value="{{ $inreason->入庫原因 }}"></label>
                                    <input type="hidden" id="oldinreason{{ $loop->index }}"
                                        name="oldinreason{{ $loop->index }}" value="{{ $inreason->入庫原因 }}">
                                    <hr />
                                @endforeach
                                <label><input class="form-control-lg" type="text" id="inreasonnew" name="inreasonnew"
                                        value="" placeholder="{!! __('basicInfoLang.new') !!}">
                                </label>
                                <hr />
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="showloc">
                        <div class="row w-100 justify-content-center">
                            <div class="col col-auto">
                                @foreach ($positions as $position)
                                    <label><input type="checkbox" name="positioncheck"
                                            value="{{ $loop->index }}"></label>
                                    <label><input class="form-control-lg" type="text"
                                            id="position{{ $loop->index }}" name="position{{ $loop->index }}"
                                            value="{{ $position->儲存位置 }}"></label>
                                    <input type="hidden" id="oldposition{{ $loop->index }}"
                                        name="oldposition{{ $loop->index }}" value="{{ $position->儲存位置 }}">
                                    <hr />
                                @endforeach
                                <label>{!! __('basicInfoLang.new') !!} : <input class="form-control-lg" type="text"
                                        id="positionnew" name="positionnew" value=""></label>
                                <hr />
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="showsenddep">
                        <div class="row w-100 justify-content-center">
                            <div class="col col-auto">
                                @foreach ($sends as $send)
                                    <label><input type="checkbox" name="sendcheck" value="{{ $loop->index }}"></label>
                                    <label><input class="form-control-lg" type="text" id="send{{ $loop->index }}"
                                            name="send{{ $loop->index }}" value="{{ $send->發料部門 }}"></label>
                                    <input type="hidden" id="oldsend{{ $loop->index }}"
                                        name="oldsend{{ $loop->index }}" value="{{ $send->發料部門 }}">
                                    <hr />
                                @endforeach
                                <label>{!! __('basicInfoLang.new') !!} : <input class="form-control-lg" type="text"
                                        id="sendnew" name="sendnew" value=""></label>
                                <hr />
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="showobound">
                        <div class="row w-100 justify-content-center">
                            <div class="col col-auto">
                                @foreach ($os as $o)
                                    <label><input type="checkbox" name="ocheck" value="{{ $loop->index }}"></label>
                                    <label><input class="form-control-lg" type="text" id="o{{ $loop->index }}"
                                            name="o{{ $loop->index }}" value="{{ $o->O庫 }}"></label>
                                    <input type="hidden" id="oldo{{ $loop->index }}" name="oldo{{ $loop->index }}"
                                        value="{{ $o->O庫 }}">
                                    <hr />
                                @endforeach
                                <label>{!! __('basicInfoLang.new') !!} : <input class="form-control-lg" type="text"
                                        id="onew" name="onew" value=""></label>
                                <hr />
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="showreturnreason">
                        <div class="row w-100 justify-content-center">
                            <div class="col col-auto">
                                @foreach ($backs as $back)
                                    <label><input type="checkbox" name="backcheck" value="{{ $loop->index }}"></label>
                                    <label><input class="form-control-lg" type="text" id="back{{ $loop->index }}"
                                            name="back{{ $loop->index }}" value="{{ $back->退回原因 }}"></label>
                                    <input type="hidden" id="oldback{{ $loop->index }}"
                                        name="oldback{{ $loop->index }}" value="{{ $back->退回原因 }}">
                                    <hr />
                                @endforeach
                                <label>{!! __('basicInfoLang.new') !!} : <input class="form-control-lg" type="text"
                                        id="backnew" name="backnew" value=""></label>
                                <hr />
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row w-100 justify-content-center">
                    <div class="col col-auto justify-content-between p-0 m-0">
                        <input type="submit" id="delete" name="delete" class="btn btn-lg btn-danger"
                            value="{!! __('basicInfoLang.delete') !!}">
                        &emsp;
                        <input type="submit" id="change" name="change" class="btn btn-lg btn-success"
                            value="{!! __('basicInfoLang.change') !!}">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

    <div class="card w-100">
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
                                <a id="download" href="#" download>{!! __('basicInfoLang.exampleExcel') !!}</a>
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <label class="col col-auto form-label">{!! __('basicInfoLang.plz_upload') !!}</label>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <div class="col col-auto">
                                <input class="form-control @error('select_file') is-invalid @enderror" type="file"
                                    name="select_file" />
                                @error('select_file')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{!! __('basicInfoLang.plz_upload') !!}</strong>
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
@endsection
