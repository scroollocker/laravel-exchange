/**
 * Created by scroollocker on 28.10.17.
 */

app.controller('InvoicesController', ['$scope', function($scope) {
    $scope.step = 1;
    $scope.selectStep = function(step) {
        $scope.step = step;
    };

    $scope.isSelect = function(step) {
        return ($scope.step == step);
    };
}]);
