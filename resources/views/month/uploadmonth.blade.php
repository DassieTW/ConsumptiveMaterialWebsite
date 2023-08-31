@foreach ($data as $row)
    <?php
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
    //判斷是否有這個客戶
    if (in_array($row[1], $clients)) {
        $i = true;
    }
    
    if ($i === false) {
        $mess = trans('monthlyPRpageLang.noclient') . ' ' . trans('monthlyPRpageLang.row') . ' : ' . $error . ' ' . $row[1];
        echo "<script LANGUAGE='JavaScript'>
                                                                                window.alert('$mess');
                                                                                window.location.href='uploadmonth';
                                                                                </script>";
    }
    
    //判斷是否有這個機種
    if (in_array($row[2], $machines)) {
        $j = true;
    }
    
    if ($j === false) {
        $mess = trans('monthlyPRpageLang.nomachine') . ' ' . trans('monthlyPRpageLang.row') . ' : ' . $error . ' ' . $row[2];
        echo "<script LANGUAGE='JavaScript'>
                                                                                window.alert('$mess');
                                                                                window.location.href='uploadmonth';
                                                                                </script>";
    }
    
    //判斷是否有這個製程
    if (in_array($row[3], $productions)) {
        $k = true;
    }
    
    if ($k === false) {
        $mess = trans('monthlyPRpageLang.noproduction') . ' ' . trans('monthlyPRpageLang.row') . ' : ' . $error . ' ' . $row[3];
        echo "<script LANGUAGE='JavaScript'>
                                                                                window.alert('$mess');
                                                                                window.location.href='uploadmonth';
                                                                                </script>";
    }
    
    ?>
@endforeach
@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
    <!--for this page's sepcified js -->
    <script src="{{ asset('/js/month/uploadmonth.js?v=') . env('APP_VERSION') }}"></script>
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.monthly') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                <vue-bread-crumb></vue-bread-crumb>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('monthlyPRpageLang.importMonthlyData') !!}</h3>
            </div>

            <div class="card-body">
                <form id="uploadmonth" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table" id="test">
                            <tr>
                                <th><input type="hidden" id="title1" name="title1"
                                        value="客戶別">{!! __('monthlyPRpageLang.client') !!}</th>
                                <th><input type="hidden" id="title2" name="title2"
                                        value="機種">{!! __('monthlyPRpageLang.machine') !!}</th>
                                <th><input type="hidden" id="title3" name="title3"
                                        value="製程">{!! __('monthlyPRpageLang.process') !!}</th>
                                <th><input type="hidden" id="title8" name="title8"
                                        value="料號90">{!! __('monthlyPRpageLang.90isn') !!}</th>
                                <th><input type="hidden" id="title4" name="title4"
                                        value="本月MPS">{!! __('monthlyPRpageLang.nowmps') !!}</th>
                                <th><input type="hidden" id="title5" name="title5"
                                        value="本月生產天數">{!! __('monthlyPRpageLang.nowday') !!}</th>
                                <th><input type="hidden" id="title6" name="title6"
                                        value="下月MPS">{!! __('monthlyPRpageLang.nextmps') !!}</th>
                                <th><input type="hidden" id="title7" name="title7"
                                        value="下月生產天數">{!! __('monthlyPRpageLang.nextday') !!}</th>

                                <input type="hidden" id="time" name="time" value="8">
                            </tr>
                            @foreach ($data as $row)
                                <tr>

                                    <td><span id="data0{{ $loop->index }}">{{ $row[1] }}</span></td>
                                    <td><span id="data1{{ $loop->index }}">{{ $row[2] }}</span></td>
                                    <td><span id="data2{{ $loop->index }}">{{ $row[3] }}</span></td>
                                    <td><span id="data7{{ $loop->index }}">{{ $row[0] }}</span></td>
                                    <td><input class="form-control form-control-lg" type="number"
                                            id="data3{{ $loop->index }}" name="data3{{ $loop->index }}"
                                            value="{{ $row[4] }}" required step="0.001"
                                            oninput="if(value.length>5)value=value.slice(0,5)" min="0"></td>
                                    <td><input class="form-control form-control-lg" type="number"
                                            id="data4{{ $loop->index }}" name="data4{{ $loop->index }}"
                                            value="{{ $row[5] }}" required step="0.001"
                                            oninput="if(value.length>5)value=value.slice(0,5)" min="0"></td>
                                    <td><input class="form-control form-control-lg" type="number"
                                            id="data5{{ $loop->index }}" name="data5{{ $loop->index }}"
                                            value="{{ $row[6] }}" required step="0.001"
                                            oninput="if(value.length>5)value=value.slice(0,5)" min="0"></td>
                                    <td><input class="form-control form-control-lg" type="number"
                                            id="data6{{ $loop->index }}" name="data6{{ $loop->index }}"
                                            value="{{ $row[7] }}" required step="0.001"
                                            oninput="if(value.length>5)value=value.slice(0,5)" min="0"></td>
                                </tr>
                                <input type="hidden" id="count" name="count" value="{{ $loop->count }}">
                            @endforeach

                        </table>
                    </div>
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                    <input type="submit" class="btn btn-lg btn-primary" value="{!! __('monthlyPRpageLang.addtodatabase') !!}">
                </form>
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            </div>
        </div>
    </div>
@endsection
