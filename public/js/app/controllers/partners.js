/**
 * Created by scroollocker on 04.12.17.
 */

app.controller('PartnersController', ['$scope', '$http', function($scope, $http) {
    $scope.partnersLoading = false;

    $scope.parners = {
        'data': []
    };

    $scope.getPartners = function() {
        return $scope.parners.data;
    };

    $scope.loadPartners = function(page) {
        $scope.partnersLoading = true;

        var request = {
            'page': page
        };

        $http.post('user/partners/get', request).then(function(response){
            $scope.partnersLoading = false;
            response = response.data;
            $scope.parners = response.page;
        }, function(response) {
            $scope.partnersLoading = false;
        });

    };

}]);
