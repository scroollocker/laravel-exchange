<html>
<head>

</head>
<body>

<style>
    .paycheck {
        border: 1px dashed #ccc;
        padding: 20px;
        width: 266px;
        background-color: #fff;
    }

    .paycheck hr {
        border-top: 1px dashed grey;
        margin: 10px 0;
    }

    @media print {
        body * {
            visibility: hidden;
        }
        #printcontent, #printcontent * {
            visibility: visible;
        }
    }
</style>

{{--<div>--}}
    {{--<h2>Информация по операции</h2>--}}
{{--</div>--}}

{{--<div class="paycheck" id="printcontent">--}}
    {{--<div class="">--}}
        {{--<strong >Дата: {{ $created_dt }}</br> Обмен Иностранной Валюты</strong>--}}
        {{--<hr>--}}
        {{--<table>--}}
            {{--<tbody>--}}
            {{--<tr>--}}
                {{--<td>Дебетуемый Счет:</td>--}}
                {{--<td >{{ $acc_dt['num_v'] }}</td>--}}
            {{--</tr>--}}
            {{--<tr>--}}
                {{--<td>Кредитуемый Счет:</td>--}}
                {{--<td >{{ $acc_ct['num_v'] }}</td>--}}
            {{--</tr>--}}
            {{--<tr>--}}
                {{--<td>Курс:</td>--}}
                {{--<td >{{ $course_nd }}</td>--}}
            {{--</tr>--}}
            {{--<tr>--}}
                {{--<td>Сумма покупки:</td>--}}
                {{--<td >{{ $sum_sell_nd }} ({{ $currency_sell['cur_code'] }})</td>--}}
            {{--</tr>--}}
            {{--<tr>--}}
                {{--<td>Сумма продажи:</td>--}}
                {{--<td >{{ $sum_buy_nd }} ({{ $currency_buy['cur_code'] }})</td>--}}
            {{--</tr>--}}

            {{--</tbody>--}}
        {{--</table>--}}
    {{--</div>--}}
{{--</div>--}}

{!! $html !!}

</body>
</html>