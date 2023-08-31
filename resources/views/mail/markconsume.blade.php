<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ __('monthlyPRpageLang.consumemail') }}</title>
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
                <th>{!! __('monthlyPRpageLang.consume') !!}</th>
                <th>{!! __('monthlyPRpageLang.90isn') !!}</th>
            </tr>
            @for ($i = 0; $i < $count; $i++)
                <tr>
                    <td><input type="hidden" id="number{{ $i }}" name="number{{ $i }}"
                            value="{{ $datas[0][$i] }}">{{ $datas[0][$i] }}</td>
                    <td><input type="hidden" id="name{{ $i }}" name="name{{ $i }}"
                            value="{{ $datas[1][$i] }}">{{ $datas[1][$i] }}
                    </td>
                    <td><input type="hidden" id="client{{ $i }}" name="client{{ $i }}"
                            value="{{ $datas[2][$i] }}">{{ $datas[2][$i] }}</td>
                    <td><input type="hidden" id="machine{{ $i }}" name="machine{{ $i }}"
                            value="{{ $datas[3][$i] }}">{{ $datas[3][$i] }}</td>
                    <td><input type="hidden" id="production{{ $i }}" name="production{{ $i }}"
                            value="{{ $datas[4][$i] }}">{{ $datas[4][$i] }}
                    </td>
                    <td><input type="hidden" id="amount{{ $i }}" name="amount{{ $i }}"
                            value="{{ $datas[5][$i] }}">{{ $datas[5][$i] }}</td>
                    <td><input type="hidden" id="number90{{ $i }}" name="number90{{ $i }}"
                            value="{{ $datas[8][$i] }}">{{ $datas[8][$i] }}</td>
                    @if ($datas[6][$i])
                        <td> <img src="{{ $message->embed(public_path() . '/admin/img/mail/check.png') }}"
                                width="30px" height="30px" />

                        </td>
                    @else
                        <td> <img src="{{ $message->embed(public_path() . '/admin/img/mail/x.png') }}" width="30px"
                                height="30px" />
                        </td>
                    @endif
                    <td><input type="hidden" id="remark{{ $i }}" name="remark{{ $i }}"
                            value="{{ $datas[7][$i] }}">{{ $datas[7][$i] }}
                    </td>
                </tr>
            @endfor

        </table>
    </div>
    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->

    <h2>{!! __('monthlyPRpageLang.markconsume') !!}</h2>

    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
    <img src="{{ $message->embed(public_path() . '/admin/img/mail/email.png') }}" />

</body>

</html>
