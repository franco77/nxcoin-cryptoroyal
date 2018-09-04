$(document).ready(function() {

$.fn.UserBalance = function(options) {
    
    var cr_this = this;
    
    this.uid = (options.uid === undefined) ? CR_USERID : options.uid;
    this.url = env.site_url + 'wallet/balance/'+options.type;
    this.fontSize = options.fontSize;
    this.type = options.type;

    var onSuccess = function(res) {
        onLoading(false);
        buildBalance(res.data.balance);
    }

    var buildBalance = function(balance) {
        var type = cr_this.type.toUpperCase();
        cr_this.html(balance);
    }
    var setStyle = function() {
        if( cr_this.fontSize ) {
            cr_this.css('font-size',cr_this.fontSize+'em');
        }
    }
    var onRequest = function() {
        onLoading(true);
    }
    var onLoading = function(status) {
        if(status) {
            cr_this.html('Loading...');
        }
    }
    this.refresh = function() {
        cr_this.get();
    }
    this.get = function() {
        $.ajax({
            method: 'GET',
            type: 'application/json',
            url: cr_this.url,
            success: onSuccess,
            beforeSend: onRequest
        });
        return cr_this;
    }
    setStyle();
    return this;
};

});