app.controller('NavbarController', ['$scope', '$location', function($scope, $location) {
    $scope.isActive = function(path) {
        console.log($location.path(), path);
        return $location.path() === path
    }
}]);