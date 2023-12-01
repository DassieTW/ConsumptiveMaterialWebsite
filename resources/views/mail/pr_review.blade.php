<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>{{ __('monthlyPRpageLang.page_name') }}</title>
    <style>
        table, th, td {
            border: 3px solid black;
            border-collapse: collapse;
        }

        table {
            width: 100%;
            height: 100%;
        }
    </style>
</head>

<body>
    <h2>{{ __('monthlyPRpageLang.page_name') }}</h1>
    <div class="w-100" style="height: 1ch;"></div><!-- </div>breaks cols to a new line-->
    <h2>Please have a look at the attached file.</h2>
    <table>
        <thead>
            <tr>
                <th>{{ __('monthlyPRpageLang.isn') }}</th>
                <th>{{ __('monthlyPRpageLang.pName') }}</th>
                <th>{{ __('monthlyPRpageLang.format') }}</th>
                <th>{{ __('monthlyPRpageLang.price') }}</th>
                <th>{{ __('monthlyPRpageLang.nowneed') }}</th>
                <th>{{ __('monthlyPRpageLang.nextneed') }}</th>
                <th>{{ __('monthlyPRpageLang.nowstock') }}</th>
                <th>{{ __('monthlyPRpageLang.transit') }}</th>
                <th>{{ __('monthlyPRpageLang.buyamount') }}</th>
                <th>{{ __('monthlyPRpageLang.buyprice') . '(' . $Currency1 . ')' }}</th>
                <th>{{ __('monthlyPRpageLang.buyprice') . '(' . $Currency2 . ' ' . $Rate . ')' }}</th>
                <th>{{ __('monthlyPRpageLang.moq') }}</th>
            </tr>
        </thead>
        <tbody>
            @for ($a = 0; $a < count($PN); $a++)
                <tr>
                    <td>{{ $PN[$a] }}</td>
                    <td>{{ $pName[$a] }}</td>
                    <td>{{ $Spec[$a] }}</td>
                    <td>{{ $Unit_price[$a] }}</td>
                    <td>{{ $nowNeed[$a] }}</td>
                    <td>{{ $nextNeed[$a] }}</td>
                    <td>{{ $Stock[$a] }}</td>
                    <td>{{ $in_Transit[$a] }}</td>
                    <td>{{ $ReqAmount[$a] }}</td>
                    <td>{{ $total_price1[$a] }}</td>
                    <td>{{ $total_price2[$a] }}</td>
                    <td>{{ $MOQ[$a] }}</td>
                </tr>
            @endfor
        </tbody>
    </table>
</body>

</html>
