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

    var pushKey = function (object) {
        var mainItem = {};
        var objectKeys = Object.keys(object);
        _.each(objectKeys, function(keyItem) {
            var tmpKeyItem = keyItem.replace(/'/g, '');
            var splitKeyItem = tmpKeyItem.split('.');
            if (splitKeyItem.length > 0) {

                if (mainItem[splitKeyItem[0]] === undefined || mainItem[splitKeyItem[0]] === null) {
                    mainItem[splitKeyItem[0]] = {};
                }
                if (splitKeyItem[1] !== undefined && splitKeyItem[1] !== null) {
                    mainItem[splitKeyItem[0]][splitKeyItem[1]] = object[keyItem];
                } else {
                    mainItem[splitKeyItem[0]] = object[keyItem];
                }
            }
        });
        return mainItem;
    };

    var pushKeys = function (arr) {
        var result = [];

        _.each(arr, function(item) {
            var tmpItem = pushKey(item);
            result.push(tmpItem);
        });

        return result;
    }

    return {
        'showAlertBox': showAlertBox,
        'pushKey': pushKey,
        'pushKeys': pushKeys,
    }



}]);
