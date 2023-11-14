@extends('layouts.adminTemplate')
@section('css')
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
