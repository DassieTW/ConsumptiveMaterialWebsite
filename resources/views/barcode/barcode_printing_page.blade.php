<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sheets of Paper</title>
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/sheets-of-paper-a4.css') }}">
    <style>
        table {
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 0.03cm dashed grey;
            padding: 0.1cm;
            /*                padding-bottom: 0.05cm;
                                padding-top: 0.05cm;*/
        }

        img {
            max-height: 100%;
            max-width: 100%;
            width: auto;
            height: auto;
        }
    </style>
</head>

<body class="document">
    @if( count( \Session::get('isnSepCount')) > 0 )
    <div class="page" contenteditable="true">
        @php
            $rowMax = 0;
            $nextPageCount = 0;
        @endphp
        <table>
            <tr>
            @for ($a = 0; $a < count( \Session::get('isnSepCount')); $a++)
                @for ($b = 0; $b < \Session::get('isnSepCount')[$a] ; $b++)
                    @php
                        $rowMax = $rowMax + 1;
                    @endphp
                    <td style="height:1.5cm; width:7cm;" align="center">
                        <img src="{{asset('storage/barcodeImg/' . \Session::getId() . '--isn--' . $a . '.png')}}">
                    </td>

                    @if( $rowMax % 3 == 0 )
                        </tr>
                        @php
                            $nextPageCount = $nextPageCount + 1;
                        @endphp
                        <tr>
                    @endif
                    @if( $nextPageCount == 19 )
                        </table>
                        </div>
                        <div class="page" contenteditable="true">
                        <table>
                        @php
                            $nextPageCount = 0;
                        @endphp
                    @endif
                @endfor
            @endfor
        </table>
    </div>
    @endif
    @if( count( \Session::get('locSepCount')) > 0 )
    <div class="page" contenteditable="true">
        @php
            $rowMax2 = 0;
            $nextPageCount2 = 0;
        @endphp
        <table>
            <tr>
            @for ($a = 0; $a < count( \Session::get('locSepCount')); $a++)
                @for ($b = 0; $b < \Session::get('locSepCount')[$a] ; $b++)
                    @php
                        $rowMax2 = $rowMax2 + 1;
                    @endphp
                    <td style="height:1.5cm; width:7cm;" align="center">
                        <img src="{{asset('storage/barcodeImg/' . \Session::getId() . '--loc--' . $a . '-2.png')}}">
                    </td>

                    @if( $rowMax2 % 4 == 0 )
                        </tr>
                        @php
                            $nextPageCount2 = $nextPageCount2 + 1;
                        @endphp
                        <tr>
                    @endif
                    @if( $nextPageCount2 == 19 )
                        </table>
                        </div>
                        <div class="page" contenteditable="true">
                        <table>
                        @php
                            $nextPageCount2 = 0;
                        @endphp
                    @endif
                @endfor
            @endfor
        </table>
    </div>
    @endif
    

    <script src="{{ asset('/js/app.js') }}"></script>
    <script src="{{ asset('/admin/js/app.js') }}"></script>
    <script src="{{ asset('/js/jquery.loadingModal.min.js') }}"></script>
    <script src="{{ asset('js/popupNotice.js') }}"></script>
    <script type="text/javascript">
        window.print();
    </script>
    <script>
        $( window ).on( "unload", function() {
            console.log('hi') ; //test
        });
    </script>
</body>

</html>