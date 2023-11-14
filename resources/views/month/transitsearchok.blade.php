@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
    <!--for this page's sepcified js -->
    <script>
        $(document).ready(function() {
            var title = [];
            var titlecol = [];
            $("body").loadingModal({
                text: "Loading...",
                animation: "circle",
            });

            $("#QueryFlag").on("click", function(e) {
                // console.log("clicked!"); // test
                $("body").loadingModal("hide");
                $("body").loadingModal("destroy");
            });
        });
    </script>
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.monthly') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                <vue-bread-crumb></vue-bread-crumb>
            </div>
        </div>
        <button type="hidden" id="QueryFlag" name="QueryFlag" value="Posting" style="display: none;"></button>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('monthlyPRpageLang.on_the_way_search') !!}</h3>

            </div>
            <div class="card-body">

                <transit-search-table></transit-search-table>

                {{-- <button class="btn btn-lg btn-primary" onclick="location.href='{{route('month.transit')}}'">{!!
                __('monthlyPRpageLang.return') !!}</button> --}}
            </div>
        </div>
    </div>
@endsection
