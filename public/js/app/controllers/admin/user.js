app.controller('UserController', ['$scope', '$http', function($scope, $http) {

    $scope.userListLoading = false;
    $scope.userList = [];

    $scope.getUserList = function () {
        return $scope.userList;
    };

    $scope.loadUserList = function () {

        $scope.userList = [];
        $scope.userListLoading = true;

        $http.get('/users/get').then(function (response) {
            $scope.userListLoading = false;
            response = response.data;
            if (response.status == true) {
                $scope.userList = response.users;
            }
            else {
                alert(response.message);
            }
        }, function (response) {
            $scope.userListLoading = false;
            alert('Произошла системная ошибка');
        });


    };

    $scope.loadUserList();

}]);