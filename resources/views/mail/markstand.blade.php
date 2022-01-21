<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ __('monthlyPRpageLang.consumemail')}}</title>
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

            @for ($i = 0; $i < $count; $i++)
                <tr>
                    <td><input type="hidden" id="number{{$loop->index}}"
                            name="number{{$loop->index}}" value="{{$data->料號}}">{{$data->料號}}</td>
                    <td><input type="hidden" id="name{{$loop->index}}" name="name{{$loop->index}}"
                            value="{{$name}}">{{$name}}</td>
                    <td><input type="hidden" id="client{{$loop->index}}"
                            name="client{{$loop->index}}" value="{{$data->客戶別}}">{{$data->客戶別}}</td>
                    <td><input type="hidden" id="machine{{$loop->index}}"
                            name="machine{{$loop->index}}" value="{{$data->機種}}">{{$data->機種}}</td>
                    <td><input type="hidden" id="production{{$loop->index}}"
                            name="production{{$loop->index}}" value="{{$data->製程}}">{{$data->製程}}
                    </td>
                    <td><input style="width: 80px" type="number" id="nowpeople{{$loop->index}}"
                            name="nowpeople{{$loop->index}}" value="{{$data->當月站位人數}}"></td>
                    <td><input style="width: 80px" type="number" id="nowline{{$loop->index}}"
                            name="nowline{{$loop->index}}" value="{{$data->當月開線數}}"></td>
                    <td><input style="width: 80px" type="number" id="nowclass{{$loop->index}}"
                            name="nowclass{{$loop->index}}" value="{{$data->當月開班數}}"></td>
                    <td><input style="width: 80px" type="number" id="nowuse{{$loop->index}}"
                            name="nowuse{{$loop->index}}" value="{{$data->當月每人每日需求量}}"></td>
                    <td><input style="width: 80px" type="number" id="nowchange{{$loop->index}}"
                            name="nowchange{{$loop->index}}" value="{{$data->當月每日更換頻率}}"></td>
                    <td><input style="width: 80px" type="number" id="nextpeople{{$loop->index}}"
                            name="nextpeople{{$loop->index}}" value="{{$data->下月站位人數}}"></td>
                    <td><input style="width: 80px" type="number" id="nextline{{$loop->index}}"
                            name="nextline{{$loop->index}}" value="{{$data->下月開線數}}"></td>
                    <td><input style="width: 80px" type="number" id="nextclass{{$loop->index}}"
                            name="nextclass{{$loop->index}}" value="{{$data->下月開班數}}"></td>
                    <td><input style="width: 80px" type="number" id="nextuse{{$loop->index}}"
                            name="nextuse{{$loop->index}}" value="{{$data->下月每人每日需求量}}"></td>
                    <td><input style="width: 80px" type="number" id="nextchange{{$loop->index}}"
                            name="nextchange{{$loop->index}}" value="{{$data->下月每日更換頻率}}"></td>

                    <td></td><input type="hidden" id="comnowpeople{{$loop->index}}"
                        name="comnowpeople{{$loop->index}}" value="{{$data->當月站位人數}}">
                    <input type="hidden" id="comnowline{{$loop->index}}"
                        name="comnowline{{$loop->index}}" value="{{$data->當月開線數}}">
                    <input type="hidden" id="comnowclass{{$loop->index}}"
                        name="comnowclass{{$loop->index}}" value="{{$data->當月開班數}}">
                    <input type="hidden" id="comnowuse{{$loop->index}}"
                        name="comnowuse{{$loop->index}}" value="{{$data->當月每人每日需求量}}">
                    <input type="hidden" id="comnowchange{{$loop->index}}"
                        name="comnowchange{{$loop->index}}" value="{{$data->當月每日更換頻率}}">
                    <input type="hidden" id="comnextpeople{{$loop->index}}"
                        name="comnextpeople{{$loop->index}}" value="{{$data->下月站位人數}}">
                    <input type="hidden" id="comnextline{{$loop->index}}"
                        name="comnextline{{$loop->index}}" value="{{$data->下月開線數}}">
                    <input type="hidden" id="comnextclass{{$loop->index}}"
                        name="comnextclass{{$loop->index}}" value="{{$data->下月開班數}}">
                    <input type="hidden" id="comnextuse{{$loop->index}}"
                        name="comnextuse{{$loop->index}}" value="{{$data->下月每人每日需求量}}">
                    <input type="hidden" id="comnextchange{{$loop->index}}"
                        name="comnextchange{{$loop->index}}" value="{{$data->下月每日更換頻率}}">

                    @if ($datas[6][$i])
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

    <h2>{!! __('monthlyPRpageLang.markconsume')!!}</h2>

    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
    <img src="{{ $message->embed(public_path() . '/admin/img/mail/email.png') }}" />

</body>

</html>
