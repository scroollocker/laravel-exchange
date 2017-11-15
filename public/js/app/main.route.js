app.config(['$routeProvider', function($routeProvider) {
    $routeProvider.when('/invoices/lists',  {
        templateUrl: '/invoices/list',
        controller: 'InvoicesController'
    });

    $routeProvider.when('/invoices/add',  {
        templateUrl: '/invoices/add',
        controller: 'InvoicesController'
    });

    $routeProvider.when('/invoices/chat',  {
        templateUrl: '/chat/base',
        controller: 'ChatTemplate'
    });

    $routeProvider.when('/invoices', {
        redirectTo: '/invoices/lists'
    });

    $routeProvider.otherwise({
        redirectTo: '/invoices/lists'
    });
}]);