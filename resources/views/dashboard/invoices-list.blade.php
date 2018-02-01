<div class="row" ng-init="init()">
    <div class="col-md-12">

        <div class="alert alert-danger" ng-if="invoiceError.show">
            <strong>Ошибка: </strong>
            <p>@{{ invoiceError.message }}</p>
        </div>

        <div class="filter-area form-group">

            <div class="row form-group">
                <div class="col-md-1">Покупка</div>
                <div class="col-md-3">
                    <select class="form-control" ng-model="filter.buy_cur" ng-options="item as item.cur_name for item in getCurrencies() track by item.id">

                    </select>
                </div>
                <div class="col-md-6">
                    <div class="form-inline">
                        <label>От:</label>
                        <input class="form-control" ng-model="filter.buy_from" type="number">
                        <label>До:</label>
                        <input class="form-control" ng-model="filter.buy_to" type="number">
                    </div>
                </div>

            </div>

            <div class="row form-group">
                <div class="col-md-1">Продажа</div>
                <div class="col-md-3">
                    <select class="form-control" ng-model="filter.sell_cur" ng-options="item as item.cur_name for item in getCurrencies() track by item.id">

                    </select>
                </div>
                <div class="col-md-6">
                    <div class="form-inline">
                        <label>От:</label>
                        <input class="form-control" ng-model="filter.sell_from" type="number">
                        <label>До:</label>
                        <input class="form-control" ng-model="filter.sell_to" type="number">
                    </div>
                </div>

            </div>

            <div class="row form-group">
                <div class="col-md-1">Курс</div>

                <div class="col-md-6">
                    <div class="form-inline">
                        <label>От:</label>
                        <input class="form-control" ng-model="filter.course_from" type="number">
                        <label>До:</label>
                        <input class="form-control" ng-model="filter.course_to" type="number">
                    </div>
                </div>

            </div>

            <div class="row form-group">

                <div class="col-md-6">
                    <button class="btn btn-primary" ng-click="doFilter()"><i class="fa fa-search"></i> Найти</button>
                    <button class="btn btn-default" ng-click="clearFilter()"><i class="fa fa-remove"></i> Сбросить</button>
                </div>

            </div>

        </div>

        <div class="panel panel-default">
            <div class="panel-heading" style="padding: 15px;">
                Мои заявки
            </div>

            <div class="panel-body">

                <div class="empty-invoice-list" ng-if="isInvoiceLoading == false && getInvoices().length == 0">
                    <div class="form-group text-center" style="font-size: 35px;">
                        <p><i class="fa fa-flag"></i></p>
                        <p>Нет доступных заявок</p>
                    </div>
                </div>
                <div class="loading-invoice-list" ng-if="isInvoiceLoading == true">
                    <div class="form-group text-center" style="font-size: 35px;">
                        <p><i class="fa fa-circle-o-notch fa-spin"></i></p>
                        <p>Идет загрузка...</p>
                    </div>
                </div>

                <div class="invoice-data-content" ng-if="isInvoiceLoading == false && getInvoices().length > 0">
                    <div class="table-responsive">
                        <table class="table table-striped table-condensed">
                            <thead>
                            <tr>
                                <th>id</th>
                                <th>Создана</th>
                                <th>Заканчивается</th>
                                <th>Автор</th>
                                <th>Сумма продажи</th>
                                <th>Валюта продажи</th>
                                <th>Курс продажи</th>
                                <th>Курс покупки</th>
                                <th>Сумма</th>
                                <th>Валюта</th>
                                <th>Статус</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="invoice in getInvoices()" style="cursor:pointer;">
                                <td>@{{ invoice.declare_id }}</td>
                                <td>@{{ normalizeDate(invoice.created_dt) }}</td>
                                <td>@{{ normalizeDate(invoice.end_dt) }}</td>
                                <td>@{{ invoice.user.email }}</td>
                                <td>@{{ invoice.sum_sell_nd }}</td>
                                <td>@{{ invoice.currency_sell.cur_name }}</td>
                                <td>@{{ invoice.sum_sell_nd / invoice.sum_buy_nd | roundFilter }}</td>
                                <td>@{{ invoice.sum_buy_nd / invoice.sum_sell_nd  | roundFilter }}</td>

                                {{--<td>@{{ invoice.course_nd }}</td>--}}
                                <td>@{{ invoice.sum_buy_nd }}</td>
                                <td>@{{ invoice.currency_buy.cur_name }}</td>
                                <td>@{{ invoice.state.name_v }}</td>
                                <td>
                                    <button data-toggle="tooltip" data-placement="bottom" title="Отправить предложение" class="btn btn-warning btn-sm" ng-click="sendOffer(invoice)"><i class="fa fa-handshake-o"></i></button>

                                </td>
                            </tr>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
