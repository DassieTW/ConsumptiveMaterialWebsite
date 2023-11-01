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
    <!--for this page's sepcified js -->
    <script>
        $(function() {
            sessionStorage.setItem("lookInType", JSON.stringify("1"));
        });
    </script>
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">

            <h2 class="col-auto">{!! __('monthlyPRpageLang.UpdateUnitPrice') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                <vue-bread-crumb></vue-bread-crumb>
            </div>
        </div>
        <div class="card">
            <div class="card-body row justify-content-center">
                <div class="col col-auto m-0 p-0">
                    <span>{!! __('monthlyPRpageLang.please_utilize') !!}</span>
                    &nbsp;
                    <a href="{{ url('/basic/materialsearch') }}">{!! __('basicInfoLang.matsInfo') !!}</a>
                    &nbsp;
                    <span>{!! __('monthlyPRpageLang.to_update_unit_price') !!}</span>
                </div>
            </div>
        </div>
    </div>
@endsection
