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

    <link rel="stylesheet" type="text/css" href="{{ asset('/css/bootstrap.min.css?v=') . env('APP_VERSION') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/app.css?v=') . env('APP_VERSION') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/app.css?v=') . env('APP_VERSION') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('/css/jquery.loadingModal.min.css?v=') . env('APP_VERSION') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/check.css?v=') . env('APP_VERSION') }}">

    <style>
        /* for single line table with over-flow , SAP style as asked */
        table {
            table-layout: fixed;
            /* width: 900px; */
        }

        .table-responsive {
            height: 600px;
        }

        thead tr:nth-child(1) th {
            background: rgb(241, 228, 202);
            position: sticky;
            top: 0;
            z-index: 10;
        }
    </style>

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
    
    <script src="{{ asset('/js/manifest.js?v=') . env('APP_VERSION') }}"></script>
    <script src="{{ asset('/js/vendor.js?v=') . env('APP_VERSION') }}"></script>
    <script src="{{ asset('/js/app.js?v=') . env('APP_VERSION') }}"></script>
    <script src="{{ asset('/admin/js/app.js?v=') . env('APP_VERSION') }}"></script>
    <script src="{{ asset('/js/jquery.loadingModal.min.js?v=') . env('APP_VERSION') }}"></script>
    <nav class="navbar navbar-expand navbar-light navbar-bg p-0 m-0">
        <div class="navbar-collapse collapse">
            <ul class="navbar-nav navbar-align">

                <div class="dropdown">
                    <a class="dropdown-toggle nav-link" href="#langMenu" data-bs-toggle="dropdown"
                        data-bs-auto-close="outside" data-bs-display="static" aria-expanded="false">
                        <i class="align-middle mr-1" data-feather="book-open"></i>{{ __('templateWords.language') }}
                    </a>

                    <ul class="dropdown-menu" id="langMenu">
                        <li> <a class="dropdown-item justify-content-center" href="{{ url('/lang/en') }}">
                                English</a></li>
                        <li><a class="dropdown-item justify-content-center" href="{{ url('/lang/zh-TW') }}">
                                繁體中文</a></li>
                        <li><a class="dropdown-item justify-content-center" href="{{ url('/lang/zh-CN') }}">
                                简体中文</a></li>
                    </ul>
                </div>
            </ul>
        </div>
    </nav>
    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

    <main class="d-flex">
        <div class="container d-flex flex-column">
            <!-- </div>breaks cols to a new line-->
            <div class="card">
                <div class="text-center mt-4">
                    <h1 class="h2 d-none d-sm-inline-block">
                        {!! __('templateWords.monthly') !!}
                    </h1>
                    <br>
                    <p class="lead d-none d-sm-inline-block">
                        {!! __('monthlyPRpageLang.consume') !!}
                    </p>
                </div>

                <div class="card-header">
                    <h3 class="text-center">{!! __('monthlyPRpageLang.emailsender') !!} : {{ $username }} ({!! __('basicInfoLang.factory') !!} :
                        {{ $database }})</h3>
                    <input type="hidden" id="sender" value="{{ $username }}">
                    <input type="hidden" id="database" value="{{ $database }}">
                </div>
            </div>
            <div class="card">
                <div class="card-body">

                    <form id="consumecheck" method="POST">
                        @csrf
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{!! __('monthlyPRpageLang.isn') !!}</th>
                                        <th>{!! __('monthlyPRpageLang.pName') !!}</th>
                                        <th>{!! __('monthlyPRpageLang.client') !!}</th>
                                        <th>{!! __('monthlyPRpageLang.machine') !!}</th>
                                        <th>{!! __('monthlyPRpageLang.process') !!}</th>
                                        <th>{!! __('monthlyPRpageLang.consume') !!}</th>
                                        <th></th>
                                        <th>{!! __('monthlyPRpageLang.reason') !!}</th>
                                    </tr>
                                </thead>
                                @foreach ($data as $data)
                                    <?php
                                    //$data->單耗 = number_format(floatval($data->單耗),12);
                                    $unitConsume = abs((float) $data->單耗) < 1e-20 ? '0' : rtrim(sprintf('%.10F', ((float) $data->單耗)), '0');
                                    // result should be 0 or 1.8392832 or 14.

                                    if (strpos($unitConsume, '.') === strlen($unitConsume) - 1) {
                                        // if the result is 5. (should be like 5.0)
                                        $data->單耗 = sprintf('%.1F', ((float) $data->單耗));
                                    }
                                    // if
                                    else {
                                        $data->單耗 = $unitConsume;
                                    } // else
                                    $name = DB::table('consumptive_material')
                                        ->where('料號', $data->料號)
                                        ->value('品名');
                                    ?>
                                    <tbody>
                                        <tr>
                                            <td><input type="hidden" id="number{{ $loop->index }}"
                                                    name="number{{ $loop->index }}"
                                                    value="{{ $data->料號 }}">{{ $data->料號 }}</td>
                                            <td><input type="hidden" id="name{{ $loop->index }}"
                                                    name="name{{ $loop->index }}"
                                                    value="{{ $name }}">{{ $name }}</td>
                                            <td><input type="hidden" id="client{{ $loop->index }}"
                                                    name="client{{ $loop->index }}"
                                                    value="{{ $data->客戶別 }}">{{ $data->客戶別 }}</td>
                                            <td><input type="hidden" id="machine{{ $loop->index }}"
                                                    name="machine{{ $loop->index }}"
                                                    value="{{ $data->機種 }}">{{ $data->機種 }}</td>
                                            <td><input type="hidden" id="production{{ $loop->index }}"
                                                    name="production{{ $loop->index }}"
                                                    value="{{ $data->製程 }}">{{ $data->製程 }}
                                            </td>
                                            <td class="table-light" id="amount{{ $loop->index }}">{{ $data->單耗 }}
                                            </td>
                                            <td><input class="checkbutton" type="checkbox"
                                                    id="check{{ $loop->index }}" name="check{{ $loop->index }}">
                                            </td>
                                            <td><input style="width: 120px;" class="form-control formcontrol-lg"
                                                    type="text" id="remark{{ $loop->index }}"
                                                    name="remark{{ $loop->index }}" required
                                                    placeholder="{!! __('monthlyPRpageLang.reason') !!}"></td>
                                        </tr>
                                        <input type="hidden" id="count" name="count"
                                            value="{{ $loop->count }}"></td>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        {{-- <label class="form-label">{!! __('monthlyPRpageLang.surepeople') !!}:</label>
                        <input class="form-control form-control-lg" type="text" id="jobnumber" name="jobnumber" required
                            style="width: 250px" oninput="if(value.length>9)value=value.slice(0,9)">
                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line--> --}}
                        <label class="form-label">{!! __('monthlyPRpageLang.surepeopleemail') !!}:</label>
                        <input class="form-control form-control-lg" type="email" id="email" name="email"
                            pattern=".+@pegatroncorp\.com" readonly style="width: 250px"
                            placeholder="xxx@pegatroncorp.com" value="{{ $email }}">
                        <div class="text-center mt-3">
                            <input type="submit" id="submit" name="submit" class="btn btn-lg btn-primary"
                                value="{!! __('monthlyPRpageLang.submit') !!}">
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </main>
    <script src="{{ asset('/messages.js?v=') . env('APP_VERSION') }}"></script>
    <script src="{{ asset('js/popupNotice.js?v=') . env('APP_VERSION') }}"></script>
    <script src="{{ asset('js/month/testconsume.js?v=') . env('APP_VERSION') }}"></script>

</body>
