<div class="row">
    <div class="col-md-12">

        <div class="panel panel-default" >
            <div class="panel-heading" style="padding: 15px;">
                Настройки
            </div>

            <div class="panel-body">
                <form>
                   <table>
                       <tr>
                           <td><label>Блокировка после количества ошибок:</label></td>
                           <td><input ng-model="editSettings.settings_err_count" required class="form-control" type="number"></td>
                       </tr>
                       <tr>
                           <td><label>Дней для операций</label></td>
                           <td><input ng-model="editSettings.settings_day" class="form-control" required type="number"></td>
                       </tr>
                       <tr>
                           <td><label>Количество проводимых операций</label></td>
                           <td><input ng-model="editSettings.settings_op_count" class="form-control" required type="number"></td>
                       </tr>
                   </table>
                </form>

                <div class="form-group text-center" style="margin-top: 15px;">
                    <div class="btn-group">
                        <button class="btn btn-success"><i class="fa fa-save"></i> Сохранить</button>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
