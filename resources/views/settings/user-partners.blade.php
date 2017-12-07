<div class="row">
    <div class="col-md-12">

        <div class="form-group">
            <button ng-click="addPartner()" class="btn btn-primary"><i class="fa fa-plus"></i> Добавить партнера
            </button>
        </div>

        <div class="panel panel-default">
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
                                <th>Показывать сделки</th>
                                <th>Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr ng-repeat="partner in getPartners()">
                                <td>@{{ partner.id }}</td>
                                <td>@{{ partner.partner_email }}</td>
                                <td><input type="checkbox" disabled ng-true-value="1" ng-false-value="2"
                                           ng-model="partner.state"></td>
                                <td>
                                    <button ng-click="setPartnerState(partner)" ng-if="partner.state == 1"
                                            class="btn btn-warning btn-sm">
                                        <i class="fa fa-lock"></i>
                                    </button>
                                    <button ng-click="setPartnerState(partner)" ng-if="partner.state == 2"
                                            class="btn btn-warning btn-sm">
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

<!-- Modal -->
<div id="addPartnerModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Добавление партнера</h4>
            </div>
            <div class="modal-body">
                <div class="add-partner-detail" ng-if="isAddView">
                    <div class="form-group">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <p>Добавление партнера</p>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label for="enable-email">
                                        <input class="form-control" id="enable-email" type="text" ng-model="selectedUser.email" disabled>
                                    </label>
                                    <label for="enable-deals">
                                        <input class="form-control" id="enable-deals" type="checkbox" ng-model="selectedUser.state" ng-true-value="1" ng-false-value="2">
                                    </label>
                                </div>
                                <div class="form-group">
                                    <div class="btn-group">
                                        <button class="btn btn-success">
                                            <i class="fa fa-save"></i> Сохранить
                                        </button>
                                        <button class="btn btn-default" ng-click="closeAddView()">
                                            <i class="fa fa-close"></i> Отмена
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="find-box" ng-if="!isAddView">
                    <div class="add-partner-search" ng-if="!userLoading && getUserList().length > 0">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="text" class="form-control" ng-model="userSearch" placeholder="Поиск...">
                                <span class="input-group-btn">
                                <button class="btn btn-default">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>E-Mail</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr ng-repeat="user in getUserList()">
                                    <td>user.id</td>
                                    <td>user.email</td>
                                    <td>
                                        <button ng-click="showAddView(user)" class="btn btn-warning btn-sm">
                                            <i class="fa fa-plus-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td colspan="3" class="text-center">
                                        <ul class="pagination">
                                            <li ng-class="{disabled: isFirstPage()}" ng-disabled="isFirstPage()"><a href="#">&lt;&lt;&lt; Первая</a></li>
                                            <li ng-class="{disabled: isFirstPage()}" ng-disabled="isFirstPage()"><a href="#">&lt; Назад</a></li>

                                            <li ng-repeat="page in getPages()"><a ng-click="loadUsers(page)">@{{ page }}</a></li>

                                            <li ng-class="{disabled: isLastPage()}" ng-disabled="isLastPage()"><a href="#">Вперед &gt;</a></li>
                                            <li ng-class="{disabled: isLastPage()}" ng-disabled="isLastPage()"><a href="#">Последняя &gt;&gt;&gt;</a></li>
                                        </ul>
                                    </td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="add-partner-not-found" ng-if="!userLoading && getUserList().length == 0">
                        <div class="form-group text-center" style="font-size: 35px;">
                            <p><i class="fa fa-flag"></i></p>
                            <p>Пользователи не найдены</p>
                        </div>
                    </div>
                    <div class="add-partner-loading" ng-if="userLoading">
                        <div class="form-group text-center" style="font-size: 35px;">
                            <p><i class="fa fa-circle-o-notch fa-spin"></i></p>
                            <p>Идет загрузка...</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-close"></i> Закрыть
                </button>
            </div>
        </div>

    </div>
</div>
