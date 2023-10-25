@extends('layouts.adminTemplate')
@section('css')
    <style>
        .scrollableWithoutScrollbar::-webkit-scrollbar-track {
            -webkit-box-shadow: 0 0 1px hsla(0, 0%, 100%, .5);
            border-radius: 4px;
            background-color: #F5F5F5;
        }

        .scrollableWithoutScrollbar::-webkit-scrollbar {
            height: 4px;
            -webkit-appearance: none;
        }

        .scrollableWithoutScrollbar::-webkit-scrollbar-thumb {
            border-radius: 4px;
            -webkit-box-shadow: 0 0 1px hsla(0, 0%, 100%, .5);
            background-color: rgba(0, 0, 0, 0.3);
        }
    </style>
@endsection

@section('js')
    <script src="{{ asset('js/basic/material.js?v=') . env('APP_VERSION') }}"></script>
    <!--for this page's sepcified js -->
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">

            <h2 class="col-auto">{!! __('basicInfoLang.basicInfo') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                <vue-bread-crumb></vue-bread-crumb>
            </div>
        </div>
        <button type="hidden" id="QueryFlag" name="QueryFlag" value="Posting" style="display: none;"></button>
        <div class="card">
            <div class="card-body">
                <form id="materialsearch" method="POST">
                    @csrf
                    <input type="submit" id="delete" name="delete" class="btn btn-lg btn-danger"
                        value="{!! __('basicInfoLang.delete') !!}">
                    &nbsp;
                    <input type="submit" id="change" name="change" class="btn btn-lg btn-primary"
                        value="{!! __('basicInfoLang.change') !!}">
                    &nbsp;
                    <input type="submit" id="download" name="download" class="btn btn-lg btn-success"
                        value="{!! __('basicInfoLang.download') !!}">
                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                    <basic-info-table></basic-info-table>
                </form>
            </div>
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
        </div>
        <div class="row justify-content-center">
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

            <div class="card w-75">
                <div class="card-header">
                    <h3>{!! __('basicInfoLang.upload') !!}</h3>
                </div>
                <div class="row justify-content-center">
                    <div class="card-body">
                        <div class=" w-100">
                            <form method="post" enctype="multipart/form-data" action="{{ route('basic.uploadmaterial') }}">
                                @csrf
                                <div class="row w-100 justify-content-center mb-3">
                                    <div class="col col-auto ">
                                        <a href="{{ asset('download/MaterialExample.xlsx') }}"
                                            download>{!! __('basicInfoLang.exampleExcel') !!}</a>
                                    </div>

                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                                    <label class="col col-auto form-label p-0 m-0">{!! __('basicInfoLang.plz_upload') !!}</label>
                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                                    <div class="col col-auto">
                                        <input class="form-control @error('select_file') is-invalid @enderror"
                                            type="file" name="select_file" />
                                        @error('select_file')
                                            <span class="invalid-feedback d-block" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                                    <div class="row w-100 justify-content-center">
                                        <div class="col col-auto">
                                            <input type="submit" name="upload" class="btn btn-lg btn-primary"
                                                value="{!! __('basicInfoLang.upload1') !!}">
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
