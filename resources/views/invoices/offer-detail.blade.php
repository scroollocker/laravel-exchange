<div class="row" ng-init="loadInvoice()">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading" style="padding: 15px;">
                Информация о предложении
            </div>

            <div class="panel-body">
                <div class="alert alert-danger" ng-if="detailOfferError.show">
                    <strong>Ошибка: </strong> @{{ detailOfferError.message }}
                </div>

                <div class="loading-invoice-list" ng-if="isDetailOffersLoading == true">
                    <div class="form-group text-center" style="font-size: 35px;">
                        <p><i class="fa fa-circle-o-notch fa-spin"></i></p>
                        <p>Идет загрузка...</p>
                    </div>
                </div>

                <table class="table" ng-if="isDetailOffersLoading == false">
                    <tbody>
                    <tr>
                        <td><label>Тип сделки: </label></td>
                        <td>
                            <p>Предложение</p>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Валюта: </label></td>
                        <td>
                            <p>@{{ invoice.cur_1.cur_name }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Контр валюта: </label></td>
                        <td>
                            <p>@{{ invoice.cur_2.cur_name }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Сумма: </label></td>
                        <td>
                            <p>@{{ invoice.cur_sum }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Курс: </label></td>
                        <td>
                            <p>@{{ invoice.cur_curs }}</p>
                        </td>
                    </tr>
                    <tr>
                        <td><label>Сумма по курсу: </label></td>
                        <td>
                            <p>@{{ invoice.final_sum }}</p>
                        </td>
                    </tr>

                    <tr>
                        <td><label> Дата окончания сделки: </label></td>
                        <td>
                            <p>@{{ invoice.endDate }}</p>
                        </td>
                    </tr>
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="2">
                                <div class="text-center ">
                                    <div class="btn-group">
                                        <button class="btn btn-primary">Отклонить</button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tfoot>

                </table>


            </div>
        </div>
    </div>
</div>