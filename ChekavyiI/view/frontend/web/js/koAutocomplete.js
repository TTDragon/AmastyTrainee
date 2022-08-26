define(['uiComponent', 'jquery'], function (Component, $) {
    return Component.extend({
        defaults: {
            searchText: '',
            searchResult: [],
            backendUrl: '',
            currentRequest: null,
            minAutocompleteLength: 3,
            maxAutocompleteSuggestions: 5

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
            return this.maxAutocompleteSuggestions;
        },

        getMinAutocompleteLength: function () {
            return this.minAutocompleteLength;
        }
    });
})
