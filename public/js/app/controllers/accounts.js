/**
 * Created by scroollocker on 27.11.17.
 */

app.controller('UserAccounts', ['$scope', function($scope) {
    $scope.accountsLoading = false;

    $scope.accountList = [];

    $scope.getAccounts = function() {
        return $scope.accountList;
    }
}]);
