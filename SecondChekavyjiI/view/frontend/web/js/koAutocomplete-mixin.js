define(function () {
    'use strict';

    var mixin = {
        getMinAutocompleteLength: function () {
            return 5;
        }
    };

    return function (target) {
        return target.extend(mixin);
    };
});
