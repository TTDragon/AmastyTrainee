define(function () {
    'use strict';


    var mixin = {
        defaults: {
            minAutocompleteLength: 5
        },

        getMinAutocompleteLength: function () {
            return this.minAutocompleteLength;
        }
    };

    return function (target) {
        return target.extend(mixin);
    };
});
