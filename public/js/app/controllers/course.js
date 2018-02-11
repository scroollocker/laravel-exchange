app.controller('CourseController', ['$scope', '$http', function($scope, $http) {

    $scope.courses = [];

    $scope.getCourses = function() {
        return $scope.courses;
    };

    $scope.loadCourses = function() {
        $scope.courses = [];


        $http.get('/get-courses').then(function (response) {
            response = response.data;

            if (response.status) {
                $scope.courses = response.currencies;
            }
        });
    };

    $scope.loadCourses();


}]);