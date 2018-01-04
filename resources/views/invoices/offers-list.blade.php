<nav>
    <ol class="cd-breadcrumb triangle">
        <li><a>Шаг 1</a></li>
        <li><a>Шаг 2</a></li>
        <li><a>Шаг 3</a></li>
        <li><a>Шаг 4</a></li>
        <li class="current"><a>Шаг 5</a></li>
        <li><a>Шаг 6</a></li>
        <li><a>Шаг 7</a></li>
    </ol>
</nav>

<div class="row" ng-init="init()">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading" style="padding: 15px;">
                Список предложений по заявке
            </div>

            <div class="panel-body">

                <div class="alert alert-danger" ng-if="invoiceError.show">
                    <strong>Ошибка: </strong>
                    <p>@{{ invoiceError.message }}</p>
                </div>

                <div class="empty-invoice-list" ng-if="isOffersLoading == false && getOffers().length == 0">
                    <div class="form-group text-center" style="font-size: 35px;">
                        <p><i class="fa fa-flag"></i></p>
                        <p>Нет доступных предложений</p>
                    </div>
                </div>
                <div class="loading-invoice-list" ng-if="isOffersLoading == true">
                    <div class="form-group text-center" style="font-size: 35px;">
                        <p><i class="fa fa-circle-o-notch fa-spin"></i></p>
                        <p>Идет загрузка...</p>
                    </div>
                </div>

                <div class="invoice-data-content" ng-if="isOffersLoading == false && getOffers().length > 0">
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
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="offer in getOffers()" ng-dblclick="openOffer(offer.detail_id)" style="cursor:pointer;">
                                <td>@{{ offer.id }}</td>
                                <td>@{{ offer.created_date }}</td>
                                <td>@{{ offer.endDate }}</td>
                                <td>@{{ offer.sum_1 }}</td>
                                <td>@{{ offer.cur_1.name }}</td>
                                <td>@{{ offer.course }}</td>
                                <td>@{{ offer.sum_2 }}</td>
                                <td>@{{ offer.cur_2.name }}</td>
                                <td>
                                    <button class="btn btn-success btn-sm" ng-click="agreeOffer(offer.id)"><i class="fa fa-check"></i></button>
                                    <button class="btn btn-danger btn-sm" ng-click="disagreeOffer(offer.id)"><i class="fa fa-remove"></i></button>
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
