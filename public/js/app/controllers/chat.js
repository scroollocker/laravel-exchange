
app.controller('ChatTemplate', ['$scope', function ($scope) {
    $scope.chatList = [{
        'author': {
            'name': 'user1'
        }
    }];
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


    $scope.getChatList = function () {
        return $scope.chatList;
    };

    $scope.getChatMessages = function () {
        return $scope.chatMessages;
    };

    $scope.isAuthor = function (message) {
        return message.author_id == $scope.author;
    };

}]);
