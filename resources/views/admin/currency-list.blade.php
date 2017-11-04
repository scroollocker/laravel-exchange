<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <button class="btn btn-primary" onclick="$('#currencyModal').modal('show');"><i class="fa fa-plus"></i> Добавить валюту</button>
        </div>
        <div class="panel panel-default" >
            <div class="panel-heading" style="padding: 15px;">
                Список валют
            </div>

            <div class="panel-body">
                <div class="form-group">
                    <input class="form-control" placeholder="Поиск">
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Код</th>
                                <th>Наименование</th>
                                <th>Сделки</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>USD</td>
                                <td>Доллар</td>
                                <td><input type="checkbox" checked></td>
                                <td>
                                    <button class="btn btn-success btn-xs"><i class="fa fa-edit"></i></button>

                                    <button class="btn btn-warning btn-xs"><i class="fa fa-lock"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>RUB</td>
                                <td>Рубль</td>
                                <td><input type="checkbox" checked></td>
                                <td>
                                    <button class="btn btn-success btn-xs"><i class="fa fa-edit"></i></button>

                                    <button class="btn btn-warning btn-xs"><i class="fa fa-lock"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>3</td>
                                <td>EUR</td>
                                <td>Евро</td>
                                <td><input type="checkbox" ></td>
                                <td>
                                    <button class="btn btn-success btn-xs"><i class="fa fa-edit"></i></button>

                                    <button class="btn btn-warning btn-xs"><i class="fa fa-unlock"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal -->
<div id="currencyModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Добавление валюты</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="form-group">
                       <table class="table">
                           <tr>
                               <td><label>Код: </label></td>
                               <td><input class="form-control" type="text"></td>
                           </tr>
                           <tr>
                               <td><label>Наименование: </label></td>
                               <td><input class="form-control"  type="tel"></td>
                           </tr>
                       </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" >Применить</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
            </div>
        </div>

    </div>
</div>