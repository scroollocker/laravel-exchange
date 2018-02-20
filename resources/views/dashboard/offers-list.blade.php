<div class="row" ng-init="init()">
    <div class="col-md-12">

        <div class="alert alert-danger" ng-if="invoiceError.show">
            <strong>Ошибка: </strong>
            <p>@{{ invoiceError.message }}</p>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading" style="padding: 15px;">
                Мои предложения
            </div>

            <div class="panel-body">

                <div class="empty-invoice-list" ng-if="isInvoiceLoading == false && getOffers().length == 0">
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

                <div class="invoice-data-content" ng-if="isInvoiceLoading == false && getOffers().length > 0">
                    <div class="table-responsive">
                        <table class="table table-striped table-condensed">
                            <thead>
                            <tr>
                                <th>id</th>
                                <th>Создана</th>
                                <th>Заканчивается</th>
                                <th>Сумма продажи</th>
                                <th>Валюта продажи</th>
                                <th>Курс</th>
                                <th>Сумма</th>
                                <th>Валюта</th>
                                <th>Статус</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="offer in getOffers()"  ng-dblclick="openInvoice(offer)">
                                <td>@{{ offer.declare_id }}</td>
                                <td>@{{ normalizeDate(offer.detail.created_dt) }}</td>
                                <td>@{{ normalizeDate(offer.detail.end_dt) }}</td>
                                <td>@{{ offer.detail.sum_sell_nd | roundFilterSum }}</td>
                                <td>@{{ offer.detail.currency_sell.cur_name }}</td>
                                <td>@{{ offer.detail.course_nd | roundFilter}}</td>
                                <td>@{{ offer.detail.sum_buy_nd | roundFilterSum }}</td>
                                <td>@{{ offer.detail.currency_buy.cur_name }}</td>
                                <td>@{{ offer.state.name_v }}</td>
                                <td>
                                    {{--<button class="btn btn-warning btn-sm" ng-click=""><i class="fa fa-inf"></i></button>--}}

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
