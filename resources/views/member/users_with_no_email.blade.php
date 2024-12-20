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

        input[type=checkbox] {
            accent-color: #dc3545;
            cursor: pointer;
            vertical-align: middle;
        }
    </style>
@endsection

@section('js')
    <!--for this page's sepcified js -->
    <script src="{{ asset('js/login/non_OA_user_change.js?v=') . env('APP_VERSION') }}"></script>
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
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="m-1 p-1 text-center">
                                    <button type="submit" id="delete" name="delete"
                                        class="col col-auto btn btn-sm btn-danger" value="{!! __('loginPageLang.delete') !!}">
                                        <i class="bi bi-trash3-fill fs-4"></i>
                                    </button>
                                </th>
                                <th class="m-1 p-1 text-center">{!! __('loginPageLang.jobnumber') !!}</th>
                                <th class="m-1 p-1 text-center">{!! __('loginPageLang.name') !!}</th>
                                <th class="m-1 p-1 text-center">{!! __('loginPageLang.dep') !!}</th>
                            </tr>
                            <tr>
                                <td class="m-0 p-0 col col-auto text-center align-middle">{!! __('loginPageLang.new') !!}:</td>
                                <td class="col col-3"><input type="text" id="newnumber" name="newnumber"
                                        class="form-control form-control-lg">
                                </td>
                                <td class="col col-3"><input type="text" id="newname" name="newname"
                                        class="form-control form-control-lg">
                                </td>
                                <td class="row">
                                    <div class="col col-10">
                                        <input type="text" id="newdep" name="newdep"
                                            class="form-control form-control-lg">
                                    </div>
                                    <div class="col col-auto m-0 p-0">
                                        <button type="submit" id="new" name="new" class="btn btn-sm btn-success"
                                            value="{!! __('loginPageLang.new') !!}">
                                            <i class="bi bi-person-plus-fill fs-4"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </thead>
                        @foreach ($data as $data)
                            <tr>
                                <td class="m-0 p-0 col col-auto text-center align-middle"><input class="innumber"
                                        type="checkbox" id="innumber" name="innumber" style="width:20px;height:20px;"
                                        value="{{ $loop->index }}"></td>
                                <td class="col col-3"><input type="hidden" id="number{{ $loop->index }}"
                                        name="number{{ $loop->index }}" value="{{ $data->工號 }}">{{ $data->工號 }}
                                </td>
                                <td class="col col-3"><input type="hidden" id="name{{ $loop->index }}"
                                        name="name{{ $loop->index }}" value="{{ $data->姓名 }}">{{ $data->姓名 }}</td>
                                <td class="col col-6"><input type="hidden" id="dep{{ $loop->index }}"
                                        name="dep{{ $loop->index }}" value="{{ $data->部門 }}">{{ $data->部門 }}
                                </td>

                            </tr>
                            <input type="hidden" id="count" name="count" value="{{ $loop->count }}">
                        @endforeach
                    </table>
                </div>
            </form>
            <div class="w-100" style="height: 2ch;"></div><!-- </div>breaks cols to a new line-->
        </div>
    </div>
@endsection
