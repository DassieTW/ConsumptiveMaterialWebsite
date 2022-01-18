<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="../admin/img/icons/icon-48x48.png" />

    <link rel="stylesheet" type="text/css" href="{{ asset('/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . time() }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/jquery.loadingModal.min.css') }}">
    <script>
        if (window.history.replaceState) {
            // java script to prvent "confirm form resubmission" dialog
            // 避免重新整理這個頁面時跳出要你重新提交表單的對話框
            // (避免重新提交表單)
            window.history.replaceState(null, null, window.location.href);
        } // if
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    {{-- 使用asset時，須注意Laravel是抓http header裡面的Host作為base url，
    所以若域名不是ip而有經過轉換時要進到Nginx裡面的proxy_set_header裡面確認Host設定值，
    否則會抓到錯誤的Host導致找不到資源 --}}

    <script src="{{ asset('/js/app.js') }}"></script>
    <script src="{{ asset('/admin/js/app.js') }}"></script>
    <script src="{{ asset('/js/jquery.loadingModal.min.js') }}"></script>

    <main class="d-flex">
        <div class="container d-flex flex-column">
            <!-- </div>breaks cols to a new line-->
            <div class="text-center mt-4">
                <h1 class="h2 d-none d-sm-inline-block">
                    {!! __('templateWords.monthly') !!}
                </h1>
                <br>
                <p class="lead d-none d-sm-inline-block">
                    Check Consume
                </p>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="">
                        <form id="consumecheck" method="POST">
                            @csrf
                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <th>{!! __('monthlyPRpageLang.isn') !!}</th>
                                        <th>{!! __('monthlyPRpageLang.client') !!}</th>
                                        <th>{!! __('monthlyPRpageLang.machine') !!}</th>
                                        <th>{!! __('monthlyPRpageLang.process') !!}</th>
                                        <th>{!! __('monthlyPRpageLang.consume') !!}</th>
                                    </tr>
                                    @foreach($data as $data)
                                    <?php
                                        $data->單耗 = round($data->單耗 , 10);
                                    ?>
                                    <tr>
                                        <td><input type="hidden" id="number{{$loop->index}}"
                                                name="number{{$loop->index}}" value="{{$data->料號}}">{{$data->料號}}</td>
                                        <td><input type="hidden" id="client{{$loop->index}}"
                                                name="client{{$loop->index}}" value="{{$data->客戶別}}">{{$data->客戶別}}</td>
                                        <td><input type="hidden" id="machine{{$loop->index}}"
                                                name="machine{{$loop->index}}" value="{{$data->機種}}">{{$data->機種}}</td>
                                        <td><input type="hidden" id="production{{$loop->index}}"
                                                name="production{{$loop->index}}" value="{{$data->製程}}">{{$data->製程}}
                                        </td>
                                        <td><input style="width: 200px;" class="form-control form-control-lg "
                                                type="number" id="amount{{$loop->index}}" name="amount{{$loop->index}}"
                                                value="{{$data->單耗}}" step="0.0000000001"
                                                oninput="if(value.length>12)value=value.slice(0,12)"></td>
                                        <td><input type="hidden" id="compare{{$loop->index}}"
                                                name="compare{{$loop->index}}" value="{{$data->單耗}}"></td>
                                    </tr>
                                    <input type="hidden" id="count" name="count" value="{{$loop->count}}"></td>
                                    @endforeach

                                </table>
                            </div>

                            <label class="form-label">{!! __('monthlyPRpageLang.surepeople') !!}:</label>
                            <input class = "form-control form-control-lg" type="text" id="jobnumber" name="jobnumber" required style="width: 250px">
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
                            <label class="form-label">{!! __('monthlyPRpageLang.surepeopleemail') !!}:</label>
                            <input class = "form-control form-control-lg" type="email" id="email" name="email" pattern=".+@pegatroncorp\.com" required style="width: 250px"
                                placeholder="xxx@pegatroncorp.com">
                                <div class="text-center mt-3">
                                    <input type="submit" id="submit" name="submit" class="btn btn-lg btn-primary"
                                        value="{!! __('monthlyPRpageLang.submit') !!}">
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script src="{{ asset('/messages.js') }}"></script>
    <script src="{{ asset('js/popupNotice.js') }}"></script>
    <script src="{{ asset('js/month/testconsume.js') }}"></script>

</body>
