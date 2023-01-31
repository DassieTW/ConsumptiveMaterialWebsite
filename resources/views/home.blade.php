@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
    <!--for this page's sepcified js -->
@endsection
@section('content')
    <div class="row justify-content-center" id="mountingPoint">
        <div class="col col-auto">
            <a href="http://eip.tw.pegatroncorp.com/" target="_blank">{!! __('templateWords.taipei') !!}</a>
            &nbsp;<hr width="3" size="10;" style="margin: auto; align:center; display:inline-block;" /> &nbsp;
            <a href="http://eip.tw.pegatroncorp.com/DeptSiteMap.aspx" target="_blank">{!! __('templateWords.dep') !!}</a>
            &nbsp;<hr width="3" size="10;" style="margin: auto; align:center; display:inline-block;" /> &nbsp;
            <a href="http://project.eip.tw.pegatroncorp.com/default.aspx" target="_blank">{!! __('templateWords.project') !!}</a>
            &nbsp;<hr width="3" size="10;" style="margin: auto; align:center; display:inline-block;" /> &nbsp;
            <a href="http://eip.sh.pegatroncorp.com/" target="_blank">{!! __('templateWords.east') !!}</a>
            &nbsp;<hr width="3" size="10;" style="margin: auto; align:center; display:inline-block;" /> &nbsp;
            <a href="http://eip.sz.pegatroncorp.com/eip/" target="_blank">{!! __('templateWords.middle') !!}</a>
            &nbsp;<hr width="3" size="10;" style="margin: auto; align:center; display:inline-block;" /> &nbsp;
            <a href="http://eip.cq.pegatroncorp.com/Home.aspx" target="_blank">{!! __('templateWords.west') !!}</a>
        </div>

        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

        <div class="row">
            <div class="card w-100 flex-fill">
                <div class="card-header">
                    <h5 class="card-title mb-0">{!! __('templateWords.News') !!}</h5>
                </div>
                <news-table></news-table>
            </div>
        </div>
    </div>
@endsection
