
app.controller('ChatTemplate', ['$scope', '$http', '$routeParams', '$timeout', function ($scope, $http, $routeParams,$timeout) {
    $scope.chatList = [];
    $scope.chatMessages = [
        {
            'author': {
                'name': 'user 1'
            },
            'author_id': 2,
            'message': 'test',
            'date_send': '15.11.2017 11:53:50'
        },
        {
            'author': {
                'name': 'user 2'
            },
            'author_id': 1,
            'message': 't e s t',
            'date_send': '15.11.2017 11:53:51'
        }
    ];

    $scope.author = 1;
    $scope.isLoading = false;
    $scope.isChatLoading = false;
    $scope.isMessageSending = false;

    $scope.selectedChat = 0;
    $scope.chatMessage = '';

    $scope.getChatList = function () {
        return $scope.chatList;
    };

    $scope.getChatMessages = function () {
        return $scope.chatMessages;
    };

    $scope.isAuthor = function (message) {
        return message.author_id == $scope.author;
    };

    $scope.loadChats = function () {

        console.log($routeParams);
        var urlParams = $routeParams;

        if (urlParams.invoice_id === null || urlParams.invoice_id === undefined) {
            $scope.chatMessages = [];
            $scope.chatList = [];
            console.log('Invoice ID is empty');
            return;
        }

        $scope.isChatLoading = true;
        $scope.chatMessages = [];

        $http.get('/chat/chats?invoice_id='+urlParams.invoice_id).then(function (response) {
            $scope.isChatLoading = false;
            response = response.data;
            if (response.status === true) {
                $scope.chatList = response.chats;
            }
            else {
                alert(response.message);
            }
        }, function (response) {
            $scope.isChatLoading = false;
            alert('Произошла системная ошибка.');
        });
    };

    $scope.loadMessages = function (chat_id) {
        $scope.chatMessages = [];
        $scope.selectedChat = 0;
        $scope.chatMessage = '';

        if (chat_id === null || chat_id === undefined) {
            return;
        }

        $scope.isLoading = true;
        $http.get('/chat/messages?chat_id='+chat_id).then(function (response) {
            $scope.isLoading = false;
            response = response.data;
            $scope.selectedChat = chat_id;
            if (response.status == true) {
                $scope.chatMessages = response.messages;
                $timeout(function () {
                    var objDiv = document.getElementById("asschat");
                    objDiv.scrollTop = objDiv.scrollHeight;
                },400);

            }
            else {
                alert(response.message);
            }
        }, function (response) {
            $scope.isLoading = false;
            alert('Произошла системная ошибка');
        });
    };

    $scope.sendMessage = function (message, form) {
        if (message == '') {
            console.log('Empty message');
            return;
        }
        else if ($scope.selectedChat === null || $scope.selectedChat === undefined || $scope.selectedChat == 0 ) {
            console.log('Empty chat_id');
            return;
        }
        else {
            if (!form.$valid) {
                console.log('Form invalid');
                return;
            }

            var request = {
                'message': message,
                'chat_id': $scope.selectedChat
            };

            $scope.isMessageSending = true;

            console.log('Request = ', request);
            $http.post('/chat/send', request).then(function (response) {
                $scope.isMessageSending = false;
                response = response.data;
                if (response.status === true) {
                    $scope.chatMessage = '';
                    $scope.loadMessages($scope.selectedChat);
                }
                else {
                    alert(response.message);
                }
            }, function (response) {
                $scope.isMessageSending = false;
                alert('Произошла системная ошибка');
            });
        }
    };

    $scope.loadChats();

}]);
