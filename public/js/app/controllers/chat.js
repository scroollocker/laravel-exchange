
app.controller('ChatTemplate', ['$scope', function ($scope) {
    $scope.chatList = [{
        'author': {
            'name': 'user1'
        }
    }];
    $scope.chatMessages = [];

    $scope.author = 1;
    $scope.isLoading = true;


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
