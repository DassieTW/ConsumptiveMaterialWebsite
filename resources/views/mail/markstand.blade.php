<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ __('monthlyPRpageLang.standmail')}}</title>
    <style>
        .form-control {
            display: block;
            width: 100%;
            padding: .375rem .75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
        }
    </style>

</head>

<body>

    <div class="table-responsive">
        <table class="table">

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
            </tr>

            @for ($i = 0; $i < $count; $i++) <tr>
                <td><input type="hidden" id="number{{$i}}" name="number{{$i}}"
                        value="{{$datas[0][$i]}}">{{$datas[0][$i]}}</td>
                <td><input type="hidden" id="name{{$i}}" name="name{{$i}}" value="{{$datas[1][$i]}}">{{$datas[1][$i]}}
                </td>
                <td><input type="hidden" id="client{{$i}}" name="client{{$i}}"
                        value="{{$datas[2][$i]}}">{{$datas[2][$i]}}</td>
                <td><input type="hidden" id="machine{{$i}}" name="machine{{$i}}"
                        value="{{$datas[3][$i]}}">{{$datas[3][$i]}}</td>
                <td><input type="hidden" id="production{{$i}}" name="production{{$i}}"
                        value="{{$datas[4][$i]}}">{{$datas[4][$i]}}
                </td>

                <td><input type="hidden" id="nowpeople{{$i}}" name="nowpeople{{$i}}" value="{{$datas[5][$i]}}">{{$datas[5][$i]}}
                </td>
                <td><input type="hidden" id="nowline{{$i}}" name="nowline{{$i}}" value="{{$datas[6][$i]}}">{{$datas[6][$i]}}</td>
                <td><input type="hidden" id="nowclass{{$i}}" name="nowclass{{$i}}" value="{{$datas[7][$i]}}">{{$datas[7][$i]}}</td>
                <td><input type="hidden" id="nowuse{{$i}}" name="nowuse{{$i}}" value="{{$datas[8][$i]}}">{{$datas[8][$i]}}</td>
                <td><input type="hidden" id="nowchange{{$i}}" name="nowchange{{$i}}" value="{{$datas[9][$i]}}">{{$datas[9][$i]}}
                </td>
                <td><input type="hidden" id="nextpeople{{$i}}" name="nextpeople{{$i}}" value="{{$datas[10][$i]}}">{{$datas[10][$i]}}
                </td>
                <td><input type="hidden" id="nextline{{$i}}" name="nextline{{$i}}" value="{{$datas[11][$i]}}">{{$datas[11][$i]}}</td>
                <td><input type="hidden" id="nextclass{{$i}}" name="nextclass{{$i}}" value="{{$datas[12][$i]}}">{{$datas[12][$i]}}
                </td>
                <td><input type="hidden" id="nextuse{{$i}}" name="nextuse{{$i}}" value="{{$datas[13][$i]}}">{{$datas[13][$i]}}
                </td>
                <td><input type="hidden" id="nextchange{{$i}}" name="nextchange{{$i}}"
                        value="{{$datas[14][$i]}}">{{$datas[14][$i]}}</td>

                @if ($datas[15][$i])
                <td> <img src="{{ $message->embed(public_path() . '/admin/img/mail/check.png') }}" width="30px"
                        height="30px" />

                </td>
                @else
                <td> <img src="{{ $message->embed(public_path() . '/admin/img/mail/x.png') }}" width="30px"
                        height="30px" />
                </td>
                @endif

                </tr>
                @endfor

        </table>
    </div>
    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

    <h2>{!! __('monthlyPRpageLang.markstand')!!}</h2>

    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
    <img src="{{ $message->embed(public_path() . '/admin/img/mail/email.png') }}" />

</body>

</html>
