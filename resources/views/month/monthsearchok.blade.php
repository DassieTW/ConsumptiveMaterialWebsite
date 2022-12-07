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
    <script src="{{ asset('/js/month/monthsearch.js?v=') . env('APP_VERSION') }}"></script>
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
                <form id="monthsearch" method="POST">
                    @csrf
                    <input type="submit" id="delete" name="delete" class="btn btn-lg btn-primary"
                        value="{!! __('monthlyPRpageLang.delete') !!}">
                    {{-- <input type="submit" id="return" name="return" class="btn btn-lg btn-primary"
                value="{!! __('monthlyPRpageLang.return') !!}"> --}}

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{!! __('monthlyPRpageLang.delete') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.client') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.machine') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.process') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.nextmps') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.nextday') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.nowmps') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.nowday') !!}</th>
                                    <th>{!! __('monthlyPRpageLang.writetime') !!}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $data)
                                    <tr>
                                        <?php
                                        $data->本月MPS = round($data->本月MPS, 5);
                                        $data->本月生產天數 = round($data->本月生產天數, 5);
                                        $data->下月MPS = round($data->下月MPS, 5);
                                        $data->下月生產天數 = round($data->下月生產天數, 5);
                                        ?>
                                        <td><input class="innumber" type="checkbox" id="innumber" name="innumber"
                                                style="width:20px;height:20px;" value="{{ $loop->index }}"></td>
                                        <td><input type="hidden" id="client{{ $loop->index }}"
                                                name="client{{ $loop->index }}"
                                                value="{{ $data->客戶別 }}">{{ $data->客戶別 }}</td>
                                        <td><input type="hidden" id="machine{{ $loop->index }}"
                                                name="machine{{ $loop->index }}"
                                                value="{{ $data->機種 }}">{{ $data->機種 }}</td>
                                        <td><input type="hidden" id="production{{ $loop->index }}"
                                                name="production{{ $loop->index }}"
                                                value="{{ $data->製程 }}">{{ $data->製程 }}</td>
                                        <td>{{ $data->下月MPS }}</td>
                                        <td>{{ $data->下月生產天數 }}</td>
                                        <td>{{ $data->本月MPS }}</td>
                                        <td>{{ $data->本月生產天數 }}</td>
                                        <td>{{ $data->填寫時間 }}</td>
                                    </tr>
                                    <input type="hidden" id="count" name="count" value="{{ $loop->count }}">
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                </form>
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            </div>
        </div>
    </div>
@endsection
