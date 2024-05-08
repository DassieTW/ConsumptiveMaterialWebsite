@extends('layouts.adminTemplate')
@section('css')
    <style>
        /* for single line table with over-flow , SAP style as asked */
        table {
            /* table-layout: fixed; */
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
    <script src="{{ asset('js/login/numberchange.js?v=') . env('APP_VERSION') }}"></script>
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.userManage') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                {{-- <vue-bread-crumb></vue-bread-crumb> --}}
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h3>{!! __('templateWords.PInfo') !!}</h3>
        </div>
        <div class="card-body">
            <form id="searchnumber" method="POST">
                @csrf
                <input type="submit" id="new" name="new" class="btn btn-lg btn-primary"
                    value="{!! __('loginPageLang.new') !!}">
                &emsp13;
                <input type="submit" id="delete" name="delete" class="btn btn-lg btn-primary"
                    value="{!! __('loginPageLang.delete') !!}">
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>{!! __('loginPageLang.jobnumber') !!}</th>
                                <th>{!! __('loginPageLang.name') !!}</th>
                                <th>{!! __('loginPageLang.dep') !!}</th>
                            </tr>
                            <tr>
                                <td class="mx-0 px-0">{!! __('loginPageLang.new') !!}:</td>
                                <td><input style="width:150px" type="text" id="newnumber" name="newnumber"
                                        class="form-control form-control-lg">
                                </td>
                                <td><input style="width:150px" type="text" id="newname" name="newname"
                                        class="form-control form-control-lg">
                                </td>
                                <td><input style="width:150px" type="text" id="newdep" name="newdep"
                                        class="form-control form-control-lg">
                                </td>
                            </tr>
                        </thead>
                        @foreach ($data as $data)
                            <tr>
                                <td><input class="innumber" type="checkbox" id="innumber" name="innumber"
                                        style="width:20px;height:20px;" value="{{ $loop->index }}"></td>
                                <td><input type="hidden" id="number{{ $loop->index }}" name="number{{ $loop->index }}"
                                        value="{{ $data->工號 }}">{{ $data->工號 }}</td>
                                <td><input type="hidden" id="name{{ $loop->index }}" name="name{{ $loop->index }}"
                                        value="{{ $data->姓名 }}">{{ $data->姓名 }}</td>
                                <td><input type="hidden" id="dep{{ $loop->index }}" name="dep{{ $loop->index }}"
                                        value="{{ $data->部門 }}">{{ $data->部門 }}</td>

                            </tr>
                            <input type="hidden" id="count" name="count" value="{{ $loop->count }}">
                        @endforeach

                    </table>
                </div>

                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            </form>

        </div>
    </div>
@endsection
