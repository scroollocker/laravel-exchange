

{{--<nav ng-init="paymentInit()">--}}
    {{--<ol class="cd-breadcrumb triangle">--}}
        {{--<li><a>Шаг 1</a></li>--}}
        {{--<li><a>Шаг 2</a></li>--}}
        {{--<li><a>Шаг 3</a></li>--}}
        {{--<li><a>Шаг 4</a></li>--}}
        {{--<li><a>Шаг 5</a></li>--}}
        {{--<li><a>Шаг 6</a></li>--}}
        {{--<li class="current"><a>Шаг 7</a></li>--}}
    {{--</ol>--}}
{{--</nav>--}}

<div ng-if="invoiceError.show" class="alert alert-danger">
    <strong>Ошибка:</strong>
    <p>@{{ invoiceError.message }}</p>
</div>

<div class="row" ng-if="isInvoiceLoading">
    <div class="col-md-12">
        <div class="invoice-loading text-center" style="font-size: 32px;">
            <p><i class="fa fa-circle-o-notch fa-spin"></i></p>
            <p>Производится загрузка... Ждите.</p>
        </div>
    </div>
</div>

<div class="row" ng-if="!isInvoiceLoading && getPayments().length == 0">
    <div class="col-md-12">
        <div class="invoice-loading text-center" style="font-size: 32px;">
            <p><i class="fa fa-flag"></i></p>
            <p>Проводки по данной заявке не найдены.</p>
        </div>
    </div>
</div>

<div ng-if="!isInvoiceLoading && getPayments().length > 0">
    <div style="font-size: 25px;" class="text-center alert alert-success">
        <p><i class="fa fa-check-square"></i></p>
        <p>Операции по сделке завершены</p>
        <a href="#!/invoices/lists" class="btn btn-default">Вернуться к списку заявок</a>
    </div>

    <div class="row" >
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Список проводок
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Счет получения ДС</th>
                                <th>Счет взымания ДС</th>
                                <th>Валюта</th>
                                <th>Сумма</th>
                                <th>КНП</th>
                                <th>Комментарий</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="payment in getPayments()">
                                <td>@{{ payment.num }}</td>
                                <td>@{{ payment.acc_ct }}</td>
                                <td>@{{ payment.acc_dt }}</td>
                                <td>@{{ getCurName(payment.cur) }}</td>
                                <td>@{{ payment.sum }}</td>
                                <td>@{{ payment.knp }}</td>
                                <td>@{{ payment.comment }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>





