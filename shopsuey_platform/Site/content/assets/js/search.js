// search as you type functionality

var SearchAsYouType = {
    MILLISECONDS_BEFORE_SEARCHING: 500,
    
    timer: null,
    options : null,
    inputElement : null,
    
    initialize: function(options) {
        var requiredOptions = ['requestUrl', 'inputElementSelector', 'dataTableSelector', 'searchParameter', 'loadingSelector'];
        
        for (var i = 0; i < requiredOptions.length; i++) {
            if (!(requiredOptions[i] in options)) {
                throw { 
                    name:    "Missing options", 
                    message: "The following options are mandatory: " + requiredOptions.join(', '), 
                } 
            }
        }
        
        this.options = options;
        this.inputElement = $(options.inputElementSelector);
        this.inputElement.bind('keyup', this.onChange.bind(this, []));
    },
    
    onChange: function (e) {
        if (this.timer) {
            clearTimeout(this.timer);
        }
        this.timer = setTimeout(this.doSearch.bind(this, []), this.MILLISECONDS_BEFORE_SEARCHING);
    },
    
    doSearch: function() {
        if (this.timer) {
            clearTimeout(this.timer);
        }
        var text = this.inputElement.val();
        var url = this.options.requestUrl;
        var data = this.getExtraParams();
        data[this.options.searchParameter] = text;
        
        if (window.stopInfScroll) {
            stopInfScroll()
        }
        
        this.displayLoading();
        
        $.get(url + '&' + $.param(data), function(data) {
            $(SearchAsYouType.options.dataTableSelector).replaceWith($(data).find(SearchAsYouType.options.dataTableSelector));
            if (window.initInfScroll) {
                initInfScroll()
            }
            SearchAsYouType.hideLoading();
        });
    },
            
    getExtraParams : function() {
        if (typeof this.options.extraParamsFn == 'function') {
            return this.options.extraParamsFn();
        } 
        return {};
    },
    
    displayLoading: function() {
        $(this.options.loadingSelector).show();
    },
    
    hideLoading: function() {
        $(this.options.loadingSelector).hide();
    }
}