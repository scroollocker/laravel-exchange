<div class="row">
    <div class="col-md-12">
        <div class="form-group">
            <button class="btn btn-primary" onclick="$('#userModal').modal('show');"><i class="fa fa-plus"></i> Добавить пользователя</button>
        </div>
        <div class="panel panel-default" >
            <div class="panel-heading" style="padding: 15px;">
                Список пользователей
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
                                <th>Login</th>
                                <th>Идентификатор</th>
                                <th>Заблокирован</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>User1</td>
                                <td>1234</td>
                                <td><input type="checkbox" checked></td>
                                <td>
                                    <button class="btn btn-success btn-xs"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-danger btn-xs"><i class="fa fa-remove"></i></button>
                                    <button class="btn btn-warning btn-xs"><i class="fa fa-unlock"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>User2</td>
                                <td>1254</td>
                                <td><input type="checkbox" ></td>
                                <td>
                                    <button class="btn btn-success btn-xs"><i class="fa fa-edit"></i></button>
                                    <button class="btn btn-danger btn-xs"><i class="fa fa-remove"></i></button>
                                    <button class="btn btn-warning btn-xs"><i class="fa fa-lock"></i></button>
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
<div id="userModal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Добавление пользователя</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="form-group">
                       <table class="table">
                           <tr>
                               <td><label>Login: </label></td>
                               <td><input class="form-control" type="text"></td>
                           </tr>
                           <tr>
                               <td><label>Телефон: </label></td>
                               <td><input class="form-control"  type="tel"></td>
                           </tr>
                           <tr>
                               <td><label>E-mail: </label></td>
                               <td><input class="form-control"  type="email"></td>
                           </tr>
                           <tr>
                               <td><label>Идентификатор в АБС: </label></td>
                               <td><input class="form-control"  type="text"></td>
                           </tr>
                           <tr>
                               <td colspan="2">
                                   <div class="table-responsive">
                                       <table class="table table-bordered">
                                           <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Валюта</th>
                                                    <th>Кредит</th>
                                                </tr>
                                           </thead>
                                           <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>USD</td>
                                                    <td>1000</td>
                                                </tr>
                                                <tr>
                                                    <td>2</td>
                                                    <td>RUB</td>
                                                    <td>2000</td>
                                                </tr>
                                           </tbody>
                                       </table>
                                   </div>

                               </td>
                           </tr>
                           <tr>
                               <td><label>Количество сделок: </label></td>
                               <td><input class="form-control"  type="number"></td>
                           </tr>
                           <tr>
                               <td><label>До какой даты осуществляет сделки: </label></td>
                               <td><input class="form-control"  type="date"></td>
                           </tr>
                           <tr>
                               <td><label>Дополнительная информация: </label></td>
                               <td><input class="form-control"  type="text"></td>
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