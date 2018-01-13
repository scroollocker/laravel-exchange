'use strict';

angular
    .module('datetimepicker', [])

    .provider('datetimepicker', function () {
        var default_options = {
            autoclose: true
        };

        this.setOptions = function (options) {
            default_options = options;
        };

        this.$get = function () {
            return {
                getOptions: function () {
                    return default_options;
                }
            };
        };
    })

    .directive('datetimepicker', [
        '$timeout',
        'datetimepicker',
        function ($timeout,
                  datetimepicker) {

            var default_options = datetimepicker.getOptions();

            return {
                require: '?ngModel',
                restrict: 'AE',
                scope: {
                    datetimepickerOptions: '@'
                },
                link: function ($scope, $element, $attrs, ngModelCtrl) {
                    return $timeout(function () {


                        var passed_in_options = $scope.$eval($attrs.datetimepickerOptions);
                        console.log(passed_in_options);
                        var options = jQuery.extend({}, default_options, passed_in_options);

                        $scope.$watch($attrs['ngModel'], function (newValue) {
                            setPickerValue();
                        });

                        console.log(options);

                        $element
                            .on('changeDate', function (e) {
                                if (ngModelCtrl) {
                                    //$timeout(function () {
                                    $scope.$apply(function () {
                                        return ngModelCtrl.$setViewValue(e.target.value);
                                    });
                                    //});
                                }
                            })
                            .datepicker(options);

                        function setPickerValue() {
                            var date = options.defaultDate || null;

                            if (ngModelCtrl && ngModelCtrl.$viewValue) {
                                date = ngModelCtrl.$viewValue;
                            }

                            console.log(date);
                            if (date !== null) {
                                $element.datepicker('setDate', moment(date, 'yyyy-mm-dd'));
                            }


                        }

                        /*if (ngModelCtrl) {
                         ngModelCtrl.$render = function () {
                         setPickerValue();
                         };
                         }

                         setPickerValue();*/
                    });
                }
            };
        }
    ]);