@foreach ($data as $row)
    <?php
    $name = DB::table('consumptive_material')
        ->where('料號', trim($row[0]))
        ->value('品名');
    $unit = DB::table('consumptive_material')
        ->where('料號', trim($row[0]))
        ->value('單位');
    $mpq = DB::table('consumptive_material')
        ->where('料號', trim($row[0]))
        ->value('MPQ');
    $lt = DB::table('consumptive_material')
        ->where('料號', trim($row[0]))
        ->value('LT');
    $month = DB::table('consumptive_material')
        ->where('料號', trim($row[0]))
        ->value('月請購');
    // $belong = DB::table('consumptive_material')
    //     ->where('料號', trim($row[0]))
    //     ->value('耗材歸屬');
    $clients = DB::table('客戶別')
        ->pluck('客戶')
        ->toArray();
    $machines = DB::table('機種')
        ->pluck('機種')
        ->toArray();
    $productions = DB::table('製程')
        ->pluck('制程')
        ->toArray();
    $i = false;
    $j = false;
    $k = false;
    $error = $loop->index + 1;
    //判斷是否有料號
    if ($name === null || $unit === null || $month === '否') {
        $mess = trans('monthlyPRpageLang.noisn') . ' ' . trans('monthlyPRpageLang.row') . ' : ' . $error . ' ' . $row[0];
        echo "<script LANGUAGE='JavaScript'>
            window.alert('$mess');
            window.location.href='uploadstand';
            </script>";
    }
    //判斷是否有這個客戶
    if (in_array(trim($row[1]), $clients)) {
        $i = true;
    }
    
    if ($i === false) {
        $mess = trans('monthlyPRpageLang.noclient') . ' ' . trans('monthlyPRpageLang.row') . ' : ' . $error . ' ' . $row[1];
        echo "<script LANGUAGE='JavaScript'>
            window.alert('$mess');
            window.location.href='uploadstand';
            </script>";
    }
    
    //判斷是否有這個機種
    if (in_array(trim($row[2]), $machines)) {
        $j = true;
    }
    
    if ($j === false) {
        $mess = trans('monthlyPRpageLang.nomachine') . ' ' . trans('monthlyPRpageLang.row') . ' : ' . $error . ' ' . $row[2];
        echo "<script LANGUAGE='JavaScript'>
            window.alert('$mess');
            window.location.href='uploadstand';
            </script>";
    }
    
    //判斷是否有這個製程
    if (in_array(trim($row[3]), $productions)) {
        $k = true;
    }
    
    if ($k === false) {
        $mess = trans('monthlyPRpageLang.noproduction') . ' ' . trans('monthlyPRpageLang.row') . ' : ' . $error . ' ' . $row[3];
        echo "<script LANGUAGE='JavaScript'>
            window.alert('$mess');
            window.location.href='uploadstand';
            </script>";
    }
    ?>
@endforeach
@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
    <!--for this page's sepcified js -->
    <script src="{{ asset('/js/month/uploadstand.js?v=') . env('APP_VERSION') }}"></script>
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.monthly') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                <vue-bread-crumb></vue-bread-crumb>
            </div>
        </div>
        <div id="standbody">
            <div class="row justify-content-center">
                <div class="card">
                    <div class="card-header">
                        <h3>{!! __('monthlyPRpageLang.standAdd') !!}</h3>
                    </div>
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                    <div class="card w-100">
                        <div class="card-header">
                            <h3>{!! __('monthlyPRpageLang.upload') !!}</h3>
                        </div>

                        <form id="uploadstand" method="POST">
                            @csrf
                            <div class="table-responsive">
                                <table class="table" id="test">
                                    <tr>
                                        <th><input type="hidden" id="title0" name="title0"
                                                value="料號">{!! __('monthlyPRpageLang.isn') !!}</th>
                                        <th><input type="hidden" id="title1" name="title1"
                                                value="品名">{!! __('monthlyPRpageLang.pName') !!}</th>
                                        <th><input type="hidden" id="title2" name="title2"
                                                value="單位">{!! __('monthlyPRpageLang.unit') !!}</th>
                                        <th><input type="hidden" id="title3" name="title3"
                                                value="MPQ">{!! __('monthlyPRpageLang.mpq') !!}</th>
                                        <th><input type="hidden" id="title4" name="title4"
                                                value="LT">{!! __('monthlyPRpageLang.lt') !!}
                                        </th>
                                        <th><input type="hidden" id="title5" name="title5" value="當月站位人數"
                                                step="0.0000001"
                                                class="form-control form-control-lg">{!! __('monthlyPRpageLang.nowpeople') !!}</th>
                                        <th><input type="hidden" id="title6" name="title6" value="當月開線數"
                                                step="0.0000001"
                                                class="form-control form-control-lg">{!! __('monthlyPRpageLang.nowline') !!}</th>
                                        <th><input type="hidden" id="title7" name="title7" value="當月開班數"
                                                step="0.0000001"
                                                class="form-control form-control-lg">{!! __('monthlyPRpageLang.nowclass') !!}</th>
                                        <th><input type="hidden" id="title8" name="title8" value="當月每人每日需求量"
                                                step="0.0000001"
                                                class="form-control form-control-lg">{!! __('monthlyPRpageLang.nowuse') !!}</th>
                                        <th><input type="hidden" id="title9" name="title9" value="當月每日更換頻率"
                                                step="0.0000001"
                                                class="form-control form-control-lg">{!! __('monthlyPRpageLang.nowchange') !!}</th>
                                        <th><input type="hidden" id="title10" name="title10" value="當月每日需求"
                                                step="0.0000001"
                                                class="form-control form-control-lg">{!! __('monthlyPRpageLang.nowdayneed') !!}</th>
                                        <th><input type="hidden" id="title11" name="title11" value="下月站位人數"
                                                step="0.0000001"
                                                class="form-control form-control-lg">{!! __('monthlyPRpageLang.nextpeople') !!}</th>
                                        <th><input type="hidden" id="title12" name="title12" value="下月開線數"
                                                step="0.0000001"
                                                class="form-control form-control-lg">{!! __('monthlyPRpageLang.nextline') !!}</th>
                                        <th><input type="hidden" id="title13" name="title13" value="下月開班數"
                                                step="0.0000001"
                                                class="form-control form-control-lg">{!! __('monthlyPRpageLang.nextclass') !!}</th>
                                        <th><input type="hidden" id="title14" name="title14" value="下月每人每日需求量"
                                                step="0.0000001"
                                                class="form-control form-control-lg">{!! __('monthlyPRpageLang.nextuse') !!}</th>
                                        <th><input type="hidden" id="title15" name="title15" value="下月每日更換頻率"
                                                step="0.0000001"
                                                class="form-control form-control-lg">{!! __('monthlyPRpageLang.nextchange') !!}</th>
                                        <th><input type="hidden" id="title16" name="title16" value="下月每日需求"
                                                step="0.0000001"
                                                class="form-control form-control-lg">{!! __('monthlyPRpageLang.nextdayneed') !!}</th>
                                        <th><input type="hidden" id="title17" name="title17"
                                                value="安全庫存">{!! __('monthlyPRpageLang.safe') !!}</th>
                                        <th><input type="hidden" id="title18" name="title18"
                                                value="客戶別">{!! __('monthlyPRpageLang.client') !!}</th>
                                        <th><input type="hidden" id="title19" name="title19"
                                                value="機種">{!! __('monthlyPRpageLang.machine') !!}</th>
                                        <th><input type="hidden" id="title20" name="title20"
                                                value="製程">{!! __('monthlyPRpageLang.process') !!}</th>

                                        <input type="hidden" id="time" name="time" value="21">
                                    </tr>
                                    @foreach ($data as $row)
                                        <tr id="row{{ $loop->index }}">
                                            <?php
                                            $name = DB::table('consumptive_material')
                                                ->where('料號', trim($row[0]))
                                                ->value('品名');
                                            $unit = DB::table('consumptive_material')
                                                ->where('料號', trim($row[0]))
                                                ->value('單位');
                                            $mpq = DB::table('consumptive_material')
                                                ->where('料號', trim($row[0]))
                                                ->value('MPQ');
                                            $lt = DB::table('consumptive_material')
                                                ->where('料號', trim($row[0]))
                                                ->value('LT');
                                            $lt = round($lt, 3);
                                            ?>
                                            <td><input type="hidden" id="data0{{ $loop->index }}"
                                                    name="data0{{ $loop->index }}"
                                                    value="{{ trim($row[0]) }}">{{ trim($row[0]) }}</td>
                                            <td>{{ $name }}</td>
                                            <td>{{ $unit }}</td>
                                            <td><input type="hidden" id="data1{{ $loop->index }}"
                                                    name="data1{{ $loop->index }}"
                                                    value="{{ $mpq }}">{{ $mpq }}</td>
                                            <td><input type="hidden" id="data2{{ $loop->index }}"
                                                    name="data2{{ $loop->index }}"
                                                    value="{{ $lt }}">{{ $lt }}</td>
                                            <td><input style="width:120px" type="number" id="data3{{ $loop->index }}"
                                                    name="data3{{ $loop->index }}" value="{{ trim($row[4]) }}"
                                                    step="0.0000001" class="form-control form-control-lg"></td>
                                            <td><input style="width:120px" type="number" id="data4{{ $loop->index }}"
                                                    name="data4{{ $loop->index }}" value="{{ trim($row[5]) }}"
                                                    step="0.0000001" class="form-control form-control-lg"></td>
                                            <td><input style="width:120px" type="number" id="data5{{ $loop->index }}"
                                                    name="data5{{ $loop->index }}" value="{{ trim($row[6]) }}"
                                                    step="0.0000001" class="form-control form-control-lg"></td>
                                            <td><input style="width:120px" type="number" id="data6{{ $loop->index }}"
                                                    name="data6{{ $loop->index }}" value="{{ trim($row[7]) }}"
                                                    step="0.0000001" class="form-control form-control-lg"></td>
                                            <td><input style="width:120px" type="number" id="data7{{ $loop->index }}"
                                                    name="data7{{ $loop->index }}" value="{{ trim($row[8]) }}"
                                                    step="0.0000001" class="form-control form-control-lg"></td>
                                            <td><input style="width:150px" type="number" id="data8{{ $loop->index }}"
                                                    name="data8{{ $loop->index }}" readonly step="0.0000001"
                                                    class="form-control form-control-lg"></td>
                                            <td><input style="width:120px" type="number" id="data9{{ $loop->index }}"
                                                    name="data9{{ $loop->index }}" value="{{ trim($row[9]) }}"
                                                    step="0.0000001" class="form-control form-control-lg"></td>
                                            <td><input style="width:120px" type="number" id="data10{{ $loop->index }}"
                                                    name="data10{{ $loop->index }}" value="{{ trim($row[10]) }}"
                                                    step="0.0000001" class="form-control form-control-lg"></td>
                                            <td><input style="width:120px" type="number" id="data11{{ $loop->index }}"
                                                    name="data11{{ $loop->index }}" value="{{ trim($row[11]) }}"
                                                    step="0.0000001" class="form-control form-control-lg"></td>
                                            <td><input style="width:120px" type="number" id="data12{{ $loop->index }}"
                                                    name="data12{{ $loop->index }}" value="{{ trim($row[12]) }}"
                                                    step="0.0000001" class="form-control form-control-lg"></td>
                                            <td><input style="width:120px" type="number" id="data13{{ $loop->index }}"
                                                    name="data13{{ $loop->index }}" value="{{ trim($row[13]) }}"
                                                    step="0.0000001" class="form-control form-control-lg"></td>
                                            <td><input style="width:150px" type="number" id="data14{{ $loop->index }}"
                                                    name="data14{{ $loop->index }}" readonly step="0.0000001"
                                                    class="form-control form-control-lg"></td>
                                            <td><input style="width:150px" type="number" id="data15{{ $loop->index }}"
                                                    name="data15{{ $loop->index }}" readonly step="0.0000001"
                                                    class="form-control form-control-lg"></td>
                                            <td><input type="hidden" id="data16{{ $loop->index }}"
                                                    name="data16{{ $loop->index }}"
                                                    value="{{ trim($row[1]) }}">{{ trim($row[1]) }}</td>
                                            <td><input type="hidden" id="data17{{ $loop->index }}"
                                                    name="data17{{ $loop->index }}"
                                                    value="{{ trim($row[2]) }}">{{ trim($row[2]) }}</td>
                                            <td><input type="hidden" id="data18{{ $loop->index }}"
                                                    name="data18{{ $loop->index }}"
                                                    value="{{ trim($row[3]) }}">{{ trim($row[3]) }}</td>
                                        </tr>
                                        <input type="hidden" id="count" name="count"
                                            value="{{ $loop->count }}">
                                    @endforeach

                                </table>
                            </div>
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <div id="emailinputarea">
                                <label class="form-label col col-3">{!! __('monthlyPRpageLang.surepeopleemail') !!}:</label>
                                <div class="col col-8">
                                    <div class="input-group" style="width: 30ch;">
                                        <select class="form-select form-select-lg" id="email">
                                            <option style="display: none" disabled selected value="">
                                                {!! __('monthlyPRpageLang.noemail') !!}
                                            </option>
                                            @foreach ($people as $people)
                                                <option value="{{ $people->姓名 }}">{{ $people->email }}</option>
                                            @endforeach
                                        </select>
                                        <span class="input-group-text input-group-text-lg" id="emailTail"></span>

                                    </div>
                                </div>
                            </div>

                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                            <input type="submit" class="btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.addtodatabase') !!}">
                        </form>
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
