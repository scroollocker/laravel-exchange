<div class="row">
    <div class="col-md-12">

        <div class="panel panel-default" >
            <div class="panel-heading" style="padding: 15px;">
                Выбор партнеров
            </div>

            <div class="panel-body">
                <div class="loading-area" ng-if="partnersLoading">
                    <div class="form-group text-center" style="font-size: 35px;">
                        <p><i class="fa fa-circle-o-notch fa-spin"></i></p>
                        <p>Идет загрузка...</p>
                    </div>
                </div>
                <div class="empty-area" ng-if="!partnersLoading && getPartners().length == 0">
                    <div class="form-group text-center" style="font-size: 35px;">
                        <p><i class="fa fa-flag"></i></p>
                        <p>Нет доступных партнеров</p>
                    </div>
                </div>
                <div class="form-group accounts-area" ng-if="!partnersLoading && getPartners().length > 0">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Пользователь</th>
                                    <th>Рейтинг</th>
                                    <th>Показывать сделки</th>
                                    <th>Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr ng-repeat="partner in getPartners()">
                                    <td>@{{ partner.id }}</td>
                                    <td>@{{ partner.partner_email }}</td>
                                    <td>@{{ partner.rating }}</td>
                                    <td><input type="checkbox" disabled ng-true-value="1" ng-false-value="2" ng-model="partner.state"></td>
                                    <td>
                                        <button ng-click="setPartnerState(partner)" ng-if="partner.state == 1" class="btn btn-warning btn-sm">
                                            <i class="fa fa-lock"></i>
                                        </button>
                                        <button ng-click="setPartnerState(partner)" ng-if="partner.state == 2" class="btn btn-warning btn-sm">
                                            <i class="fa fa-unlock"></i>
                                        </button>
                                        <button ng-click="removePartner(partner)" class="btn btn-danger btn-sm">
                                            <i class="fa fa-remove"></i>
                                        </button>
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
