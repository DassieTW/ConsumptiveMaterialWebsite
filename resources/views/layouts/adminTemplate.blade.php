<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    {{--
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords"
        content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">
    --}}

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="../admin/img/icons/icon-48x48.png" />

    <link rel="stylesheet" type="text/css" href="{{ asset('/css/bootstrap.min.css?v=') . env('APP_VERSION') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/app.css?v=') . env('APP_VERSION') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . env('APP_VERSION') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('/css/jquery.loadingModal.min.css?v=') . env('APP_VERSION') }}">
    <style>
        #UserDropDown::-webkit-scrollbar-track {
            -webkit-box-shadow: 0 0 1px hsla(0, 0%, 100%, .5);
            border-radius: 4px;
            background-color: #F5F5F5;
        }

        #UserDropDown::-webkit-scrollbar {
            width: 4px;
            -webkit-appearance: none;
        }

        #UserDropDown::-webkit-scrollbar-thumb {
            border-radius: 4px;
            -webkit-box-shadow: 0 0 1px hsla(0, 0%, 100%, .5);
            background-color: rgba(0, 0, 0, 0.3);
        }
    </style>
    @yield('css')
    {{-- local lang for js --}}
    <script src="{{ asset('/messages.js?v=') . env('APP_VERSION') }}"></script>
    {{-- for date picker's js --}}
    <script type="text/javascript" href="{{ asset('/js/moment.min.js?v=') . env('APP_VERSION') }}"></script>
    {{-- get parameters from url, js version --}}
    {{-- usage example : var temp = getUrlParameter('parameterName'); --}}
    <script type="module" src="{{ asset('/js/getUrlParameter.js?v=') . env('APP_VERSION') }}"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <script>
        if (window.history.replaceState) {
            // java script to prvent "confirm form resubmission" dialog
            // 避免重新整理這個頁面時跳出要你重新提交表單的對話框
            // (避免重新提交表單)
            window.history.replaceState(null, null, window.location.href);
        } // if
        /**

                                                             __----~~~~~~~~~~~------___
                                            .  .   ~~//====......          __--~ ~~
                            -.            \_|//     |||\\  ~~~~~~::::... /~
                         ___-==_       _-~o~  \/    |||  \\            _/~~-
                 __---~~~.==~||\=_    -_--~/_-~|-   |\\   \\        _/~
             _-~~     .=~    |  \\-_    '-~7  /-   /  ||    \      /
           .~       .~       |   \\ -_    /  /-   /   ||      \   /
          /  ____  /         |     \\ ~-_/  /|- _/   .||       \ /
          |~~    ~~|--~~~~--_ \     ~==-/   | \~--===~~        .\
                   '         ~-|      /|    |-~\~~       __--~~
                               |-~~-_/ |    |   ~\_   _-~            /\
                                    /  \     \__   \/~                \__
                                _--~ _/ | .-~~____--~-/                  ~~==.
                               ((->/~   '.|||' -_|    ~~-/ ,              . _||
                                          -_     ~\      ~~---l__i__i__i--~~_/
                                          _-~-__   ~)  \--______________--~~
                                        //.-~~~-~_--~- |-------~~~~~~~~
                                               //.-~~~--\
                                        神獸保佑，程式碼沒Bug!

        */
    </script>
</head>

<body>
    <div class="wrapper">
        <nav id="sidebar" class="sidebar js-sidebar">
            <div class="sidebar-content js-simplebar" data-simplebar="init">
                <div class="simplebar-wrapper" style="margin: 0px;">
                    <div class="simplebar-height-auto-observer-wrapper">
                        <div class="simplebar-height-auto-observer"></div>
                    </div>
                    <div class="simplebar-mask">
                        <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                            <div class="simplebar-content-wrapper" style="height: 100%; overflow: hidden scroll;">
                                <div class="simplebar-content" style="padding: 0px;">
                                    <a class="sidebar-brand" href="{{ url('/') }}">
                                        <span class="sidebar-brand-text align-middle">
                                            {!! __('templateWords.websiteName') !!}
                                            {{-- <sup><small class="badge bg-primary text-uppercase">Pro</small></sup> --}}
                                        </span>
                                        <svg class="sidebar-brand-icon align-middle" width="32px" height="32px"
                                            viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="1.5"
                                            stroke-linecap="square" stroke-linejoin="miter" color="#FFFFFF"
                                            style="margin-left: -3px">
                                            <path d="M12 4L20 8.00004L12 12L4 8.00004L12 4Z"></path>
                                            <path d="M20 12L12 16L4 12"></path>
                                            <path d="M20 16L12 20L4 16"></path>
                                        </svg>
                                    </a>

                                    <ul class="sidebar-nav">
                                        {{-- <li class="sidebar-header">
                                            Pages
                                        </li> --}}
                                        @can('editNews', App\Models\Bulletin::class)
                                            <li class="sidebar-item {{ isActiveRoute(['home', 'editNews']) }}">
                                                <a data-bs-target="#editNews" data-bs-toggle="collapse"
                                                    class="sidebar-link collapsed" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-sliders align-middle me-2"
                                                        viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd"
                                                            d="M11.5 2a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM9.05 3a2.5 2.5 0 0 1 4.9 0H16v1h-2.05a2.5 2.5 0 0 1-4.9 0H0V3h9.05zM4.5 7a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM2.05 8a2.5 2.5 0 0 1 4.9 0H16v1H6.95a2.5 2.5 0 0 1-4.9 0H0V8h2.05zm9.45 4a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zm-2.45 1a2.5 2.5 0 0 1 4.9 0H16v1h-2.05a2.5 2.5 0 0 1-4.9 0H0v-1h9.05z" />
                                                    </svg>
                                                    <span class="align-middle">{!! __('templateWords.dashboard') !!}</span>
                                                </a>
                                                <ul id="editNews" class="sidebar-dropdown list-unstyled collapse"
                                                    data-bs-parent="#sidebar" style="">
                                                    <li class="sidebar-item {{ isActiveRoute(['home']) }}">
                                                        <a class="sidebar-link" href="{{ url('home') }}">
                                                            {!! __('templateWords.News') !!}</a>
                                                    </li>
                                                    <li
                                                        class="sidebar-item {{ isActiveRoute(['editNewsBoard', 'editNews']) }}">
                                                        <a class="sidebar-link" href="{{ url('editNews') }}">
                                                            {!! __('templateWords.editNews') !!}</a>
                                                    </li>
                                                </ul>
                                            </li>
                                        @else
                                            <li class="sidebar-item {{ isActiveRoute(['home']) }}">
                                                <a class="sidebar-link" href="{{ url('home') }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-sliders align-middle me-2"
                                                        viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd"
                                                            d="M11.5 2a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM9.05 3a2.5 2.5 0 0 1 4.9 0H16v1h-2.05a2.5 2.5 0 0 1-4.9 0H0V3h9.05zM4.5 7a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zM2.05 8a2.5 2.5 0 0 1 4.9 0H16v1H6.95a2.5 2.5 0 0 1-4.9 0H0V8h2.05zm9.45 4a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3zm-2.45 1a2.5 2.5 0 0 1 4.9 0H16v1h-2.05a2.5 2.5 0 0 1-4.9 0H0v-1h9.05z" />
                                                    </svg>
                                                    <span class="align-middle">{!! __('templateWords.dashboard') !!}</span>
                                                    {{-- <span class="sidebar-badge badge bg-primary">Pro</span> --}}
                                                </a>
                                            </li>
                                        @endcan

                                        @can('viewBasicInfo', App\Models\ConsumptiveMaterial::class)
                                            <li class="sidebar-item {{ isActiveRoute(['basic/', 'basic.index']) }}">
                                                <a data-bs-target="#basicInfo" data-bs-toggle="collapse"
                                                    class="sidebar-link collapsed" aria-expanded="false"
                                                    id="myCollapsible">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-info-circle align-middle me-2"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                                        <path
                                                            d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0z" />
                                                    </svg>
                                                    <span class="align-middle">{!! __('templateWords.basicInfo') !!}</span>
                                                </a>
                                                <ul id="basicInfo" class="sidebar-dropdown list-unstyled collapse"
                                                    data-bs-parent="#sidebar" style="">
                                                    <li
                                                        class="sidebar-item {{ isActiveRoute(['basic.index', 'basic/uploadbasic', 'basic/inf', 'basic/insertuploadbasic']) }}">
                                                        <a class="sidebar-link" href="{{ url('basic') }}">
                                                            {!! __('basicInfoLang.basicInfo') !!}</a>
                                                    </li>
                                                    <li
                                                        class="sidebar-item {{ isActiveRoute(['basic/new', 'basic.uploadmaterial']) }}">
                                                        <a class="sidebar-link" href="{{ url('basic/new') }}">
                                                            {!! __('basicInfoLang.newMats') !!}</a>
                                                    </li>
                                                    <li class="sidebar-item {{ isActiveRoute(['basic/material']) }}">
                                                        <a class="sidebar-link" href="{{ url('basic/material') }}">
                                                            {!! __('basicInfoLang.matsInfo') !!}</a>
                                                    </li>

                                                </ul>
                                            </li>
                                        @endcan

                                        <li class="sidebar-item {{ isActiveRoute(['barcode/', 'barcode.index']) }}">
                                            <a data-bs-target="#barcodePages" data-bs-toggle="collapse"
                                                class="sidebar-link collapsed" aria-expanded="false">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-upc align-middle me-2"
                                                    viewBox="0 0 16 16">
                                                    <path
                                                        d="M3 4.5a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7zm2 0a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-7zm3 0a.5.5 0 0 1 1 0v7a.5.5 0 0 1-1 0v-7z" />
                                                </svg>
                                                <span class="align-middle">{!! __('templateWords.barcode_gen') !!}</span>
                                            </a>
                                            <ul id="barcodePages" class="sidebar-dropdown list-unstyled collapse"
                                                data-bs-parent="#sidebar" style="">
                                                <li class="sidebar-item {{ isActiveRoute(['barcode.index']) }}">
                                                    <a class="sidebar-link" href="{{ url('barcode') }}">
                                                        {!! __('templateWords.barcode_generator') !!}</a>
                                                </li>
                                                <li class="sidebar-item {{ isActiveRoute(['barcode/isn_search']) }}">
                                                    <a class="sidebar-link" href="{{ url('barcode/isn_search') }}">
                                                        {!! __('templateWords.isnBarcode') !!}</a>
                                                </li>
                                                <li class="sidebar-item {{ isActiveRoute(['barcode/loc_search']) }}">
                                                    <a class="sidebar-link" href="{{ url('barcode/loc_search') }}">
                                                        {!! __('templateWords.locBarcode') !!}</a>
                                                </li>
                                            </ul>
                                        </li>

                                        @can('viewInbound', App\Models\Inbound::class)
                                            <li class="sidebar-item {{ isActiveRoute(['inbound/', 'inbound.index']) }}">
                                                <a data-bs-target="#inbound" data-bs-toggle="collapse"
                                                    class="sidebar-link collapsed" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-inboxes-fill align-middle me-2"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M4.98 1a.5.5 0 0 0-.39.188L1.54 5H6a.5.5 0 0 1 .5.5 1.5 1.5 0 0 0 3 0A.5.5 0 0 1 10 5h4.46l-3.05-3.812A.5.5 0 0 0 11.02 1H4.98zM3.81.563A1.5 1.5 0 0 1 4.98 0h6.04a1.5 1.5 0 0 1 1.17.563l3.7 4.625a.5.5 0 0 1 .106.374l-.39 3.124A1.5 1.5 0 0 1 14.117 10H1.883A1.5 1.5 0 0 1 .394 8.686l-.39-3.124a.5.5 0 0 1 .106-.374L3.81.563zM.125 11.17A.5.5 0 0 1 .5 11H6a.5.5 0 0 1 .5.5 1.5 1.5 0 0 0 3 0 .5.5 0 0 1 .5-.5h5.5a.5.5 0 0 1 .496.562l-.39 3.124A1.5 1.5 0 0 1 14.117 16H1.883a1.5 1.5 0 0 1-1.489-1.314l-.39-3.124a.5.5 0 0 1 .121-.393z" />
                                                    </svg>
                                                    <span class="align-middle">{!! __('templateWords.inbound') !!}</span>
                                                </a>
                                                <ul id="inbound" class="sidebar-dropdown list-unstyled collapse"
                                                    data-bs-parent="#sidebar" style="">
                                                    <li class="sidebar-item {{ isActiveRoute(['inbound/add']) }}">
                                                        <a class="sidebar-link" href="{{ route('inbound.add') }}">
                                                            {!! __('inboundpageLang.new') !!}
                                                        </a>
                                                    </li>
                                                    <li
                                                        class="sidebar-item {{ isActiveRoute(['inbound.search', 'inbound/inquire']) }}">
                                                        <a class="sidebar-link" href="{{ route('inbound.search') }}">
                                                            {!! __('inboundpageLang.search') !!}
                                                        </a>
                                                    </li>
                                                    <li class="sidebar-item {{ isActiveRoute(['inbound/searchstock']) }}">
                                                        <a class="sidebar-link"
                                                            href="{{ route('inbound.searchstock') }}">
                                                            {!! __('inboundpageLang.searchstock') !!}
                                                        </a>
                                                    </li>
                                                    <li
                                                        class="sidebar-item {{ isActiveRoute(['inbound/positionchange', 'inbound/change']) }}">
                                                        <a class="sidebar-link"
                                                            href="{{ route('inbound.positionchange') }}">
                                                            {!! __('inboundpageLang.locationchange') !!}
                                                        </a>
                                                    </li>
                                                    <li class="sidebar-item {{ isActiveRoute(['inbound/upload']) }}">
                                                        <a class="sidebar-link" href="{{ route('inbound.upload') }}">
                                                            {!! __('inboundpageLang.stockupload') !!}
                                                        </a>
                                                    </li>

                                                </ul>
                                            </li>
                                        @endcan

                                        @can('viewOutbound', App\Models\Outbound::class)
                                            <li class="sidebar-item {{ isActiveRoute(['outbound/', 'outbound.index']) }}">
                                                <a data-bs-target="#outbound" data-bs-toggle="collapse"
                                                    class="sidebar-link collapsed" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-inboxes align-middle me-2"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M4.98 1a.5.5 0 0 0-.39.188L1.54 5H6a.5.5 0 0 1 .5.5 1.5 1.5 0 0 0 3 0A.5.5 0 0 1 10 5h4.46l-3.05-3.812A.5.5 0 0 0 11.02 1H4.98zm9.954 5H10.45a2.5 2.5 0 0 1-4.9 0H1.066l.32 2.562A.5.5 0 0 0 1.884 9h12.234a.5.5 0 0 0 .496-.438L14.933 6zM3.809.563A1.5 1.5 0 0 1 4.981 0h6.038a1.5 1.5 0 0 1 1.172.563l3.7 4.625a.5.5 0 0 1 .105.374l-.39 3.124A1.5 1.5 0 0 1 14.117 10H1.883A1.5 1.5 0 0 1 .394 8.686l-.39-3.124a.5.5 0 0 1 .106-.374L3.81.563zM.125 11.17A.5.5 0 0 1 .5 11H6a.5.5 0 0 1 .5.5 1.5 1.5 0 0 0 3 0 .5.5 0 0 1 .5-.5h5.5a.5.5 0 0 1 .496.562l-.39 3.124A1.5 1.5 0 0 1 14.117 16H1.883a1.5 1.5 0 0 1-1.489-1.314l-.39-3.124a.5.5 0 0 1 .121-.393zm.941.83.32 2.562a.5.5 0 0 0 .497.438h12.234a.5.5 0 0 0 .496-.438l.32-2.562H10.45a2.5 2.5 0 0 1-4.9 0H1.066z" />
                                                    </svg>
                                                    <span class="align-middle">{!! __('templateWords.outbound') !!}</span>
                                                    {{-- <span class="sidebar-badge badge bg-primary">Pro</span> --}}
                                                </a>
                                                <ul id="outbound" class="sidebar-dropdown list-unstyled collapse"
                                                    data-bs-parent="#sidebar" style="">
                                                    @can('outboundPickup', App\Models\Outbound::class)
                                                        <li
                                                            class="sidebar-item {{ isActiveRoute(['outbound.pick', 'outbound/pickaddok']) }}">
                                                            <a class="sidebar-link" href="{{ route('outbound.pick') }}">
                                                                {!! __('outboundpageLang.pick') !!}
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('outboundPickupSerialNum', App\Models\Outbound::class)
                                                        <li class="sidebar-item {{ isActiveRoute(['outbound/picklist']) }}">
                                                            <a class="sidebar-link"
                                                                href="{{ route('outbound.picklistpage') }}">
                                                                {!! __('outboundpageLang.picklist') !!}
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('outboundPickupRecord', App\Models\Outbound::class)
                                                        <li class="sidebar-item {{ isActiveRoute(['outbound/pickrecord']) }}">
                                                            <a class="sidebar-link"
                                                                href="{{ route('outbound.pickrecord') }}">
                                                                {!! __('outboundpageLang.pickrecord') !!}
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('outboundReturn', App\Models\Outbound::class)
                                                        <li
                                                            class="sidebar-item {{ isActiveRoute(['outbound.back', 'outbound/backaddok']) }}">
                                                            <a class="sidebar-link" href="{{ route('outbound.back') }}">
                                                                {!! __('outboundpageLang.back') !!}
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('outboundReturnSerialNum', App\Models\Outbound::class)
                                                        <li class="sidebar-item {{ isActiveRoute(['outbound/backlist']) }}">
                                                            <a class="sidebar-link"
                                                                href="{{ route('outbound.backlistpage') }}">
                                                                {!! __('outboundpageLang.backlist') !!}
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('outboundReturnRecord', App\Models\Outbound::class)
                                                        <li class="sidebar-item {{ isActiveRoute(['outbound/backrecord']) }}">
                                                            <a class="sidebar-link"
                                                                href="{{ route('outbound.backrecord') }}">
                                                                {!! __('outboundpageLang.backrecord') !!}
                                                            </a>
                                                        </li>
                                                    @endcan
                                                </ul>
                                            </li>
                                        @endcan

                                        @can('viewMonthlyPR', App\Models\月請購_單耗::class)
                                            <li class="sidebar-item {{ isActiveRoute(['month/', 'month.index']) }}">
                                                <a data-bs-target="#monthly" data-bs-toggle="collapse"
                                                    class="sidebar-link collapsed" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-cart2 align-middle me-2"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l1.25 5h8.22l1.25-5H3.14zM5 13a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0z" />
                                                    </svg>
                                                    <span class="align-middle">{!! __('templateWords.monthly') !!}</span>
                                                    {{-- <span class="sidebar-badge badge bg-primary">Pro</span> --}}
                                                </a>
                                                <ul id="monthly" class="sidebar-dropdown list-unstyled collapse"
                                                    data-bs-parent="#sidebar" style="">
                                                    <li
                                                        class="sidebar-item {{ isActiveRoute(['month/consumeadd', 'month/consumenewok', 'month/uploadconsume', 'month/insertuploadconsume']) }}">
                                                        <a class="sidebar-link" href="{{ route('month.consumeadd') }}">
                                                            {!! __('templateWords.isnConsumeAdd') !!}
                                                        </a>
                                                    </li>
                                                    <li
                                                        class="sidebar-item {{ isActiveRoute(['month.consume', 'month/consumesearch']) }}">
                                                        <a class="sidebar-link" href="{{ route('month.consume') }}">
                                                            {!! __('templateWords.isnConsumeUpdate') !!}
                                                        </a>
                                                    </li>
                                                    <li
                                                        class="sidebar-item {{ isActiveRoute(['month/standadd', 'month/standnewok', 'month/uploadstand', 'month/insertuploadstand']) }}">
                                                        <a class="sidebar-link" href="{{ route('month.standadd') }}">
                                                            {!! __('templateWords.standAdd') !!}
                                                        </a>
                                                    </li>
                                                    <li
                                                        class="sidebar-item {{ isActiveRoute(['month.stand', 'month/standsearch']) }}">
                                                        <a class="sidebar-link" href="{{ route('month.stand') }}">
                                                            {!! __('templateWords.standUpdate') !!}
                                                        </a>
                                                    </li>
                                                    <li
                                                        class="sidebar-item {{ isActiveRoute(['month/importmonth', 'month/uploadmonth', 'month/monthinf']) }}">
                                                        <a class="sidebar-link" href="{{ route('month.importmonth') }}">
                                                            {!! __('templateWords.importMonthlyData') !!}
                                                        </a>
                                                    </li>
                                                    <li class="sidebar-item {{ isActiveRoute(['month/buylist']) }}">
                                                        <a class="sidebar-link" href="{{ route('month.buylist') }}">
                                                            {!! __('monthlyPRpageLang.PR') !!}
                                                        </a>
                                                    </li>
                                                    <li class="sidebar-item {{ isActiveRoute(['month/srm']) }}">
                                                        <a class="sidebar-link" href="{{ route('month.srm') }}">
                                                            {!! __('monthlyPRpageLang.SRM') !!}
                                                        </a>
                                                    </li>
                                                    <li class="sidebar-item {{ isActiveRoute(['month/sxb']) }}">
                                                        <a class="sidebar-link" href="{{ route('month.sxb') }}">
                                                            {!! __('monthlyPRpageLang.SXB_search') !!}
                                                        </a>
                                                    </li>
                                                    <li
                                                        class="sidebar-item {{ isActiveRoute(['month/importnotmonth', 'month/uploadnotmonth', 'month/notmonthinf', 'month/notmonthsearchok', 'month/notmonthaddok']) }}">
                                                        <a class="sidebar-link"
                                                            href="{{ route('month.importnotmonth') }}">
                                                            {!! __('templateWords.importNonMonthlyData') !!}
                                                        </a>
                                                    </li>
                                                    <li class="sidebar-item {{ isActiveRoute(['month/transit']) }}">
                                                        <a class="sidebar-link" href="{{ route('month.transit') }}">
                                                            {!! __('templateWords.on_the_way_search') !!}
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                        @endcan

                                        @can('viewObound', App\Models\O庫::class)
                                            <li class="sidebar-item {{ isActiveRoute(['obound/', 'obound.index']) }}">
                                                <a data-bs-target="#obound" data-bs-toggle="collapse"
                                                    class="sidebar-link collapsed" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor" class="bi bi-box-seam align-middle me-2"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M8.186 1.113a.5.5 0 0 0-.372 0L1.846 3.5l2.404.961L10.404 2l-2.218-.887zm3.564 1.426L5.596 5 8 5.961 14.154 3.5l-2.404-.961zm3.25 1.7-6.5 2.6v7.922l6.5-2.6V4.24zM7.5 14.762V6.838L1 4.239v7.923l6.5 2.6zM7.443.184a1.5 1.5 0 0 1 1.114 0l7.129 2.852A.5.5 0 0 1 16 3.5v8.662a1 1 0 0 1-.629.928l-7.185 2.874a.5.5 0 0 1-.372 0L.63 13.09a1 1 0 0 1-.63-.928V3.5a.5.5 0 0 1 .314-.464L7.443.184z" />
                                                    </svg>
                                                    <span class="align-middle">{!! __('templateWords.obound') !!}</span>
                                                    {{-- <span class="sidebar-badge badge bg-primary">Pro</span> --}}
                                                </a>
                                                <ul id="obound" class="sidebar-dropdown list-unstyled collapse"
                                                    data-bs-parent="#sidebar" style="">
                                                    @can('oboundNewMat', App\Models\O庫::class)
                                                        <li
                                                            class="sidebar-item {{ isActiveRoute(['obound/new', 'obound/uploadmaterial']) }}">
                                                            <a class="sidebar-link" href="{{ route('obound.new') }}">
                                                                {!! __('oboundpageLang.newMats') !!}
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('oboundMatSearch', App\Models\O庫::class)
                                                        <li
                                                            class="sidebar-item {{ isActiveRoute(['obound.material', 'obound/material']) }}">
                                                            <a class="sidebar-link" href="{{ route('obound.material') }}">
                                                                {!! __('oboundpageLang.matsInfo') !!}
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('oboundIn', App\Models\O庫::class)
                                                        <li
                                                            class="sidebar-item {{ isActiveRoute(['obound.inbound', 'obound/inboundnewok']) }}">
                                                            <a class="sidebar-link" href="{{ route('obound.inbound') }}">
                                                                {!! __('oboundpageLang.inbound') !!}
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('oboundInSearch', App\Models\O庫::class)
                                                        <li
                                                            class="sidebar-item {{ isActiveRoute(['obound/inboundsearch']) }}">
                                                            <a class="sidebar-link"
                                                                href="{{ route('obound.inboundsearch') }}">
                                                                {!! __('oboundpageLang.inboundsearch') !!}
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('oboundStockUpload', App\Models\O庫::class)
                                                        <li
                                                            class="sidebar-item {{ isActiveRoute(['obound.upload', 'obound/uploadinventory']) }}">
                                                            <a class="sidebar-link" href="{{ route('obound.upload') }}">
                                                                {!! __('oboundpageLang.stockupload') !!}
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('oboundStockSearch', App\Models\O庫::class)
                                                        <li class="sidebar-item {{ isActiveRoute(['obound/searchstock']) }}">
                                                            <a class="sidebar-link" href="{{ route('obound.searchstock') }}">
                                                                {!! __('oboundpageLang.searchstock') !!}
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('oboundPickup', App\Models\O庫::class)
                                                        <li
                                                            class="sidebar-item {{ isActiveRoute(['obound.pick', 'obound/pickaddok']) }}">
                                                            <a class="sidebar-link" href="{{ route('obound.pick') }}">
                                                                {!! __('oboundpageLang.pick') !!}
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('oboundPickupSerialNum', App\Models\O庫::class)
                                                        <li class="sidebar-item {{ isActiveRoute(['obound/picklist']) }}">
                                                            <a class="sidebar-link"
                                                                href="{{ route('obound.picklistpage') }}">
                                                                {!! __('oboundpageLang.picklist') !!}
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('oboundPickupRecord', App\Models\O庫::class)
                                                        <li class="sidebar-item {{ isActiveRoute(['obound/pickrecord']) }}">
                                                            <a class="sidebar-link" href="{{ route('obound.pickrecord') }}">
                                                                {!! __('oboundpageLang.pickrecord') !!}
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('oboundReturn', App\Models\O庫::class)
                                                        <li
                                                            class="sidebar-item {{ isActiveRoute(['obound.back', 'obound/backaddok']) }}">
                                                            <a class="sidebar-link" href="{{ route('obound.back') }}">
                                                                {!! __('oboundpageLang.back') !!}
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('oboundReturnSerialNum', App\Models\O庫::class)
                                                        <li class="sidebar-item {{ isActiveRoute(['obound/backlist']) }}">
                                                            <a class="sidebar-link"
                                                                href="{{ route('obound.backlistpage') }}">
                                                                {!! __('oboundpageLang.backlist') !!}
                                                            </a>
                                                        </li>
                                                    @endcan
                                                    @can('oboundReturnRecord', App\Models\O庫::class)
                                                        <li class="sidebar-item {{ isActiveRoute(['obound/backrecord']) }}">
                                                            <a class="sidebar-link" href="{{ route('obound.backrecord') }}">
                                                                {!! __('oboundpageLang.backrecord') !!}
                                                            </a>
                                                        </li>
                                                    @endcan
                                                </ul>
                                            </li>
                                        @endcan



                                        <li class="sidebar-item {{ isActiveRoute(['bu/', 'bu.index']) }}">
                                            <a data-bs-target="#bu" data-bs-toggle="collapse"
                                                class="sidebar-link collapsed" aria-expanded="false">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-briefcase align-middle me-2"
                                                    viewBox="0 0 16 16">
                                                    <path
                                                        d="M6.5 1A1.5 1.5 0 0 0 5 2.5V3H1.5A1.5 1.5 0 0 0 0 4.5v8A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-8A1.5 1.5 0 0 0 14.5 3H11v-.5A1.5 1.5 0 0 0 9.5 1h-3zm0 1h3a.5.5 0 0 1 .5.5V3H6v-.5a.5.5 0 0 1 .5-.5zm1.886 6.914L15 7.151V12.5a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5V7.15l6.614 1.764a1.5 1.5 0 0 0 .772 0zM1.5 4h13a.5.5 0 0 1 .5.5v1.616L8.129 7.948a.5.5 0 0 1-.258 0L1 6.116V4.5a.5.5 0 0 1 .5-.5z" />
                                                </svg>

                                                <span class="align-middle">{!! __('templateWords.bu') !!}</span>
                                            </a>
                                            <ul id="bu" class="sidebar-dropdown list-unstyled collapse"
                                                data-bs-parent="#sidebar" style="">
                                                <li class="sidebar-item {{ isActiveRoute(['bu.sluggish']) }}">
                                                    <a id="passiveStockBtn" class="sidebar-link"
                                                        href="{{ route('bu.sluggish') }}">
                                                        {!! __('bupagelang.sluggish') !!}
                                                    </a>
                                                </li>
                                                <li
                                                    class="sidebar-item {{ isActiveRoute(['bu/material', 'bu/sluggishmaterial']) }}">
                                                    <a class="sidebar-link" href="{{ route('bu.material') }}">
                                                        {!! __('bupagelang.factorychange') !!}
                                                    </a>
                                                </li>
                                                <li
                                                    class="sidebar-item {{ isActiveRoute(['bu.searchlist', 'bu/searchlistsub']) }}">
                                                    <a class="sidebar-link" href="{{ route('bu.searchlist') }}">
                                                        {!! __('bupagelang.searchlist') !!}
                                                    </a>
                                                </li>
                                                <li class="sidebar-item {{ isActiveRoute(['bu/outlist']) }}">
                                                    <a class="sidebar-link" href="{{ route('bu.outlistpage') }}">
                                                        {!! __('bupagelang.outlist') !!}
                                                    </a>
                                                </li>
                                                <li class="sidebar-item {{ isActiveRoute(['bu/picklist']) }}">
                                                    <a class="sidebar-link" href="{{ route('bu.picklistpage') }}">
                                                        {!! __('bupagelang.picklist') !!}
                                                    </a>
                                                </li>
                                                <li
                                                    class="sidebar-item {{ isActiveRoute(['bu.searchdetail', 'bu/searchdetailsub']) }}">
                                                    <a class="sidebar-link" href="{{ route('bu.searchdetail') }}">
                                                        {!! __('bupagelang.searchdetail') !!}
                                                    </a>
                                                </li>

                                            </ul>
                                        </li>


                                        @can('viewAlarm', App\Models\Inventory::class)
                                            <li class="sidebar-item {{ isActiveRoute(['call/']) }}">
                                                <a href="#call" data-bs-toggle="collapse"
                                                    class="sidebar-link collapsed">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor"
                                                        class="bi bi-exclamation-triangle align-middle me-2"
                                                        viewBox="0 0 16 16">
                                                        <path
                                                            d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z" />
                                                        <path
                                                            d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z" />
                                                    </svg>
                                                    <span class="align-middle">{!! __('templateWords.callsys') !!}</span>
                                                </a>
                                                <ul id="call" class="sidebar-dropdown list-unstyled collapse "
                                                    data-bs-parent="#sidebar">
                                                    <li class="sidebar-item {{ isActiveRoute(['call/safe']) }}">
                                                        <a class="sidebar-link" href="{{ route('call.safe') }}">
                                                            {!! __('callpageLang.safealert') !!}
                                                        </a>
                                                    </li>

                                                    <li class="sidebar-item {{ isActiveRoute(['call/day']) }}">
                                                        <a class="sidebar-link" href="{{ route('call.day') }}">
                                                            {!! __('callpageLang.dayalert') !!}
                                                        </a>
                                                    </li>

                                                </ul>
                                            </li>
                                        @endcan

                                        @can('viewCheckInvent', App\Models\Checking_inventory::class)
                                            <li
                                                class="sidebar-item {{ isActiveRoute(['checking/', 'checking.index']) }}">
                                                <a data-bs-target="#checking" data-bs-toggle="collapse"
                                                    class="sidebar-link collapsed" aria-expanded="false">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                        fill="currentColor"
                                                        class="bi bi-clipboard-check align-middle me-2"
                                                        viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd"
                                                            d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z" />
                                                        <path
                                                            d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z" />
                                                        <path
                                                            d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z" />
                                                    </svg>
                                                    <span class="align-middle">{!! __('templateWords.checkIvent') !!}</span>
                                                    {{-- <span class="sidebar-badge badge bg-primary">Pro</span> --}}
                                                </a>
                                                <ul id="checking" class="sidebar-dropdown list-unstyled collapse"
                                                    data-bs-parent="#sidebar" style="">
                                                    <li
                                                        class="sidebar-item {{ isActiveRoute(['checking/create_new_table']) }}">
                                                        <a class="sidebar-link"
                                                            href="{{ route('checking.create_new_table') }}">
                                                            {!! __('checkInvLang.create_new_table') !!}
                                                        </a>
                                                    </li>
                                                    <li class="sidebar-item {{ isActiveRoute(['checking.index']) }}">
                                                        <a class="sidebar-link" href="{{ route('checking.index') }}">
                                                            {!! __('checkInvLang.check') !!}
                                                        </a>
                                                    </li>
                                                    <li
                                                        class="sidebar-item {{ isActiveRoute(['checking/check_result', 'checking.check_result']) }}">
                                                        <a class="sidebar-link"
                                                            href="{{ route('checking.check_result') }}">
                                                            {!! __('checkInvLang.check_result') !!}
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                        @endcan
                                        <li class="sidebar-item {{ isActiveRoute(['member/', 'member.index']) }}">
                                            <a href="#auth" data-bs-toggle="collapse"
                                                class="sidebar-link collapsed">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                    fill="currentColor" class="bi bi-people align-middle me-2"
                                                    viewBox="0 0 16 16">
                                                    <path
                                                        d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z" />
                                                </svg>
                                                <span class="align-middle">{!! __('templateWords.userManage') !!}</span>
                                            </a>
                                            <ul id="auth" class="sidebar-dropdown list-unstyled collapse "
                                                data-bs-parent="#sidebar">
                                                <li class="sidebar-item {{ isActiveRoute(['member/change']) }}">
                                                    <a class="sidebar-link" href="{{ route('member.change') }}">
                                                        {!! __('templateWords.changePass') !!}
                                                        {{-- <span class="sidebar-badge badge bg-primary">Pro</span>
                                                        --}}
                                                    </a>
                                                </li>
                                                @can('searchAndUpdateUser', App\Models\Login::class)
                                                    <li class="sidebar-item {{ isActiveRoute(['member/username']) }}">
                                                        <a class="sidebar-link" href="{{ route('member.username') }}">
                                                            {!! __('templateWords.UserInfo') !!}
                                                            {{-- <span class="sidebar-badge badge bg-primary">Pro</span>
                                                        --}}
                                                        </a>
                                                    </li>
                                                @endcan
                                                @can('searchAndUpdatePeople', App\Models\Login::class)
                                                    <li class="sidebar-item {{ isActiveRoute(['member/number']) }}">
                                                        <a class="sidebar-link"
                                                            href="{{ route('member.numbersearch') }}">
                                                            {!! __('templateWords.PInfo') !!}
                                                            {{-- <span class="sidebar-badge badge bg-primary">Pro</span>
                                                        --}}
                                                        </a>
                                                    </li>
                                                @endcan
                                            </ul>
                                        </li>

                                        {{-- <li class="sidebar-header">
                                            Components
                                        </li> --}}

                                        {{-- <li class="sidebar-item">
                                            <a data-bs-target="#ui" data-bs-toggle="collapse"
                                                class="sidebar-link collapsed">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-briefcase align-middle me-2">
                                                    <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                                                    <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                                                </svg> <span class="align-middle">UI Elements</span>
                                            </a>
                                            <ul id="ui" class="sidebar-dropdown list-unstyled collapse "
                                                data-bs-parent="#sidebar">
                                                <li class="sidebar-item"><a class="sidebar-link"
                                                        href="ui-alerts.html">Alerts</a></li>
                                                <li class="sidebar-item"><a class="sidebar-link"
                                                        href="ui-buttons.html">Buttons</a></li>
                                                <li class="sidebar-item"><a class="sidebar-link"
                                                        href="ui-cards.html">Cards</a></li>
                                                <li class="sidebar-item"><a class="sidebar-link"
                                                        href="ui-general.html">General</a></li>
                                                <li class="sidebar-item"><a class="sidebar-link"
                                                        href="ui-grid.html">Grid</a></li>
                                                <li class="sidebar-item"><a class="sidebar-link"
                                                        href="ui-modals.html">Modals</a></li>
                                                <li class="sidebar-item"><a class="sidebar-link"
                                                        href="ui-offcanvas.html">Offcanvas <span
                                                            class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                                <li class="sidebar-item"><a class="sidebar-link"
                                                        href="ui-tabs.html">Tabs <span
                                                            class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                                <li class="sidebar-item"><a class="sidebar-link"
                                                        href="ui-typography.html">Typography</a></li>
                                            </ul>
                                        </li>
                                        <li class="sidebar-item">
                                            <a data-bs-target="#forms" data-bs-toggle="collapse"
                                                class="sidebar-link collapsed">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-check-circle align-middle me-2">
                                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                                </svg> <span class="align-middle">Forms</span>
                                            </a>
                                            <ul id="forms" class="sidebar-dropdown list-unstyled collapse "
                                                data-bs-parent="#sidebar">
                                                <li class="sidebar-item"><a class="sidebar-link"
                                                        href="forms-basic-inputs.html">Basic Inputs</a></li>
                                                <li class="sidebar-item"><a class="sidebar-link"
                                                        href="forms-layouts.html">Form Layouts <span
                                                            class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                                <li class="sidebar-item"><a class="sidebar-link"
                                                        href="forms-input-groups.html">Input Groups <span
                                                            class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                            </ul>
                                        </li>
                                        <li class="sidebar-item">
                                            <a class="sidebar-link" href="tables-bootstrap.html">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-list align-middle me-2">
                                                    <line x1="8" y1="6" x2="21" y2="6"></line>
                                                    <line x1="8" y1="12" x2="21" y2="12"></line>
                                                    <line x1="8" y1="18" x2="21" y2="18"></line>
                                                    <line x1="3" y1="6" x2="3.01" y2="6"></line>
                                                    <line x1="3" y1="12" x2="3.01" y2="12"></line>
                                                    <line x1="3" y1="18" x2="3.01" y2="18"></line>
                                                </svg> <span class="align-middle">Tables</span>
                                            </a>
                                        </li> --}}

                                        {{-- <li class="sidebar-header">
                                            Plugins &amp; Addons
                                        </li> --}}
                                        {{-- <li class="sidebar-item">
                                            <a data-bs-target="#form-plugins" data-bs-toggle="collapse"
                                                class="sidebar-link collapsed">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-check-square align-middle me-2">
                                                    <polyline points="9 11 12 14 22 4"></polyline>
                                                    <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11">
                                                    </path>
                                                </svg> <span class="align-middle">Form Plugins</span>
                                            </a>
                                            <ul id="form-plugins" class="sidebar-dropdown list-unstyled collapse "
                                                data-bs-parent="#sidebar">
                                                <li class="sidebar-item"><a class="sidebar-link"
                                                        href="forms-advanced-inputs.html">Advanced Inputs <span
                                                            class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                                <li class="sidebar-item"><a class="sidebar-link"
                                                        href="forms-editors.html">Editors <span
                                                            class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                                <li class="sidebar-item"><a class="sidebar-link"
                                                        href="forms-validation.html">Validation <span
                                                            class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                            </ul>
                                        </li> --}}
                                        {{-- <li class="sidebar-item">
                                            <a data-bs-target="#datatables" data-bs-toggle="collapse"
                                                class="sidebar-link collapsed">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-list align-middle me-2">
                                                    <line x1="8" y1="6" x2="21" y2="6"></line>
                                                    <line x1="8" y1="12" x2="21" y2="12"></line>
                                                    <line x1="8" y1="18" x2="21" y2="18"></line>
                                                    <line x1="3" y1="6" x2="3.01" y2="6"></line>
                                                    <line x1="3" y1="12" x2="3.01" y2="12"></line>
                                                    <line x1="3" y1="18" x2="3.01" y2="18"></line>
                                                </svg> <span class="align-middle">DataTables</span>
                                            </a>
                                            <ul id="datatables" class="sidebar-dropdown list-unstyled collapse "
                                                data-bs-parent="#sidebar">
                                                <li class="sidebar-item"><a class="sidebar-link"
                                                        href="tables-datatables-responsive.html">Responsive Table <span
                                                            class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                                <li class="sidebar-item"><a class="sidebar-link"
                                                        href="tables-datatables-buttons.html">Table with Buttons <span
                                                            class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                                <li class="sidebar-item"><a class="sidebar-link"
                                                        href="tables-datatables-column-search.html">Column Search <span
                                                            class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                                <li class="sidebar-item"><a class="sidebar-link"
                                                        href="tables-datatables-fixed-header.html">Fixed Header <span
                                                            class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                                <li class="sidebar-item"><a class="sidebar-link"
                                                        href="tables-datatables-multi.html">Multi Selection <span
                                                            class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                                <li class="sidebar-item"><a class="sidebar-link"
                                                        href="tables-datatables-ajax.html">Ajax Sourced Data <span
                                                            class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                            </ul>
                                        </li> --}}
                                        {{-- <li class="sidebar-item">
                                            <a data-bs-target="#charts" data-bs-toggle="collapse"
                                                class="sidebar-link collapsed">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-bar-chart-2 align-middle me-2">
                                                    <line x1="18" y1="20" x2="18" y2="10"></line>
                                                    <line x1="12" y1="20" x2="12" y2="4"></line>
                                                    <line x1="6" y1="20" x2="6" y2="14"></line>
                                                </svg> <span class="align-middle">Charts</span>
                                            </a>
                                            <ul id="charts" class="sidebar-dropdown list-unstyled collapse "
                                                data-bs-parent="#sidebar">
                                                <li class="sidebar-item"><a class="sidebar-link"
                                                        href="charts-chartjs.html">Chart.js</a></li>
                                                <li class="sidebar-item"><a class="sidebar-link"
                                                        href="charts-apexcharts.html">ApexCharts <span
                                                            class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                            </ul>
                                        </li> --}}
                                        {{-- <li class="sidebar-item">
                                            <a class="sidebar-link" href="notifications.html">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-bell align-middle me-2">
                                                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path>
                                                    <path d="M13.73 21a2 2 0 0 1-3.46 0"></path>
                                                </svg> <span class="align-middle">Notifications</span>
                                                <span class="sidebar-badge badge bg-primary">Pro</span>
                                            </a>
                                        </li> --}}
                                        {{-- <li class="sidebar-item">
                                            <a data-bs-target="#maps" data-bs-toggle="collapse"
                                                class="sidebar-link collapsed">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-map align-middle me-2">
                                                    <polygon points="1 6 1 22 8 18 16 22 23 18 23 2 16 6 8 2 1 6">
                                                    </polygon>
                                                    <line x1="8" y1="2" x2="8" y2="18"></line>
                                                    <line x1="16" y1="6" x2="16" y2="22"></line>
                                                </svg> <span class="align-middle">Maps</span>
                                            </a>
                                            <ul id="maps" class="sidebar-dropdown list-unstyled collapse "
                                                data-bs-parent="#sidebar">
                                                <li class="sidebar-item"><a class="sidebar-link"
                                                        href="maps-google.html">Google Maps</a></li>
                                                <li class="sidebar-item"><a class="sidebar-link"
                                                        href="maps-vector.html">Vector Maps <span
                                                            class="sidebar-badge badge bg-primary">Pro</span></a></li>
                                            </ul>
                                        </li> --}}

                                        {{-- <li class="sidebar-item">
                                            <a data-bs-target="#multi" data-bs-toggle="collapse"
                                                class="sidebar-link collapsed">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-corner-right-down align-middle me-2">
                                                    <polyline points="10 15 15 20 20 15"></polyline>
                                                    <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                                                </svg> <span class="align-middle">Multi Level</span>
                                            </a>
                                            <ul id="multi" class="sidebar-dropdown list-unstyled collapse"
                                                data-bs-parent="#sidebar">
                                                <li class="sidebar-item">
                                                    <a data-bs-target="#multi-2" data-bs-toggle="collapse"
                                                        class="sidebar-link collapsed">Two Levels</a>
                                                    <ul id="multi-2" class="sidebar-dropdown list-unstyled collapse">
                                                        <li class="sidebar-item">
                                                            <a class="sidebar-link" href="#">Item 1</a>
                                                            <a class="sidebar-link" href="#">Item 2</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                                <li class="sidebar-item">
                                                    <a data-bs-target="#multi-3" data-bs-toggle="collapse"
                                                        class="sidebar-link collapsed">Three Levels</a>
                                                    <ul id="multi-3" class="sidebar-dropdown list-unstyled collapse">
                                                        <li class="sidebar-item">
                                                            <a data-bs-target="#multi-3-1" data-bs-toggle="collapse"
                                                                class="sidebar-link collapsed">Item 1</a>
                                                            <ul id="multi-3-1"
                                                                class="sidebar-dropdown list-unstyled collapse">
                                                                <li class="sidebar-item">
                                                                    <a class="sidebar-link" href="#">Item 1</a>
                                                                    <a class="sidebar-link" href="#">Item 2</a>
                                                                </li>
                                                            </ul>
                                                        </li>
                                                        <li class="sidebar-item">
                                                            <a class="sidebar-link" href="#">Item 2</a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </li> --}}

                                    </ul>

                                    {{-- <div class="sidebar-cta">
                                        <div class="sidebar-cta-content">
                                            <strong class="d-inline-block mb-2">Weekly Sales Report</strong>
                                            <div class="mb-3 text-sm">
                                                Your weekly sales report is ready for download!
                                            </div>

                                            <div class="d-grid">
                                                <a href="#" class="btn btn-outline-primary" target="_blank">Download</a>
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="simplebar-placeholder" style="width: auto; height: 1314px;"></div>
                </div>
                <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                    <div class="simplebar-scrollbar" style="width: 0px; display: none;"></div>
                </div>
                <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
                    <div class="simplebar-scrollbar"
                        style="height: 396px; transform: translate3d(0px, 325px, 0px); display: block;"></div>
                </div>
            </div>
        </nav>

        <div class="main">
            <nav class="navbar navbar-expand navbar-light navbar-bg ">
                <a class="sidebar-toggle d-flex">
                    <i class="hamburger align-self-center"></i>
                </a>
                <div class="d-none d-sm-inline-block">
                    <div class="input-group input-group-navbar">
                        <input id="instantSearchBar" type="text" class="form-control" placeholder="Search…"
                            aria-label="Search" autocomplete="off">
                        <button class="btn" type="button">
                            <i class="align-middle" data-feather="search"></i>
                        </button>
                    </div>
                    <ul id="searchResult" class="dropdown-menu" style="border-radius: 10px;">
                    </ul>
                </div>

                <div class="navbar-collapse collapse">
                    <ul class="navbar-nav navbar-align">
                        {{-- <li class="nav-item dropdown">
                            <a class="nav-icon dropdown-toggle pt-1" href="#" id="alertsDropdown"
                                data-bs-toggle="dropdown" data-bs-display="static" style="height: 100%;">
                                <div class="position-relative">
                                    <i class="align-middle" data-feather="bell"></i>
                                    <span class="indicator bg-danger">4</span>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-lg-end py-0"
                                aria-labelledby="alertsDropdown">
                                <div class="dropdown-menu-header">
                                    4 New Notifications
                                </div>
                                <div class="list-group">
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <i class="text-danger" data-feather="alert-circle"></i>
                                            </div>
                                            <div class="col-10">
                                                <div class="text-dark">Update completed</div>
                                                <div class="text-muted small mt-1">Restart server 12 to complete the
                                                    update.</div>
                                                <div class="text-muted small mt-1">30m ago</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <i class="text-warning" data-feather="bell"></i>
                                            </div>
                                            <div class="col-10">
                                                <div class="text-dark">Lorem ipsum</div>
                                                <div class="text-muted small mt-1">Aliquam ex eros, imperdiet vulputate
                                                    hendrerit et.</div>
                                                <div class="text-muted small mt-1">2h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <i class="text-primary" data-feather="home"></i>
                                            </div>
                                            <div class="col-10">
                                                <div class="text-dark">Login from 192.186.1.8</div>
                                                <div class="text-muted small mt-1">5h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <i class="text-success" data-feather="user-plus"></i>
                                            </div>
                                            <div class="col-10">
                                                <div class="text-dark">New connection</div>
                                                <div class="text-muted small mt-1">Christina accepted your request.
                                                </div>
                                                <div class="text-muted small mt-1">14h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="dropdown-menu-footer">
                                    <a href="#" class="text-muted">Show all notifications</a>
                                </div>
                            </div>
                        </li> --}}
                        {{-- <li class="nav-item dropdown">
                            <a class="nav-icon dropdown-toggle" href="#" id="messagesDropdown" data-bs-toggle="dropdown"
                                data-bs-display="static">
                                <div class="position-relative">
                                    <i class="align-middle" data-feather="message-square"></i>
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-lg-end py-0"
                                aria-labelledby="messagesDropdown">
                                <div class="dropdown-menu-header">
                                    <div class="position-relative">
                                        4 New Messages
                                    </div>
                                </div>
                                <div class="list-group">
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <img src="../admin/img/avatars/avatarBot5.png"
                                                    class="avatar img-fluid rounded-circle" alt="Vanessa">
                                            </div>
                                            <div class="col-10 pl-2">
                                                <div class="text-dark">Vanessa Tucker</div>
                                                <div class="text-muted small mt-1">Nam pretium turpis et arcu. Duis arcu
                                                    tortor.</div>
                                                <div class="text-muted small mt-1">15m ago</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <img src="../admin/img/avatars/avatarBot2.png"
                                                    class="avatar img-fluid rounded-circle" alt="William">
                                            </div>
                                            <div class="col-10 pl-2">
                                                <div class="text-dark">William Harris</div>
                                                <div class="text-muted small mt-1">Curabitur ligula sapien euismod
                                                    vitae.</div>
                                                <div class="text-muted small mt-1">2h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <img src="../admin/img/avatars/avatarBot4.png"
                                                    class="avatar img-fluid rounded-circle" alt="Christina">
                                            </div>
                                            <div class="col-10 pl-2">
                                                <div class="text-dark">Christina Mason</div>
                                                <div class="text-muted small mt-1">Pellentesque auctor neque nec urna.
                                                </div>
                                                <div class="text-muted small mt-1">4h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                    <a href="#" class="list-group-item">
                                        <div class="row g-0 align-items-center">
                                            <div class="col-2">
                                                <img src="../admin/img/avatars/avatarBot3.png"
                                                    class="avatar img-fluid rounded-circle" alt="Sharon">
                                            </div>
                                            <div class="col-10 pl-2">
                                                <div class="text-dark">Sharon Lessman</div>
                                                <div class="text-muted small mt-1">Aenean tellus metus, bibendum sed,
                                                    posuere ac, mattis non.</div>
                                                <div class="text-muted small mt-1">5h ago</div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="dropdown-menu-footer">
                                    <a href="#" class="text-muted">Show all messages</a>
                                </div>
                            </div>
                        </li> --}}
                        <li class="nav-item dropdown">
                            <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#"
                                data-bs-toggle="dropdown" data-bs-auto-close="outside" data-bs-display="static">
                                <i class="align-middle" data-feather="settings"></i>
                            </a>

                            <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#"
                                data-bs-toggle="dropdown" data-bs-auto-close="outside" data-bs-display="static">
                                <img src="../admin/img/avatars/avatarBot{{ \Auth::user()->avatarChoice }}.png"
                                    class="avatar img-fluid rounded mx-auto" alt="{{ \Auth::user()->姓名 }}" /> <span
                                    class="text-dark">{{ \Auth::user()->姓名 }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end align-items-center" id="UserDropDown"
                                style="border-radius: 10px; max-height: 48ch; overflow-y: auto;">
                                <a class="dropdown-item" href="{{ url('/member/change') }}">
                                    <i class="p-0 mr-1" data-feather="user"></i>
                                    <span>Profile</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                @can('canSwitchSites', App\Models\Login::class)
                                    <a class="dropdown-item align-items-center" data-bs-toggle="collapse"
                                        data-bs-target="#sitesMenu" aria-expanded="false">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                            fill="currentColor" class="bi bi-building p-0 mr-1" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M14.763.075A.5.5 0 0 1 15 .5v15a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V14h-1v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V10a.5.5 0 0 1 .342-.474L6 7.64V4.5a.5.5 0 0 1 .276-.447l8-4a.5.5 0 0 1 .487.022zM6 8.694 1 10.36V15h5V8.694zM7 15h2v-1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5V15h2V1.309l-7 3.5V15z" />
                                            <path
                                                d="M2 11h1v1H2v-1zm2 0h1v1H4v-1zm-2 2h1v1H2v-1zm2 0h1v1H4v-1zm4-4h1v1H8V9zm2 0h1v1h-1V9zm-2 2h1v1H8v-1zm2 0h1v1h-1v-1zm2-2h1v1h-1V9zm0 2h1v1h-1v-1zM8 7h1v1H8V7zm2 0h1v1h-1V7zm2 0h1v1h-1V7zM8 5h1v1H8V5zm2 0h1v1h-1V5zm2 0h1v1h-1V5zm0-2h1v1h-1V3z" />
                                        </svg>
                                        <span>{{ str_replace('Consumables management', '', \Session::get('database')) }}</span>
                                    </a>
                                    <div class="collapse" id="sitesMenu">
                                        @for ($i = 0; $i < count(explode('_', \Auth::user()->available_dblist)); $i++)
                                            @if (\Session::get('database') != explode('_', \Auth::user()->available_dblist)[$i] . ' Consumables management')
                                                <a class="dropdown-item justify-content-center"
                                                    href="{{ url('/switchSite/' . str_replace(' ', '_', explode('_', \Auth::user()->available_dblist)[$i]) . '_Consumables_management') }}"
                                                    value="{{ explode('_', \Auth::user()->available_dblist)[$i] }}">
                                                    {{ explode('_', \Auth::user()->available_dblist)[$i] }}</a>
                                            @endif
                                        @endfor
                                    </div>
                                @else
                                    <a class="dropdown-item disabled align-items-center" style="color : #495057;"
                                        href="#">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                            fill="currentColor" class="bi bi-building p-0 mr-1" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M14.763.075A.5.5 0 0 1 15 .5v15a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5V14h-1v1.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V10a.5.5 0 0 1 .342-.474L6 7.64V4.5a.5.5 0 0 1 .276-.447l8-4a.5.5 0 0 1 .487.022zM6 8.694 1 10.36V15h5V8.694zM7 15h2v-1.5a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 .5.5V15h2V1.309l-7 3.5V15z" />
                                            <path
                                                d="M2 11h1v1H2v-1zm2 0h1v1H4v-1zm-2 2h1v1H2v-1zm2 0h1v1H4v-1zm4-4h1v1H8V9zm2 0h1v1h-1V9zm-2 2h1v1H8v-1zm2 0h1v1h-1v-1zm2-2h1v1h-1V9zm0 2h1v1h-1v-1zM8 7h1v1H8V7zm2 0h1v1h-1V7zm2 0h1v1h-1V7zM8 5h1v1H8V5zm2 0h1v1h-1V5zm2 0h1v1h-1V5zm0-2h1v1h-1V3z" />
                                        </svg>
                                        <span>{{ str_replace('Consumables management', '', \Session::get('database')) }}</span>
                                    </a>
                                @endcan
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" data-bs-toggle="collapse" data-bs-target="#langMenu"
                                    aria-expanded="false">
                                    <i class="p-0 mr-1" data-feather="book-open"></i>
                                    <span>{!! __('templateWords.language') !!}</span>
                                </a>
                                <div class="collapse" id="langMenu">
                                    <a class="dropdown-item justify-content-center" href="{{ url('/lang/en') }}">
                                        English</a>
                                    <a class="dropdown-item justify-content-center" href="{{ url('/lang/zh-TW') }}">
                                        繁體中文</a>
                                    <a class="dropdown-item justify-content-center" href="{{ url('/lang/zh-CN') }}">
                                        简体中文</a>
                                </div>
                                <div class="dropdown-divider"></div>
                                {{-- <a class="dropdown-item" href="#">
                                    <i class="p-0 mr-1" data-feather="settings"></i>
                                    <span>Settings</span>
                                </a> --}}
                                <a class="dropdown-item" href="{{ url('/help') }}">
                                    <i class="p-0 mr-1" data-feather="help-circle"></i>
                                    <span>Help Center</span>
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" id="logoutbtn" href="{{ route('member.logout') }}">
                                    <i class="p-0 mr-1" data-feather="log-out"></i>
                                    <span>{!! __('templateWords.logout_btn') !!}</span>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="content">
                @yield('content')
            </main>

            <!-- <footer class="footer">
                <div class="container-fluid">
                    <div class="row text-muted">
                        <div class="col-6 text-start">
                            <p class="mb-0">
                                <a class="text-muted" href="#" target="_blank"><strong>Consumptive Material Management Website</strong></a>
                            </p>
                        </div>
                        <div class="col-6 text-end">
                            <ul class="list-inline">
                                <li class="list-inline-item">
                                    <a class="text-muted" href="#" target="_blank">Support</a>
                                </li>
                                <li class="list-inline-item">
                                    <a class="text-muted" href="#" target="_blank">Help Center</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </footer> -->
        </div>
    </div>

    <!-- vue component example -->
    <!-- <div id="vue-app">
        <example-component></example-component>
    </div> -->
    <!-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            var ctx = document.getElementById("chartjs-dashboard-line").getContext("2d");
            var gradientLight = ctx.createLinearGradient(0, 0, 0, 225);
            gradientLight.addColorStop(0, "rgba(215, 227, 244, 1)");
            gradientLight.addColorStop(1, "rgba(0, 0, 0, 0)");
            var gradientDark = ctx.createLinearGradient(0, 0, 0, 225);
            gradientDark.addColorStop(0, "rgba(51, 66, 84, 1)");
            gradientDark.addColorStop(1, "rgba(0, 0, 0, 0)");
            // Line chart
            new Chart(document.getElementById("chartjs-dashboard-line"), {
                type: "line",
                data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov",
                        "Dec"
                    ],
                    datasets: [{
                        label: "Sales ($)",
                        fill: true,
                        backgroundColor: window.theme.id === "light" ? gradientLight : gradientDark,
                        borderColor: window.theme.primary,
                        data: [
                            2115,
                            1562,
                            1584,
                            1892,
                            1587,
                            1923,
                            2566,
                            2448,
                            2805,
                            3438,
                            2917,
                            3327
                        ]
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    legend: {
                        display: false
                    },
                    tooltips: {
                        intersect: false
                    },
                    hover: {
                        intersect: true
                    },
                    plugins: {
                        filler: {
                            propagate: false
                        }
                    },
                    scales: {
                        xAxes: [{
                            reverse: true,
                            gridLines: {
                                color: "rgba(0,0,0,0.0)"
                            }
                        }],
                        yAxes: [{
                            ticks: {
                                stepSize: 1000
                            },
                            display: true,
                            borderDash: [3, 3],
                            gridLines: {
                                color: "rgba(0,0,0,0.0)",
                                fontColor: "#fff"
                            }
                        }]
                    }
                }
            });
        });
    </script> -->
    <!-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Pie chart
            new Chart(document.getElementById("chartjs-dashboard-pie"), {
                type: "pie",
                data: {
                    labels: ["Chrome", "Firefox", "IE", "Other"],
                    datasets: [{
                        data: [4306, 3801, 1689, 3251],
                        backgroundColor: [
                            window.theme.primary,
                            window.theme.warning,
                            window.theme.danger,
                            "#E8EAED"
                        ],
                        borderWidth: 5,
                        borderColor: window.theme.white
                    }]
                },
                options: {
                    responsive: !window.MSInputMethodContext,
                    maintainAspectRatio: false,
                    legend: {
                        display: false
                    },
                    cutoutPercentage: 70
                }
            });
        });
    </script> -->
    <!-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Bar chart
            new Chart(document.getElementById("chartjs-dashboard-bar"), {
                type: "bar",
                data: {
                    labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov",
                        "Dec"
                    ],
                    datasets: [{
                        label: "This year",
                        backgroundColor: window.theme.primary,
                        borderColor: window.theme.primary,
                        hoverBackgroundColor: window.theme.primary,
                        hoverBorderColor: window.theme.primary,
                        data: [54, 67, 41, 55, 62, 45, 55, 73, 60, 76, 48, 79],
                        barPercentage: .75,
                        categoryPercentage: .5
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    legend: {
                        display: false
                    },
                    scales: {
                        yAxes: [{
                            gridLines: {
                                display: false
                            },
                            stacked: false,
                            ticks: {
                                stepSize: 20
                            }
                        }],
                        xAxes: [{
                            stacked: false,
                            gridLines: {
                                color: "transparent"
                            }
                        }]
                    }
                }
            });
        });
    </script> -->
    <!-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            var markers = [{
                    coords: [31.230391, 121.473701],
                    name: "Shanghai"
                },
                {
                    coords: [28.704060, 77.102493],
                    name: "Delhi"
                },
                {
                    coords: [6.524379, 3.379206],
                    name: "Lagos"
                },
                {
                    coords: [35.689487, 139.691711],
                    name: "Tokyo"
                },
                {
                    coords: [23.129110, 113.264381],
                    name: "Guangzhou"
                },
                {
                    coords: [40.7127837, -74.0059413],
                    name: "New York"
                },
                {
                    coords: [34.052235, -118.243683],
                    name: "Los Angeles"
                },
                {
                    coords: [41.878113, -87.629799],
                    name: "Chicago"
                },
                {
                    coords: [51.507351, -0.127758],
                    name: "London"
                },
                {
                    coords: [40.416775, -3.703790],
                    name: "Madrid "
                }
            ];
            var map = new jsVectorMap({
                map: "world",
                selector: "#world_map",
                zoomButtons: true,
                markers: markers,
                markerStyle: {
                    initial: {
                        r: 9,
                        stroke: window.theme.white,
                        strokeWidth: 7,
                        stokeOpacity: .4,
                        fill: window.theme.primary
                    },
                    hover: {
                        fill: window.theme.primary,
                        stroke: window.theme.primary
                    }
                },
                regionStyle: {
                    initial: {
                        fill: window.theme["gray-200"]
                    }
                },
                zoomOnScroll: false
            });
            window.addEventListener("resize", () => {
                map.updateSize();
            });
            setTimeout(function() {
                map.updateSize();
            }, 250);
        });
    </script> -->
    <!-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            var date = new Date(Date.now() - 5 * 24 * 60 * 60 * 1000);
            var defaultDate = date.getUTCFullYear() + "-" + (date.getUTCMonth() + 1) + "-" + date.getUTCDate();
            document.getElementById("datetimepicker-dashboard").flatpickr({
                inline: true,
                prevArrow: "<span class=\"fas fa-chevron-left\" title=\"Previous month\"></span>",
                nextArrow: "<span class=\"fas fa-chevron-right\" title=\"Next month\"></span>",
                defaultDate: defaultDate
            });
        });
    </script> -->

    <script src="{{ asset('/js/manifest.js') }}"></script>
    <script src="{{ asset('/js/vendor.js') }}"></script>
    <script src="{{ asset('/js/app.js?v=') . env('APP_VERSION') }}"></script>
    <script src="{{ asset('/admin/js/app.js?v=') . env('APP_VERSION') }}"></script>
    <script src="{{ asset('/js/jquery.loadingModal.min.js?v=') . env('APP_VERSION') }}"></script>
    <script src="{{ asset('/js/logout.js?v=') . env('APP_VERSION') }}"></script>
    <script src="{{ asset('js/popupNotice.js?v=') . env('APP_VERSION') }}"></script>
    <script src="{{ asset('js/template.js?v=') . env('APP_VERSION') }}"></script>
    @yield('js')
</body>

</html>
