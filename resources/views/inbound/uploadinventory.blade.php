@foreach ($data as $row)
    <?php
    if (strlen(trim($row[1])) !== 0) {
        $name = DB::table('consumptive_material')
            ->where('料號', $row[1])
            ->value('品名');
        $format = DB::table('consumptive_material')
            ->where('料號', $row[1])
            ->value('規格');
        $position = DB::table('儲位')->pluck('儲存位置');
        $clients = DB::table('客戶別')
            ->pluck('客戶')
            ->toArray();
        $positions = DB::table('儲位')
            ->pluck('儲存位置')
            ->toArray();
        $i = false;
        $j = false;
        $error = $loop->index + 1;
        //判斷是否有料號
        if ($name === null || $format === null) {
            $mess = trans('inboundpageLang.noisn') . ' ' . trans('inboundpageLang.row') . ' : ' . $error . ' ' . $row[1];
            echo "<script LANGUAGE='JavaScript'>
                                                                        window.alert('$mess');
                                                                        window.location.href='upload';
                                                                        </script>";
        } // if
        //判斷是否有這個客戶
        if (in_array($row[0], $clients)) {
            $i = true;
        } // if
    
        if ($i === false) {
            $mess = trans('inboundpageLang.noclient') . ' ' . trans('inboundpageLang.row') . ' : ' . $error . ' ' . $row[0];
            echo "<script LANGUAGE='JavaScript'>
                                                                        window.alert('$mess');
                                                                        window.location.href='upload';
                                                                        </script>";
        } // if
    
        //判斷是否有這個儲位
        if (in_array($row[3], $positions)) {
            $j = true;
        } // if
    
        if ($j === false) {
            $mess = trans('inboundpageLang.noloc') . ' ' . trans('inboundpageLang.row') . ' : ' . $error . ' ' . $row[3];
            echo "<script LANGUAGE='JavaScript'>
                                                                        window.alert('$mess');
                                                                        window.location.href='upload';
                                                                        </script>";
        } // if
    }
    ?>
@endforeach
@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
    <script src="{{ asset('js/inbound/upload.js?v=') . env('APP_VERSION') }}"></script>
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.inbound') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                <vue-bread-crumb></vue-bread-crumb>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('inboundpageLang.stockupload') !!}</h3>
            </div>
            <div class="card-body">
                <form id="uploadinventory" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th><input type="hidden" id="title0" name="title0"
                                        value="客戶別">{!! __('inboundpageLang.client') !!}</th>
                                <th><input type="hidden" id="title1" name="title1"
                                        value="料號">{!! __('inboundpageLang.isn') !!}
                                </th>
                                <th><input type="hidden" id="title1" name="title1"
                                        value="品名">{!! __('inboundpageLang.pName') !!}</th>
                                <th><input type="hidden" id="title1" name="title1"
                                        value="規格">{!! __('inboundpageLang.format') !!}</th>
                                <th><input type="hidden" id="title2" name="title2"
                                        value="數量">{!! __('inboundpageLang.amount') !!}</th>
                                <th><input type="hidden" id="title2" name="title3"
                                        value="儲位">{!! __('inboundpageLang.loc') !!}
                                </th>
                            </tr>
                            @foreach ($data as $row)
                                <?php
                                $name = DB::table('consumptive_material')
                                    ->where('料號', $row[1])
                                    ->value('品名');
                                $format = DB::table('consumptive_material')
                                    ->where('料號', $row[1])
                                    ->value('規格');
                                ?>
                                @if (strlen(trim($row[1])) !== 0)
                                    <tr>
                                        <td><input type="hidden" id="data0{{ $loop->index }}"
                                                name="data0{{ $loop->index }}"
                                                value="{{ $row[0] }}">{{ $row[0] }}</td>
                                        <td><input type="hidden" id="data1{{ $loop->index }}"
                                                name="data1{{ $loop->index }}"
                                                value="{{ $row[1] }}">{{ $row[1] }}</td>
                                        <td>{{ $name }}</td>
                                        <td>{{ $format }}</td>
                                        <td><input type="number" id="data2{{ $loop->index }}"
                                                name="data2{{ $loop->index }}" value="{{ $row[2] }}" required
                                                min="1" class="form-control form-control-lg"></td>
                                        <td>
                                            <select class="form-select form-select-lg" id="data3{{ $loop->index }}"
                                                name="data3{{ $loop->index }}" required>
                                                <option style="display: none" selected value="{{ $row[3] }}">
                                                    {{ $row[3] }}</option>
                                                @foreach ($positions as $position)
                                                    <option>{{ $position }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                    <input type="hidden" id="count" name="count" value="{{ $loop->count }}">
                                @endif
                            @endforeach

                        </table>
                    </div>
                    <br>
                    <input type="submit" class="btn btn-lg btn-primary" value="{!! __('inboundpageLang.addtodatabase') !!}">
                </form>
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            </div>
        </div>
    </div>
@endsection
