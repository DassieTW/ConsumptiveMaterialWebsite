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
