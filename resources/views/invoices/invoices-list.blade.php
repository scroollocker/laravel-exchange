<div class="row" ng-init="init()">
    <div class="col-md-12">
        <div class="actions" style="margin-bottom: 10px;">
            <a href="#!/invoices/invoice" class="btn btn-primary"><i class="fa fa-plus"></i> Добавить заявку</a>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" style="padding: 15px;">
                Мои заявки
            </div>

            <div class="panel-body">

                <div class="alert alert-danger" ng-if="invoiceError.show">
                    <strong>Ошибка: </strong>
                    <p>@{{ invoiceError.message }}</p>
                </div>

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
                    <div class="alert alert-info">
                        Если заявка находится в статусе "На согласовании с банком" -
                        Вам необходимо дождаться оканчания согласования, уведомление придет Вам на почту.
                        Если сделка успешно обработана, Вы можете просмотреть детали ее исполнения,
                        нажав на соответствующее значение в столбце id.
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-condensed">
                            <thead>
                            <tr>
                                <th>id</th>
                                <th>Создана</th>
                                <th>Заканчивается</th>
                                <th>Сумма продажи</th>
                                <th>Валюта продажи</th>
                                <th>Курс покупки</th>
                                <th>Курс продажи</th>
                                <th>Сумма</th>
                                <th>Валюта</th>
                                <th>Статус сделки</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            {{--<tr ng-repeat="invoice in getInvoices()" ng-dblclick="openInvoice(invoice)" >--}}
                            <tr ng-repeat="invoice in getInvoices()" ng-dblclick="openInvoice(invoice)" ng-class="{'with-cursor':enableClick(invoice)}">
                                <td>@{{ invoice.id }}</td>
                                <td>@{{ normalizeDate(invoice.created_date) }}</td>
                                <td>@{{ normalizeDate(invoice.endDate) }}</td>
                                <td>@{{ invoice.cur_sum | roundFilterSum }}</td>
                                <td>@{{ invoice.cur_2.cur_name }}</td>
                                <td>@{{ invoice.final_sum / invoice.cur_sum  | roundFilter }}</td>
                                <td>@{{ invoice.cur_sum / invoice.final_sum | roundFilter }}</td>
                                <td>@{{ invoice.final_sum | roundFilterSum }}</td>
                                <td>@{{ invoice.cur_1.cur_name }}</td>
                                <td>@{{ invoice.state.name }}</td>
                                <td ng-if="invoice.state.code === 'OPENED'">
                                    {{--<button class="btn btn-warning btn-sm" ng-click="editInvoice(invoice.id)"><i class="fa fa-edit"></i></button>--}}
                                    <button  data-toggle="tooltip" data-placement="bottom" title="Отменить" class="btn btn-danger btn-sm" ng-click="removeInvoice(invoice.id)"><i class="fa fa-remove"></i></button>
                                </td>
                                <td ng-if="invoice.state.code !== 'OPENED'" class="text-center">
                                    <span>Управление не доступно</span>
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
