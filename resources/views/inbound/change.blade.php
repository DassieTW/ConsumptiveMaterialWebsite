@extends('layouts.adminTemplate')
@section('css')
    <style>
        /* for single line table with over-flow , SAP style as asked */
        table {
            table-layout: fixed;
            /* width: 900px; */
        }

        .table-responsive {
            height: 600px;
            overflow: scroll;
        }

        thead tr:nth-child(1) th {
            background: white;
            position: sticky;
            top: 0;
            z-index: 10;
        }
    </style>
@endsection

@section('js')
    <!--for this page's sepcified js -->
    <script src="{{ asset('js/inbound/change.js?v=') . env('APP_VERSION') }}"></script>
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
                <h3>{!! __('inboundpageLang.locationchange') !!}</h3>
                <input class="form-control form-control-lg " type="text" id="numbersearch" name="numbersearch"
                    placeholder="{!! __('basicInfoLang.enterisn') !!}" oninput="if(value.length>12)value=value.slice(0,12)"
                    style="width: 200px">
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <form id="change" method="POST">
                        @csrf
                        <table class="table" id="inboundsearch">
                            <thead>
                                <tr>
                                    <th>{!! __('inboundpageLang.check') !!}</th>
                                    <th>{!! __('inboundpageLang.isn') !!}</th>
                                    <th>{!! __('inboundpageLang.nowstock') !!}</th>
                                    <th>{!! __('inboundpageLang.loc') !!}</th>
                                    <th>{!! __('inboundpageLang.client') !!}</th>
                                    <th>{!! __('inboundpageLang.updatetime') !!}</th>
                                    <th>{!! __('inboundpageLang.transferamount') !!}</th>
                                    <th>{!! __('inboundpageLang.newloc') !!}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $data)
                                    <tr id="{{ $loop->index }}" class="isnRows">
                                        <?php $position = DB::table('儲位')->pluck('儲存位置'); ?>
                                        <td><button class="basic btn btn-info btn-lg m-0 p-0 rounded-circle"
                                                id="submit{{ $loop->index }}" value="{{ $loop->index }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="23" height="23"
                                                    fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                                                    <path
                                                        d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425a.247.247 0 0 1 .02-.022Z" />
                                                </svg></button>
                                        </td>
                                        <td><input type="hidden" id="number{{ $loop->index }}"
                                                name="number{{ $loop->index }}"
                                                value="{{ $data->料號 }}">{{ $data->料號 }}</td>
                                        <td><input type="hidden" id="stock{{ $loop->index }}"
                                                name="stock{{ $loop->index }}"
                                                value="{{ $data->現有庫存 }}">{{ $data->現有庫存 }}</td>
                                        <td><input type="hidden" id="oldposition{{ $loop->index }}"
                                                name="oldposition{{ $loop->index }}"
                                                value="{{ $data->儲位 }}">{{ $data->儲位 }}</td>
                                        <td><input type="hidden" id="client{{ $loop->index }}"
                                                name="client{{ $loop->index }}"
                                                value="{{ $data->客戶別 }}">{{ $data->客戶別 }}</td>
                                        <td>{{ $data->最後更新時間 }}</td>
                                        <td><input type="number" style="width: 120px" id="amount{{ $loop->index }}"
                                                class="form-control form-control-lg" name="amount{{ $loop->index }}"
                                                placeholder="{!! __('inboundpageLang.enteramount') !!}" min="1">
                                        </td>
                                        <td>
                                            <select class="form-select form-select-lg" id="newposition{{ $loop->index }}"
                                                name="newposition{{ $loop->index }}" style="width:120px">
                                                <option style="display: none" disabled selected>{!! __('inboundpageLang.enterloc') !!}
                                                </option>
                                                @foreach ($position as $position)
                                                    @if ($position !== $data->儲位)
                                                        <option>{{ $position }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                            </tbody>
                            <input type="hidden" id="count" name="count" value="{{ $loop->count }}">
                            @endforeach


                        </table>

                </div>
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                </form>
                {{-- <button class="btn btn-lg btn-primary" onclick="location.href='{{route('inbound.positionchange')}}'">{!!
            __('inboundpageLang.return') !!}</button> --}}
            </div>
        </div>
    </div>
@endsection
