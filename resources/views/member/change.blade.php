@extends('layouts.adminTemplate')
@section('css')
    <style>
        td,
        th {
            text-align: center;
            vertical-align: middle;
        }

        #siteListPicker::-webkit-scrollbar-track {
            -webkit-box-shadow: 0 0 1px hsla(0, 0%, 100%, .5);
            border-radius: 4px;
            background-color: #F5F5F5;
        }

        #siteListPicker::-webkit-scrollbar {
            width: 4px;
            -webkit-appearance: none;
        }

        #siteListPicker::-webkit-scrollbar-thumb {
            border-radius: 4px;
            -webkit-box-shadow: 0 0 1px hsla(0, 0%, 100%, .5);
            background-color: rgba(0, 0, 0, 0.3);
        }
    </style>
@endsection

@section('js')
    <script src="{{ asset('/js/login/change.js?v=') . env('APP_VERSION') }}"></script>
    <!--for this page's sepcified js -->
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
    <div class="row justify-content-center">
        <div class="card w-100">
            <div class="card-header">
                <h3>{!! __('loginPageLang.priority_details') !!}</h3>
            </div>
            <div class="row justify-content-center">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered" id="test">
                            <tr>
                                <th class="w-25">{!! __('loginPageLang.priority') !!}</th>
                                <th colspan="2">{!! __('loginPageLang.functionality') !!}</th>
                            </tr>
                            {{-- 基礎資料 --}}
                            <tr>
                                <td rowspan="7">1</td>
                                <td rowspan="3" class="table-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-info-circle align-middle me-2" viewBox="0 0 16 16">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                        <path
                                            d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                                    </svg>
                                    <span class="align-middle">{!! __('templateWords.basicInfo') !!}</span>
                                </td>
                                <td class="table-success"><span>{!! __('basicInfoLang.basicInfo') !!}</span></td>
                            </tr>
                            <tr>
                                <td class="table-success"><span>{!! __('basicInfoLang.matsInfo') !!}</span></td>
                            </tr>
                            <tr>
                                <td class="table-success"><span>{!! __('basicInfoLang.newMats') !!}</span></td>
                            </tr>
                            {{-- ----------------------------------------------------------------------------------------- --}}
                            <tr class="table-secondary">
                                <td colspan="2"></td>
                            </tr>
                            {{-- ----------------------------------------------------------------------------------------- --}}
                            {{-- 條碼 --}}
                            <tr>
                                <td rowspan="3" class="table-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        fill="currentColor" class="bi bi-upc align-middle me-2" viewBox="0 0 16 16">
                                        <path
                                            d="M3 4.5a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-7zm3 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7z" />
                                    </svg>
                                    <span class="align-middle">{!! __('templateWords.barcode_gen') !!}</span>
                                </td>
                                <td class="table-success"><span>{!! __('templateWords.barcode_generator') !!}</span></td>
                            </tr>
                            <tr>
                                <td class="table-success"><span>{!! __('templateWords.isnBarcode') !!}</span></td>
                            </tr>
                            <tr>
                                <td class="table-success"><span>{!! __('templateWords.locBarcode') !!}</span></td>
                            </tr>
                            <tr class="table-secondary">
                                <td colspan="3"></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
