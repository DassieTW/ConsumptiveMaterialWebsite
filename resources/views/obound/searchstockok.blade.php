@extends('layouts.adminTemplate')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
    <script src="{{ asset('js/obound/search.js') }}"></script>
    <!-- <script src="{{ asset('/js/popupNotice.js') }}"></script> -->
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
            <h3>{!! __('oboundpageLang.searchstock') !!}</h3>
            <input class="form-control form-control-lg " type="text" id="numbersearch" name="numbersearch"
                placeholder="{!! __('basicInfoLang.enterisn') !!}" oninput="if(value.length>12)value=value.slice(0,12)"
                style="width: 200px">
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table" id="inboundsearch">
                    <tr id="require">

                        <th><input type="hidden" id="title0" name="title0" value="料號">{!! __('oboundpageLang.isn') !!}</th>
                        <th><input type="hidden" id="title1" name="title1" value="現有庫存">{!! __('oboundpageLang.nowstock') !!}</th>
                        <th><input type="hidden" id="title2" name="title2" value="客戶別">{!! __('oboundpageLang.client') !!}</th>
                        <th><input type="hidden" id="title3" name="title3" value="庫別">{!! __('oboundpageLang.bound') !!}</th>
                        <th><input type="hidden" id="title4" name="title4" value="最後更新時間">{!! __('oboundpageLang.updatetime') !!}</th>
                        <th><input type="hidden" id="title5" name="title5" value="品名">{!! __('oboundpageLang.pName') !!}</th>
                        <th><input type="hidden" id="title6" name="title6" value="規格">{!! __('oboundpageLang.format') !!}</th>
                        <input type="hidden" id="time" name="time" value="13">
                    </tr>
                    @foreach ($data as $data)
                        <tr class="isnRows">
                            <td>{{ $data->料號 }}</td>
                            <input type="hidden" id="number{{ $loop->index }}" value="{{ $data->料號 }}">
                            <td>{{ $data->現有庫存 }}</td>
                            <td>{{ $data->客戶別 }}</td>
                            <td>{{ $data->庫別 }}</td>
                            <td>{{ $data->最後更新時間 }}</td>
                            <td>{{ $data->品名 }}</td>
                            <td>{{ $data->規格 }}</td>
                        </tr>
                    @endforeach

                </table>
            </div>
            <br>
            <button class="btn btn-lg btn-primary"
                onclick="location.href='{{ route('obound.searchstock') }}'">{!! __('oboundpageLang.return') !!}</button>
        </div>
    </div>
@endsection
