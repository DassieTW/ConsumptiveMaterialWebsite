@foreach ($data as $row)
    <?php

    $name = DB::table('O庫_material')
        ->where('料號', $row[1])
        ->value('品名');
    $format = DB::table('O庫_material')
        ->where('料號', $row[1])
        ->value('規格');
    $clients = DB::table('客戶別')
        ->pluck('客戶')
        ->toArray();
    $i = false;
    $j = false;
    $error = $loop->index + 1;
    //判斷是否有料號
    if ($name === null || $format === null) {
        $mess = trans('oboundpageLang.noisn') . ' ' . trans('oboundpageLang.row') . ' : ' . $error . ' ' . $row[1];
        echo "<script LANGUAGE='JavaScript'>
                        window.alert('$mess');
                        window.location.href='upload';

                        </script>";
    }
    //判斷是否有這個客戶
    if (in_array($row[0], $clients)) {
        $i = true;
    }

    if ($i === false) {
        $mess = trans('oboundpageLang.noclient') . ' ' . trans('oboundpageLang.row') . ' : ' . $error . ' ' . $row[0];
        echo "<script LANGUAGE='JavaScript'>
                        window.alert('$mess');
                        window.location.href='upload';

                        </script>";
    }
    ?>
@endforeach
@extends('layouts.adminTemplate')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
    <!-- <script src="{{ asset('/js/popupNotice.js') }}"></script> -->
    <script src="{{ asset('js/obound/upload.js') }}"></script>
    <!--for notifications pop up -->
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.obound') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                <vue-bread-crumb></vue-bread-crumb>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3>{!! __('oboundpageLang.stockupload') !!}</h3>
        </div>

        <div class="card-body">

            <form id="uploadinventory" method="POST">
                @csrf
                <div class="table-responsive">
                    <table class="table" id="test">
                        <tr>
                            <th><input type="hidden" id="title0" name="title0" value="客戶別">{!! __('oboundpageLang.client') !!}
                            </th>
                            <th><input type="hidden" id="title1" name="title1" value="料號">{!! __('oboundpageLang.isn') !!}
                            </th>
                            <th><input type="hidden" id="title2" name="title2" value="品名">{!! __('oboundpageLang.pName') !!}
                            </th>
                            <th><input type="hidden" id="title3" name="title3" value="規格">{!! __('oboundpageLang.format') !!}
                            </th>
                            <th><input type="hidden" id="title4" name="title4" value="數量">{!! __('oboundpageLang.amount') !!}
                            </th>
                            <th><input type="hidden" id="title5" name="title5" value="庫別">{!! __('oboundpageLang.bound') !!}
                            </th>
                        </tr>
                        @foreach ($data as $row)
                            <tr>

                                <td><input type="hidden" id="data0{{ $loop->index }}" name="data0{{ $loop->index }}"
                                        value="{{ $row[0] }}">{{ $row[0] }}</td>
                                <td><input type="hidden" id="data1{{ $loop->index }}" name="data1{{ $loop->index }}"
                                        value="{{ $row[1] }}">{{ $row[1] }}</td>
                                <td><input type="hidden" id="data2{{ $loop->index }}" name="data2{{ $loop->index }}"
                                        value="{{ $name }}">{{ $name }}</td>
                                <td><input type="hidden" id="data3{{ $loop->index }}" name="data3{{ $loop->index }}"
                                        value="{{ $format }}">{{ $format }}</td>
                                <td><input class="form-control form-control-lg" id="data4{{ $loop->index }}"
                                        type="number" name="data4{{ $loop->index }}" value="{{ $row[2] }}"
                                        min="1" required></td>
                                <td>
                                    <input class="form-control form-control-lg" id="data5{{ $loop->index }}"
                                        type="text" name="data5{{ $loop->index }}" value="{{ $row[3] }}"
                                        required>
                                </td>
                                </td>
                            </tr>
                            <input type="hidden" id="count" name="count" value="{{ $loop->count }}">
                        @endforeach

                    </table>
                </div>
                <input type="submit" class="btn btn-lg btn-primary" value="{!! __('oboundpageLang.addtodatabase') !!}">
            </form>
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            <button class="btn btn-lg btn-primary"
                onclick="location.href='{{ route('obound.upload') }}'">{!! __('oboundpageLang.return') !!}</button>
        </div>
    </div>
@endsection
