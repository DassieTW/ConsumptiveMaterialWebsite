@foreach ($data as $row)
    <?php
    $name = DB::table('consumptive_material')
        ->where('料號', trim($row[1]))
        ->value('品名');
    $format = DB::table('consumptive_material')
        ->where('料號', trim($row[1]))
        ->value('規格');
    $unit = DB::table('consumptive_material')
        ->where('料號', trim($row[1]))
        ->value('單位');
    $lt = DB::table('consumptive_material')
        ->where('料號', trim($row[1]))
        ->value('LT');
    $month = DB::table('consumptive_material')
        ->where('料號', trim($row[1]))
        ->value('月請購');
    $belong = DB::table('consumptive_material')
        ->where('料號', trim($row[1]))
        ->value('耗材歸屬');
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
    if ($name === null || $format === null || $month === '否' || $belong !== '單耗') {
        $mess = trans('monthlyPRpageLang.noisn') . ' ' . trans('monthlyPRpageLang.row') . ' : ' . $error . ' ' . $row[1];
        echo "<script LANGUAGE='JavaScript'>
                                                                                                                                                                                                            window.alert('$mess');
                                                                                                                                                                                                            window.location.href='uploadconsume';
                                                                                                                                                                                                            </script>";
    }
    //判斷是否有這個客戶
    if (in_array(trim($row[2]), $clients)) {
        $i = true;
    }
    
    if ($i === false) {
        $mess = trans('monthlyPRpageLang.noclient') . ' ' . trans('monthlyPRpageLang.row') . ' : ' . $error . ' ' . $row[2];
        echo "<script LANGUAGE='JavaScript'>
                                                                                                                                                                                                            window.alert('$mess');
                                                                                                                                                                                                            window.location.href='uploadconsume';
                                                                                                                                                                                                            </script>";
    }
    
    //判斷是否有這個機種
    if (in_array(trim($row[4]), $machines)) {
        $j = true;
    }
    
    if ($j === false) {
        $mess = trans('monthlyPRpageLang.nomachine') . ' ' . trans('monthlyPRpageLang.row') . ' : ' . $error . ' ' . $row[4];
        echo "<script LANGUAGE='JavaScript'>
                                                                                                                                                                                                            window.alert('$mess');
                                                                                                                                                                                                            window.location.href='uploadconsume';
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
                                                                                                                                                                                                            window.location.href='uploadconsume';
                                                                                                                                                                                                            </script>";
    }
    ?>
@endforeach
@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
    <!--for this page's sepcified js -->
    <script src="{{ asset('/js/month/uploadconsume.js?v=') . env('APP_VERSION') }}"></script>
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.monthly') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                <vue-bread-crumb></vue-bread-crumb>
            </div>
        </div>
        <div id="consumebody">
            <div class="row justify-content-center">
                <div class="card">
                    <div class="card-header">
                        <h3>{!! __('monthlyPRpageLang.isnConsumeAdd') !!}</h3>
                    </div>
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                    <div class="card w-100">
                        <div class="card-header">
                            <h3>{!! __('monthlyPRpageLang.upload') !!}</h3>
                        </div>
                        <form id="uploadconsume" method="POST">
                            @csrf
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <th><input type="hidden" id="title0" name="title0"
                                                value="90料號">{!! __('monthlyPRpageLang.90isn') !!}</th>
                                        <th><input type="hidden" id="title1" name="title1"
                                                value="料號">{!! __('monthlyPRpageLang.isn') !!}</th>
                                        <th><input type="hidden" id="title2" name="title2"
                                                value="品名">{!! __('monthlyPRpageLang.pName') !!}</th>
                                        <th><input type="hidden" id="title3" name="title3"
                                                value="規格">{!! __('monthlyPRpageLang.format') !!}</th>
                                        <th><input type="hidden" id="title4" name="title4"
                                                value="單位">{!! __('monthlyPRpageLang.unit') !!}</th>
                                        <th><input type="hidden" id="title5" name="title5"
                                                value="LT">{!! __('monthlyPRpageLang.lt') !!}</th>
                                        <th><input type="hidden" id="title6" name="title6"
                                                value="單耗">{!! __('monthlyPRpageLang.consume') !!}</th>
                                        <th><input type="hidden" id="title7" name="title7"
                                                value="客戶別">{!! __('monthlyPRpageLang.client') !!}</th>
                                        <th><input type="hidden" id="title8" name="title8"
                                                value="機種">{!! __('monthlyPRpageLang.machine') !!}</th>
                                        <th><input type="hidden" id="title9" name="title9"
                                                value="製程">{!! __('monthlyPRpageLang.process') !!}</th>
                                    </tr>
                                    @foreach ($data as $row)
                                        <tr id="row{{ $loop->index }}">
                                            <?php
                                            $name = DB::table('consumptive_material')
                                                ->where('料號', trim($row[1]))
                                                ->value('品名');
                                            $format = DB::table('consumptive_material')
                                                ->where('料號', trim($row[1]))
                                                ->value('規格');
                                            $unit = DB::table('consumptive_material')
                                                ->where('料號', trim($row[1]))
                                                ->value('單位');
                                            $lt = DB::table('consumptive_material')
                                                ->where('料號', trim($row[1]))
                                                ->value('LT');
                                            $lt = round($lt, 3);
                                            ?>
                                            <td><input type="hidden" id="dataj{{ $loop->index }}"
                                                    name="dataj{{ $loop->index }}"
                                                    value="{{ trim($row[0]) }}">{{ trim($row[0]) }}</td>
                                            <td><input type="hidden" id="dataa{{ $loop->index }}"
                                                    name="dataa{{ $loop->index }}"
                                                    value="{{ trim($row[1]) }}">{{ trim($row[1]) }}</td>
                                            <td>{{ $name }}</td>
                                            <td>{{ $format }}</td>
                                            <td>{{ $unit }}</td>
                                            <td><input type="hidden" id="datab{{ $loop->index }}"
                                                    name="datab{{ $loop->index }}"
                                                    value="{{ $lt }}">{{ $lt }}</td>
                                            <td><input style="width:200px" type="number" id="datac{{ $loop->index }}"
                                                    name="datac{{ $loop->index }}" step="0.0000000001" required
                                                    value="{{ trim($row[5]) }}" class="form-control form-control-lg"
                                                    min="0.0000000001">
                                            </td>

                                            <td><input type="hidden" id="datag{{ $loop->index }}"
                                                    name="datag{{ $loop->index }}"
                                                    value="{{ trim($row[2]) }}">{{ trim($row[2]) }}</td>
                                            <td><input type="hidden" id="datah{{ $loop->index }}"
                                                    name="datah{{ $loop->index }}"
                                                    value="{{ trim($row[4]) }}">{{ trim($row[4]) }}</td>
                                            <td><input type="hidden" id="datai{{ $loop->index }}"
                                                    name="datai{{ $loop->index }}"
                                                    value="{{ trim($row[3]) }}">{{ trim($row[3]) }}</td>


                                        </tr>
                                        <input type="hidden" id="count" name="count" value="{{ $loop->count }}">
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

                        {{-- <div class="row w-100 justify-content-start">
                        <div class="col col-auto">
                            <button class="btn btn-lg btn-primary"
                                onclick="location.href='{{route('month.consumeadd')}}'">{!!
                                __('monthlyPRpageLang.return') !!}</button>
                        </div>
                    </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
