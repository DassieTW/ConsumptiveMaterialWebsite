@extends('layouts.adminTemplate')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('./admin/css/app.css?v=') . time() }}">
@endsection

@section('js')
    <!--for this page's sepcified js -->
    <script src="{{ asset('/js/month/monthadd.js') }}"></script>
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
                <form id="monthadd" method="POST">
                    @csrf
                    <div class="table-responsive">
                        <table class="table" id="month">
                            <tr>
                                <td>{!! __('monthlyPRpageLang.client') !!}</td>
                                <td>{!! __('monthlyPRpageLang.machine') !!}</td>
                                <td>{!! __('monthlyPRpageLang.process') !!}</td>
                                <td>{!! __('monthlyPRpageLang.nextmps') !!}</td>
                                <td>{!! __('monthlyPRpageLang.nextday') !!}</td>
                                <td>{!! __('monthlyPRpageLang.nowmps') !!}</td>
                                <td>{!! __('monthlyPRpageLang.nowday') !!}</td>
                            </tr>
                            @foreach ($production as $production)
                                <tr>
                                    <td><span id="client{{ $loop->index }}">{{ $client }}</span></td>
                                    <td><span id="machine{{ $loop->index }}">{{ $machine }}</span></td>
                                    <td><span id="production{{ $loop->index }}">{{ $production }}</span></td>
                                    <td><input class="form-control form-control-lg" type="number"
                                            id="nextmps{{ $loop->index }}" name="nextmps{{ $loop->index }}" required
                                            value="{{ $nextmps }}" step="0.001"
                                            oninput="if(value.length>5)value=value.slice(0,5)" min="0"></td>
                                    <td><input class="form-control form-control-lg" type="number"
                                            id="nextday{{ $loop->index }}" name="nextday{{ $loop->index }}" required
                                            value="{{ $nextday }}" step="0.001"
                                            oninput="if(value.length>5)value=value.slice(0,5)" min="0"></td>
                                    <td><input class="form-control form-control-lg" type="number"
                                            id="nowmps{{ $loop->index }}" name="nowmps{{ $loop->index }}" required
                                            value="{{ $nowmps }}" step="0.001"
                                            oninput="if(value.length>5)value=value.slice(0,5)" min="0"></td>
                                    <td><input class="form-control form-control-lg" type="number"
                                            id="nowday{{ $loop->index }}" name="nowday{{ $loop->index }}" required
                                            value="{{ $nowday }}" step="0.001"
                                            oninput="if(value.length>5)value=value.slice(0,5)" min="0"></td>
                                </tr>
                                <input type="hidden" id="count" name="count" value="{{ $loop->count }}">
                            @endforeach
                        </table>
                    </div>
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                    <input type="submit" id="add" name="add" class="btn btn-lg btn-primary"
                        value="{!! __('monthlyPRpageLang.add') !!}">
                </form>
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
            </div>
        </div>
    </div>
@endsection
