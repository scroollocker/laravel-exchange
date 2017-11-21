app.controller('UserController', ['$scope', '$http', 'AppUtils', function($scope, $http, AppUtils) {

    $scope.userListLoading = false;
    $scope.userList = [];
    $scope.editedUser = {};

    $scope.userError = {
        'show': false,
        'message': ''
    };

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

    $scope.addUser = function() {
        $scope.editedUser = {};
        $('#userModal').modal('show');
    };

    $scope.blockUser = function (user) {
        var blockState = ($scope.isBlocked(user)) ? '0' : '1';

        var blockConfirm;
        if (blockState == '0') {
            blockConfirm = 'разблокировать';
        }
        else {
            blockConfirm = 'заблокировать';
        }

        if (!confirm('Вы действительно хотите ' + blockConfirm + '?')) {
            return;
        }

        var request = {
            'user_id': user.id,
            'block_state': blockState
        };
        $http.post('/user/block', request).then(function (response) {
            response = response.data;
            if (response.status == true) {
                $scope.loadUserList();
            }
            else {
                alert(response.message);
            }
        }, function (response) {
           alert('Произошла системная ошибка');
        });
    };

    $scope.saveEditUser = function (editedUser, form) {
        if (form.$invalid) {
            $scope.userError.message = 'Форма заполнена неверно';
            AppUtils.showAlertBox($scope.userError);
            return;
        }

        var request = $scope.editedUser;

        $http.post('/user/edit', request).then(function (response) {
            response = response.data;
            if (response.status == true) {
                $scope.editedUser = {};
                $scope.loadUserList();
                $('#userModal').modal('hide');
            }
            else {
                $scope.userError.message = response.message;
                AppUtils.showAlertBox($scope.userError);
            }
        }, function (response) {
            $scope.userError.message = 'Произошла системная ошибка';
            AppUtils.showAlertBox($scope.userError);
        });

        console.log('Success');

    };

    $scope.saveAddUser = function (editedUser, form) {
        if (form.$invalid) {
            $scope.userError.message = 'Форма заполнена неверно';
            AppUtils.showAlertBox($scope.userError);
            return;
        }

        var request = $scope.editedUser;

        $http.post('/user/add', request).then(function (response) {
            response = response.data;
            if (response.status == true) {
                $scope.editedUser = {};
                $scope.loadUserList();
                $('#userModal').modal('hide');
            }
            else {
                $scope.userError.message = response.message;
                AppUtils.showAlertBox($scope.userError);
            }
        }, function (response) {
            $scope.userError.message = 'Произошла системная ошибка';
            AppUtils.showAlertBox($scope.userError);
        });

        console.log('Success');

    }

}]);