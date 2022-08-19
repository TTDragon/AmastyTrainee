define(['uiComponent', 'jquery'], function (Component, $) {
    return Component.extend({
        defaults: {
            searchText: '',
            searchResult: [],
            backendUrl: '',
            currentRequest: null
        },

        initObservable: function () {
            this._super();
            this.observe(['searchText', 'searchResult']);
            this.searchText.subscribe(this.handleAutocomplete.bind(this));

            return this;
        },

        handleAutocomplete: function (searchValue) {
            if (searchValue.length >= this.getMinAutocompleteLength()) {
                if (this.currentRequest !== null) {
                    this.currentRequest.abort();
                    this.currentRequest = null;
                }
                this.currentRequest = $.getJSON(
                    this.backendUrl,
                    {
                        sku: searchValue,
                        max_items: this.getMaxAutocompleteSuggestions()
                    },
                    this.handleBackendResponse.bind(this)
                );
            } else {
                this.searchResult([]);
            }
        },

        handleBackendResponse: function (searchResult) {
            this.searchResult(searchResult);
        },

        getMaxAutocompleteSuggestions: function () {
            return 5;
        },

        getMinAutocompleteLength: function () {
            return 3;
        }
    });
})
