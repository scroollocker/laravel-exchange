app.service('AppUtils', ['$timeout', function ($timeout) {
    
    var showAlertBox = function (alert) {
        if (alert !== null && alert !== undefined) {
            console.log(alert);
            alert.show = true;
            $timeout(function () {
                alert.show = false;
            }, 3000);
        }
    };

    return {
        'showAlertBox': showAlertBox
    }
}]);
