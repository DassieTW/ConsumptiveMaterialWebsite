@extends('layouts.adminTemplate')
@section('css')
@endsection

@section('js')
    <!--for this page's sepcified js -->
    <script>
        function ScientificNotaionToFixed(x) {
            // toFixed
            if (Math.abs(x.value) < 1.0) {
                var e = parseInt(x.value.toString().split("e-")[1]);
                if (e) {
                    x.value *= Math.pow(10, e - 1);
                    x.value = "0." + new Array(e).join("0") + x.value.toString().substring(2);
                } // if
            } else {
                var e = parseInt(x.value.toString().split("+")[1]);
                if (e > 20) {
                    e -= 20;
                    x.value /= Math.pow(10, e);
                    x.value += new Array(e + 1).join("0");
                } // if
            } // if-else
        } // to prevent scientific notaion
    </script>
    <script src="{{ asset('js/month/consumeadd.js?v=') . env('APP_VERSION') }}"></script>
@endsection
@section('content')
    <div id="mountingPoint">
        <div class="row mb-2 mb-xl-3 justify-content-between">
            <h2 class="col-auto">{!! __('templateWords.monthly') !!}</h2>
            <div class="col-auto ml-auto text-right mt-n1 d-none d-sm-block">
                <vue-bread-crumb></vue-bread-crumb>
            </div>
        </div>

        <form id="consumeadd" style="display: none">
        </form>

        <unit-consumption-upload-table></unit-consumption-upload-table>
    </div>
@endsection
