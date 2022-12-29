@extends('layouts.adminTemplate')
@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . env('APP_VERSION') }}">
@endsection

@section('js')
    <!--for this page's sepcified js -->
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row w-100">
            <div class="col col-auto">
                <a href="http://eip.tw.pegatroncorp.com/" target="_blank">{!! __('templateWords.taipei') !!}</a>
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                <a href="http://eip.tw.pegatroncorp.com/DeptSiteMap.aspx" target="_blank">{!! __('templateWords.dep') !!}</a>
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                <a href="http://project.eip.tw.pegatroncorp.com/default.aspx" target="_blank">{!! __('templateWords.project') !!}</a>
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                <a href="http://eip.sh.pegatroncorp.com/" target="_blank">{!! __('templateWords.east') !!}</a>
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                <a href="http://eip.sz.pegatroncorp.com/eip/" target="_blank">{!! __('templateWords.middle') !!}</a>
                <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                <a href="http://eip.cq.pegatroncorp.com/Home.aspx" target="_blank">{!! __('templateWords.west') !!}</a>
            </div>
        </div>

        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

        <div class="row">
            <div class="card w-100 flex-fill">
                <div class="card-header">
                    <h5 class="card-title mb-0">Latest Projects</h5>
                </div>
                <news-table></news-table>
            </div>
        </div>
    </div>
@endsection
