/**
 * Created by scroollocker on 27.11.17.
 */

app.controller('UserAccounts', ['$scope', '$http', function($scope, $http) {
    $scope.accountsLoading = false;

    $scope.accountList = [];

    $scope.getAccounts = function() {
        return $scope.accountList;
    };

    $scope.loadAccounts = function() {
        $scope.accountsLoading = true;
        $scope.accountList = [];

        $http.get('/user/accounts/get').then(function(response) {
            $scope.accountsLoading = false;
            response = response.data;
            if (response.status) {
                $scope.accountList = response.accounts;
            }
            else {
                alert(response.message);
            }
        }, function() {
            $scope.accountsLoading = false;
            alert('Произошла системная ошибка');
        });
    };

    $scope.setAccountState = function(account) {
        $scope.accountsLoading = true;
        var state = (account.for_deal_n == 0) ? '1' : '0';

        var request = {
            account_id: account.acc_id,
            account_state: state
        };

        $http.post('/user/accounts/state', request).then(function(response) {
            $scope.accountsLoading = false;
            response = response.data;
            if (response.status) {
                $scope.loadAccounts();
            }
            else {
                alert(response.message);
            }
        }, function(){
            $scope.accountsLoading = false;
            alert('Произошла системная ошибка');
        });

    };

    $scope.loadAccounts();
}]);
