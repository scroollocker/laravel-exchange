app.controller('CurrencyController', ['$scope', function($scope) {

    $scope.currenciesList = [];
    $scope.isCurrenciesLoading = false;

    $scope.getCurrencies = function () {
        return $scope.currenciesList;
    };


}]);