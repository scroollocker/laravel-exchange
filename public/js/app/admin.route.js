app.config(['$routeProvider', function($routeProvider) {
    $routeProvider.when('/admin/users/list', {
        templateUrl: '/users/list',
        controller: 'UserController'
    })
    .when('/admin/currency/list', {
        templateUrl: '/currency/list',
        controller: 'CurrencyController'
    })
    .when('/admin/settings/list', {
        templateUrl: '/settings/list',
        controller: 'SettingsController'
    })
    .otherwise({
        redirectTo: '/admin/users/list'
    });
}]);