<div class="row" ng-init="init()">
    <div class="col-md-12">
        <div class="actions" style="margin-bottom: 10px;">
            <a href="#!/invoices/invoice" class="btn btn-primary"><i class="fa fa-plus"></i> Добавить заявку</a>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading" style="padding: 15px;">
                Список заявок
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

                <div class="invoice-data-content">
                    <div class="table-responsive">
                        <table class="table table-striped table-condensed">
                            <thead>
                            <tr>
                                <th>id</th>
                                <th>Создана</th>
                                <th>Заканчивается</th>
                                <th>Сумма продажи</th>
                                <th>Валюта продажи</th>
                                <th>Сумма</th>
                                <th>Валюта</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="invoice in getInvoices()" ng-click="openInvoice(invoice.id)">
                                <td>@{{ invoice.id }}</td>
                                <td>@{{ invoice.created_date }}</td>
                                <td>@{{ invoice.endDate }}</td>
                                <td>@{{ invoice.cur_sum }}</td>
                                <td>@{{ invoice.cur_1.cur_name }}</td>
                                <td>@{{ invoice.final_sum }}</td>
                                <td>@{{ invoice.cur_2.cur_name }}</td>
                            </tr>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
