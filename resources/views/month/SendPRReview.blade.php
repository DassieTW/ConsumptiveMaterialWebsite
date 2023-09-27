@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
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

                    <combined-monthly-search-table></combined-monthly-search-table>
                </form>
            </div>
            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
        </div>
    </div>
@endsection
