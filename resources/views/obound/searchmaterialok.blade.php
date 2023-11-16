@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
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
            <h2 class="col-auto">{!! __('templateWords.obound') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                {{-- <vue-bread-crumb></vue-bread-crumb> --}}
            </div>
        </div>
        <button type="hidden" id="QueryFlag" name="QueryFlag" value="Posting" style="display: none;"></button>
        <div class="card">
            <div class="card-header">
                <h3>{!! __('oboundpageLang.matsInfo') !!}</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <obound-isnsearch-table></obound-isnsearch-table>

                </div>
            </div>
        </div>
    </div>
@endsection
