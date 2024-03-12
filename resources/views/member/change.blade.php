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
                <h3>{!! __('loginPageLang.permission_details') !!}</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-bordered" id="test">
                        <tr>
                            <th class="table-warning" colspan="3">
                                {{ __('loginPageLang.current_permission') . ' : ' . Auth::user()->priority }}</th>
                        </tr>
                        <tr>
                            <th class="w-25">{!! __('loginPageLang.priority') !!}</th>
                            <th colspan="2">{!! __('loginPageLang.functionality') !!}</th>
                        </tr>
                        {{-- 基礎資料 --}}
                        <tr>
                            <td rowspan="20">1</td>
                            <td rowspan="3" class="table-success">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-info-circle align-middle me-2" viewBox="0 0 16 16">
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
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-upc align-middle me-2" viewBox="0 0 16 16">
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
                        {{-- ----------------------------------------------------------------------------------------- --}}
                        <tr class="table-secondary">
                            <td colspan="2"></td>
                        </tr>
                        {{-- ----------------------------------------------------------------------------------------- --}}
                        {{-- 入庫 --}}
                        <tr>
                            <td rowspan="5" class="table-success">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-inboxes-fill align-middle me-2" viewBox="0 0 16 16">
                                    <path
                                        d="M4.98 1a.5.5 0 0 0-.39.188L1.54 5H6a.5.5 0 0 1 .5.5 1.5 1.5 0 0 0 3 0A.5.5 0 0 1 10 5h4.46l-3.05-3.812A.5.5 0 0 0 11.02 1H4.98zM3.81.563A1.5 1.5 0 0 1 4.98 0h6.04a1.5 1.5 0 0 1 1.17.563l3.7 4.625a.5.5 0 0 1 .106.374l-.39 3.124A1.5 1.5 0 0 1 14.117 10H1.883A1.5 1.5 0 0 1 .394 8.686l-.39-3.124a.5.5 0 0 1 .106-.374L3.81.563zM.125 11.17A.5.5 0 0 1 .5 11H6a.5.5 0 0 1 .5.5 1.5 1.5 0 0 0 3 0 .5.5 0 0 1 .5-.5h5.5a.5.5 0 0 1 .496.562l-.39 3.124A1.5 1.5 0 0 1 14.117 16H1.883a1.5 1.5 0 0 1-1.489-1.314l-.39-3.124a.5.5 0 0 1 .121-.393z" />
                                </svg>
                                <span class="align-middle">{!! __('templateWords.inbound') !!}</span>
                            </td>
                            <td class="table-success"><span>{!! __('inboundpageLang.new') !!}</span></td>
                        </tr>
                        <tr>
                            <td class="table-success"><span>{!! __('inboundpageLang.search') !!}</span></td>
                        </tr>
                        <tr>
                            <td class="table-success"><span>{!! __('inboundpageLang.searchstock') !!}</span></td>
                        </tr>
                        <tr>
                            <td class="table-success"><span>{!! __('inboundpageLang.locationchange') !!}</span></td>
                        </tr>
                        <tr>
                            <td class="table-success"><span>{!! __('inboundpageLang.stockupload') !!}</span></td>
                        </tr>
                        {{-- ----------------------------------------------------------------------------------------- --}}
                        <tr class="table-secondary">
                            <td colspan="2"></td>
                        </tr>
                        {{-- ----------------------------------------------------------------------------------------- --}}
                        {{-- 出庫 --}}
                        <tr>
                            <td rowspan="6" class="table-success">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-inboxes align-middle me-2" viewBox="0 0 16 16">
                                    <path
                                        d="M4.98 1a.5.5 0 0 0-.39.188L1.54 5H6a.5.5 0 0 1 .5.5 1.5 1.5 0 0 0 3 0A.5.5 0 0 1 10 5h4.46l-3.05-3.812A.5.5 0 0 0 11.02 1H4.98zm9.954 5H10.45a2.5 2.5 0 0 1-4.9 0H1.066l.32 2.562A.5.5 0 0 0 1.884 9h12.234a.5.5 0 0 0 .496-.438L14.933 6zM3.809.563A1.5 1.5 0 0 1 4.981 0h6.038a1.5 1.5 0 0 1 1.172.563l3.7 4.625a.5.5 0 0 1 .105.374l-.39 3.124A1.5 1.5 0 0 1 14.117 10H1.883A1.5 1.5 0 0 1 .394 8.686l-.39-3.124a.5.5 0 0 1 .106-.374L3.81.563zM.125 11.17A.5.5 0 0 1 .5 11H6a.5.5 0 0 1 .5.5 1.5 1.5 0 0 0 3 0 .5.5 0 0 1 .5-.5h5.5a.5.5 0 0 1 .496.562l-.39 3.124A1.5 1.5 0 0 1 14.117 16H1.883a1.5 1.5 0 0 1-1.489-1.314l-.39-3.124a.5.5 0 0 1 .121-.393zm.941.83.32 2.562a.5.5 0 0 0 .497.438h12.234a.5.5 0 0 0 .496-.438l.32-2.562H10.45a2.5 2.5 0 0 1-4.9 0H1.066z" />
                                </svg>
                                <span class="align-middle">{!! __('templateWords.outbound') !!}</span>
                            </td>
                            <td class="table-success"><span>{!! __('outboundpageLang.pick') !!}</span></td>
                        </tr>
                        <tr>
                            <td class="table-success"><span>{!! __('outboundpageLang.picklist') !!}</span></td>
                        </tr>
                        <tr>
                            <td class="table-success"><span>{!! __('outboundpageLang.pickrecord') !!}</span></td>
                        </tr>
                        <tr>
                            <td class="table-success"><span>{!! __('outboundpageLang.back') !!}</span></td>
                        </tr>
                        <tr>
                            <td class="table-success"><span>{!! __('outboundpageLang.backlist') !!}</span></td>
                        </tr>
                        <tr>
                            <td class="table-success"><span>{!! __('outboundpageLang.backrecord') !!}</span></td>
                        </tr>
                        {{-- ----------------------------------------------------------------------------------------- --}}
                        <tr class="table-secondary">
                            <td colspan="2"></td>
                        </tr>
                        {{-- ----------------------------------------------------------------------------------------- --}}
                        {{-- 月請購 --}}
                        <tr>
                            <td rowspan="8" class="table-success">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                    class="bi bi-inboxes align-middle me-2" viewBox="0 0 16 16">
                                    <path
                                        d="M4.98 1a.5.5 0 0 0-.39.188L1.54 5H6a.5.5 0 0 1 .5.5 1.5 1.5 0 0 0 3 0A.5.5 0 0 1 10 5h4.46l-3.05-3.812A.5.5 0 0 0 11.02 1H4.98zm9.954 5H10.45a2.5 2.5 0 0 1-4.9 0H1.066l.32 2.562A.5.5 0 0 0 1.884 9h12.234a.5.5 0 0 0 .496-.438L14.933 6zM3.809.563A1.5 1.5 0 0 1 4.981 0h6.038a1.5 1.5 0 0 1 1.172.563l3.7 4.625a.5.5 0 0 1 .105.374l-.39 3.124A1.5 1.5 0 0 1 14.117 10H1.883A1.5 1.5 0 0 1 .394 8.686l-.39-3.124a.5.5 0 0 1 .106-.374L3.81.563zM.125 11.17A.5.5 0 0 1 .5 11H6a.5.5 0 0 1 .5.5 1.5 1.5 0 0 0 3 0 .5.5 0 0 1 .5-.5h5.5a.5.5 0 0 1 .496.562l-.39 3.124A1.5 1.5 0 0 1 14.117 16H1.883a1.5 1.5 0 0 1-1.489-1.314l-.39-3.124a.5.5 0 0 1 .121-.393zm.941.83.32 2.562a.5.5 0 0 0 .497.438h12.234a.5.5 0 0 0 .496-.438l.32-2.562H10.45a2.5 2.5 0 0 1-4.9 0H1.066z" />
                                </svg>
                                <span class="align-middle">{!! __('templateWords.outbound') !!}</span>
                            </td>
                            <td class="table-success"><span>{!! __('outboundpageLang.pick') !!}</span></td>
                        </tr>
                        <tr>
                            <td class="table-success"><span>{!! __('outboundpageLang.picklist') !!}</span></td>
                        </tr>
                        <tr>
                            <td class="table-success"><span>{!! __('outboundpageLang.pickrecord') !!}</span></td>
                        </tr>
                        <tr>
                            <td class="table-success"><span>{!! __('outboundpageLang.back') !!}</span></td>
                        </tr>
                        <tr>
                            <td class="table-success"><span>{!! __('outboundpageLang.backlist') !!}</span></td>
                        </tr>
                        <tr>
                            <td class="table-success"><span>{!! __('outboundpageLang.backrecord') !!}</span></td>
                        </tr>

                        <tr class="table-secondary">
                            <td colspan="3"></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
