app.controller('UserController', ['$scope', '$http', function($scope, $http) {

    $scope.userListLoading = false;
    $scope.userList = [];
    $scope.editedUser = {};

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

    $scope.isBlocked = function(user) {
        return user.blocked == '1';
    };

    $scope.editUser = function(user) {
        $scope.editedUser = angular.copy(user);
        $('#userModal').modal('show');
    };

}]);