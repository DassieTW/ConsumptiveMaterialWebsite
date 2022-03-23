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
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/app.css?v=') . time() }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/jquery.loadingModal.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/check.css') }}">

    <style>
        /* for single line table with over-flow , SAP style as asked */
        table {
            table-layout: fixed;
            /* width: 300px; */
        }

        .table-responsive {
            height: 650px;
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

    <script src="{{ asset('/js/app.js') }}"></script>
    <script src="{{ asset('/admin/js/app.js') }}"></script>
    <script src="{{ asset('/js/jquery.loadingModal.min.js') }}"></script>

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
                        {!! __('monthlyPRpageLang.stand') !!}
                    </p>
                </div>
                <div class="card-header">
                    <h3 class="text-center">{!! __('monthlyPRpageLang.emailsender') !!} : {{$username}} ({!!
                        __('basicInfoLang.factory') !!} : {{$database}})</h3>
                    <input type="hidden" id="sender" value="{{$username}}">
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <form id="standcheck" method="POST">
                        @csrf
                        <div class="table-responsive ">
                            <table class = "table">
                                <thead>
                                    <tr>
                                        <th>{!! __('monthlyPRpageLang.isn') !!}</th>
                                        <th>{!! __('monthlyPRpageLang.pName') !!}</th>
                                        <th>{!! __('monthlyPRpageLang.client') !!}</th>
                                        <th>{!! __('monthlyPRpageLang.machine') !!}</th>
                                        <th>{!! __('monthlyPRpageLang.process') !!}</th>
                                        <th>{!! __('monthlyPRpageLang.nowpeople') !!}</th>
                                        <th>{!! __('monthlyPRpageLang.nowline') !!}</th>
                                        <th>{!! __('monthlyPRpageLang.nowclass') !!}</th>
                                        <th>{!! __('monthlyPRpageLang.nowuse') !!}</th>
                                        <th>{!! __('monthlyPRpageLang.nowchange') !!}</th>
                                        <th>{!! __('monthlyPRpageLang.nextpeople') !!}</th>
                                        <th>{!! __('monthlyPRpageLang.nextline') !!}</th>
                                        <th>{!! __('monthlyPRpageLang.nextclass') !!}</th>
                                        <th>{!! __('monthlyPRpageLang.nextuse') !!}</th>
                                        <th>{!! __('monthlyPRpageLang.nextchange') !!}</th>
                                        <th></th>
                                        <th></th>
                                        <th>{!! __('monthlyPRpageLang.reason') !!}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($data as $data)
                                    <?php
                                        $name = DB::table('consumptive_material')->where('料號',$data->料號)->value('品名');
                                        $data->當月站位人數 = floatval($data->當月站位人數);
                                        $data->當月開線數 = floatval($data->當月開線數 );
                                        $data->當月開班數 = floatval($data->當月開班數 );
                                        $data->當月每人每日需求量 = floatval($data->當月每人每日需求量 );
                                        $data->當月每日更換頻率 = floatval($data->當月每日更換頻率 );
                                        $data->下月站位人數 = floatval($data->下月站位人數 );
                                        $data->下月開線數 = floatval($data->下月開線數 );
                                        $data->下月開班數 = floatval($data->下月開班數 );
                                        $data->下月每人每日需求量 = floatval($data->下月每人每日需求量 );
                                        $data->下月每日更換頻率 = floatval($data->下月每日更換頻率 );

                                    ?>
                                    <tr>
                                        <td><input type="hidden" id="number{{$loop->index}}"
                                                name="number{{$loop->index}}" value="{{$data->料號}}">{{$data->料號}}
                                        </td>
                                        <td><input type="hidden" id="name{{$loop->index}}" name="name{{$loop->index}}"
                                                value="{{$name}}">{{$name}}</td>
                                        <td><input type="hidden" id="client{{$loop->index}}"
                                                name="client{{$loop->index}}" value="{{$data->客戶別}}">{{$data->客戶別}}
                                        </td>
                                        <td><input type="hidden" id="machine{{$loop->index}}"
                                                name="machine{{$loop->index}}" value="{{$data->機種}}">{{$data->機種}}
                                        </td>
                                        <td><input type="hidden" id="production{{$loop->index}}"
                                                name="production{{$loop->index}}" value="{{$data->製程}}">{{$data->製程}}
                                        </td>
                                        <td class="table-light">{{$data->當月站位人數}}</td>
                                        <td class="table-light">{{$data->當月開線數}}</td>
                                        <td class="table-light">{{$data->當月開班數}}</td>
                                        <td class="table-light">{{$data->當月每人每日需求量}}</td>
                                        <td class="table-light">{{$data->當月每日更換頻率}}</td>
                                        <td class="table-light">{{$data->下月站位人數}}</td>
                                        <td class="table-light">{{$data->下月開線數}}</td>
                                        <td class="table-light">{{$data->下月開班數}}</td>
                                        <td class="table-light">{{$data->下月每人每日需求量}}</td>
                                        <td class="table-light">{{$data->下月每日更換頻率}}</td>

                                        <td><input class="checkbutton" type="checkbox" id="check{{$loop->index}}"
                                                name="check{{$loop->index}}"></td>
                                        <td></td>
                                        <td><input style="width: 120px;" class="form-control formcontrol-lg" type="text"
                                                id="remark{{$loop->index}}" name="remark{{$loop->index}}" required
                                                placeholder="{!! __('monthlyPRpageLang.reason') !!}"></td>
                                    </tr>

                                    <input type="hidden" id="count" name="count" value="{{$loop->count}}"></td>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

                        {{-- <label class="form-label">{!! __('monthlyPRpageLang.surepeople') !!}:</label>
                            <input class="form-control form-control-lg" type="text" id="jobnumber" name="jobnumber"
                                required style="width: 250px" oninput="if(value.length>9)value=value.slice(0,9)">
                            <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line--> --}}
                        <label class="form-label">{!! __('monthlyPRpageLang.surepeopleemail') !!}:</label>
                        <input class="form-control form-control-lg" type="email" id="email" name="email"
                            pattern=".+@pegatroncorp\.com" readonly style="width: 250px"
                            placeholder="xxx@pegatroncorp.com" value="{{$email}}">
                        <div class="text-center mt-3">
                            <input type="submit" id="submit" name="submit" class="btn btn-lg btn-primary"
                                value="{!! __('monthlyPRpageLang.submit') !!}">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <script src="{{ asset('/messages.js') }}"></script>
    <script src="{{ asset('js/popupNotice.js') }}"></script>
    <script src="{{ asset('js/month/teststand.js') }}"></script>

</body>
