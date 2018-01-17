<style>
    .chat {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .chat li {
        margin-bottom: 10px;
        padding-bottom: 5px;
        border-bottom: 1px dotted #B3A9A9;
    }

    .chat li.left .chat-body {
        margin-left: 60px;
    }

    .chat li.right .chat-body {
        margin-right: 60px;
    }

    .chat li .chat-body p {
        margin: 0;
        color: #777777;
    }

    .chat-panel .slidedown .glyphicon, .chat .glyphicon {
        margin-right: 5px;
    }

    .chat-panel-body {
        overflow-y: scroll;
        height: 250px;
    }

    ::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        background-color: #F5F5F5;
    }

    ::-webkit-scrollbar {
        width: 12px;
        background-color: #F5F5F5;
    }

    ::-webkit-scrollbar-thumb {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, .3);
        background-color: #555;
    }

</style>

<div class="container">



    <div class="row">
        <div class="col-md-3 col-sm-3" style="background-color: #fff; padding: 10px;">
            <div class="panel panel-default">
                <div class="panel-heading" data-toggle="collapse" data-target="#userListBody" style="cursor: pointer;">

                    <span class="fa fa-bars"></span>

                    <span>
                        <i class="fa fa-users"></i> Чаты
                    </span>
                </div>
            </div>
            <div class="panel-body collapse in" id="userListBody">

                <div class="loading-chats text-center" ng-if="isChatLoading">
                    <p><i class="fa fa-circle-o-notch fa-spin"></i></p>
                    <p>Загрузка...</p>
                </div>

                <div class="empty-chats text-center" ng-if="!isChatLoading && getChatList().length == 0">
                    <p><i class="fa fa-flag"></i></p>
                    <p>Нет доступных чатов. </p>
                </div>

                <div class="table-responsive" ng-if="!isChatLoading && getChatList().length > 0">
                    <table class="table table-bordered">
                        <tr ng-repeat="chat in getChatList()">
                            <td><a href="" ng-click="loadMessages(chat)"><i
                                            class="fa fa-user"></i> Заявка #@{{ chat.invoice.declare_id }}</a></td>
                        </tr>
                    </table>
                </div>

            </div>
        </div>
        <div class="col-md-8 col-sm-8">
            <div class="chat-panel panel panel-primary">
                <div class="row" ng-if="selected_chat !== null" style="padding: 15px; border-radius: 3px;">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tr>
                                <td>Сделка: </td>
                                <td>#@{{ selected_chat.invoice.declare_id }}</td>
                            </tr>
                            <tr>
                                <td>Сумма продажи: </td>
                                <td>@{{ selected_chat.invoice.sum_sell_nd }} @{{ selected_chat.invoice.currency_sell.cur_code }}</td>
                            </tr>
                            <tr>
                                <td>Сумма покупки: </td>
                                <td>@{{ selected_chat.invoice.sum_buy_nd }} @{{ selected_chat.invoice.currency_buy.cur_code }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="chat-empty text-center" style="font-size: 30px; color: #BDBDBD;" ng-if="isLoading">
                    <p>
                        <i class="fa fa-circle-o-notch fa-spin"></i>
                    </p>
                    <p> Ожидайте загрузки...</p>
                </div>
                <div class="chat-empty text-center" style="font-size: 30px; color: #BDBDBD;"
                     ng-if="!isLoading && getChatMessages().length == 0">
                    <p>
                        <i class="fa fa-flag"></i>
                    </p>
                    <p> Нет сообщений</p>
                </div>
                <div class="chat-body" ng-if="getChatMessages().length > 0">
                    <div class="panel-heading">
                        <span class="fa fa-comment"></span> Список сообщений

                    </div>
                    <div id="asschat" class="panel-body chat-panel-body">
                        <ul class="chat">
                            <li ng-class="{'left': !isAuthor(message), 'right': isAuthor(message)}" class="clearfix"
                                ng-repeat="message in getChatMessages()">
                        <span ng-class="{'pull-left': !isAuthor(message), 'pull-right': isAuthor(message)}"
                              class="chat-img">
                            <img src="http://placehold.it/50/55C1E7/fff&text=U" alt="User Avatar" class="img-circle"/>
                        </span>
                                <div class="chat-body clearfix">
                                    <div class="header">
                                        <strong ng-class="{'pull-right': isAuthor(message)}"
                                                class="primary-font"> @{{ message.author.name }} </strong>
                                        <small ng-class="{'pull-right': !isAuthor(message)}" class=" text-muted">
                                            <span class="fa fa-clock-o"></span> @{{ message.date_send }}
                                        </small>
                                    </div>
                                    <p>
                                        @{{ message.message }}
                                    </p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="panel-footer">
                        <form name="sendMessageForm">
                            {{ csrf_field() }}
                            <div class="input-group">
                                <input id="btn-input" type="text" class="form-control input-sm"
                                       placeholder="Введите ваше сообщение..." ng-model="chatMessage" required maxlength="500"/>
                                <span class="input-group-btn">
                                    <button ng-disabled="isMessageSending" class="btn btn-warning btn-sm" id="btn-chat" ng-click="sendMessage(chatMessage, sendMessageForm)">
                                    <i class="fa fa-send"></i> Отправить</button>
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>