@foreach ($data as $row)
    <?php
    if (strlen(trim($row[0])) !== 0) {
        $name = DB::table('consumptive_material')
            ->where('料號', trim($row[0]))
            ->value('品名');
        $i = false;
        $error = $loop->index + 1;
    
        //判斷是否有料號
        if ($name === null) {
            $mess = trans('monthlyPRpageLang.noisn') . ' ' . trans('monthlyPRpageLang.row') . ' : ' . $error . ' ' . $row[0];
            echo "<script LANGUAGE='JavaScript'>
                                        window.alert('$mess');
                                        window.location.href='uploadnotmonth';
                                        </script>";
        }
    }
    ?>
@endforeach
@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
    <!--for this page's sepcified js -->
    <script src="{{ asset('/js/month/uploadnotmonth.js?v=') . env('APP_VERSION') }}"></script>
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
                <h3>{!! __('monthlyPRpageLang.importNonMonthlyData') !!}</h3>
            </div>

            <div class="card-body">

                <form id="uploadnotmonth" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table" id="test">
                            <tr>
                                <th><input type="hidden" id="title0" name="title0"
                                        value="料號">{!! __('monthlyPRpageLang.isn') !!}</th>
                                <th><input type="hidden" id="title1" name="title1"
                                        value="品名">{!! __('monthlyPRpageLang.pName') !!}</th>
                                <th><input type="hidden" id="title2" name="title2"
                                        value="請購數量">{!! __('monthlyPRpageLang.buyamount1') !!}</th>
                                <th><input type="hidden" id="title3" name="title3"
                                        value="說明">{!! __('monthlyPRpageLang.description') !!}</th>
                                <input type="hidden" id="titlecount" name="titlecount" value="4">
                            </tr>
                            @foreach ($data as $row)
                                @if (strlen(trim($row[0])) !== 0)
                                    <tr id="row{{ $loop->index }}">

                                        <?php
                                        $name = DB::table('consumptive_material')
                                            ->where('料號', trim($row[0]))
                                            ->value('品名');
                                        ?>
                                        <td><input type="hidden" id="number{{ $loop->index }}"
                                                name="number{{ $loop->index }}"
                                                value="{{ trim($row[0]) }}">{{ $row[0] }}
                                        </td>
                                        <td>{{ $name }}</td>
                                        <td><input class="form-control corm-control-lg" type="number"
                                                id="amount{{ $loop->index }}" name="amount{{ $loop->index }}"
                                                value="{{ trim($row[1]) }}" required style="width: 100px"></td>
                                        <td><input class="form-control corm-control-lg" type="text"
                                                id="desc{{ $loop->index }}" name="desc{{ $loop->index }}"
                                                value="{{ trim($row[2]) }}" style="width: 150px"></td>

                                    </tr>
                                    <input type="hidden" id="count" name="count" value="{{ $loop->count }}">
                                @endif
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
