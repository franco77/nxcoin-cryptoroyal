$(document).ready(function() {
    var temp =
    '<table id="tb_crPrice" class="table table-bordered table-hover text-center">'
        +'<tbody>'
        +    '<tr>'
        +        '<td><label id="tmp_cr_last_price"></label><br/>Last Price</td>'
        +        '<td><label id="tmp_cr_changes"></label><br/> 24h Changes</td>'
        +        '<td><label id="tmp_cr_low"></label><br/>Low</td>'
        +        '<td><label id="tmp_cr_high"></label><br/>High</td>'
        +    '</tr>'
        +    '<tr>'
        +        '<td colspan=4><label id="tmp_cr_volume"></label><br/>Volume</td>'
        +    '</tr>'
        +'</tbody>'
    +'</table>';
    $.fn.CrPrice = function(options) {

        var url = env.site_url + 'order/lastprice/86400';
        var _this = this;

        this.onSuccess = function(res) {
            console.log(res);
            _this.build(res);
        }
        this.build = function(data) {
            var el = $(temp);
            var
                last_price  = 0,
                changes     = 0,
                low         = 0,
                high        = 0,
                volume     = 0;
            
            if( data.length > 0 ) {

                var price = data[0];
                
                last_price  = price.close_price;
                changes     = price.changes;
                low         = price.low_price;
                high        = price.high_price;
                volume     = price.volume;

                close = price.close_price * 100000000;
                open = price.open_price * 100000000;
                
                if( close > open  ) {
                    el.find("#tmp_cr_changes").parent().addClass('cr_price_up');
                } else if(close < open) {
                    el.find("#tmp_cr_changes").parent().addClass('cr_price_down');
                } else {
                    //el.find("#tmp_cr_changes").parent().addClass('cr_price_down');
                }
            }
            
            el.find("#tmp_cr_last_price").html( last_price );
            
            el.find("#tmp_cr_low").html( low );
            el.find("#tmp_cr_high").html( high );
            el.find("#tmp_cr_volume").html( volume );
            el.find("#tmp_cr_changes").html( changes +'%');
            //console.log(lp);
            this.html(el);
        }

        this.get = function() {

            $.ajax({
                method: 'GET',
                url: url,
                type:'application/json',
                success: _this.onSuccess
            });

            return _this;
        };
        
        return this;
    }


});