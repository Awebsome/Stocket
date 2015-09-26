(function ($) { "use strict";

    var ProductForm = function() {
        this.initLayout();
    }

    ProductForm.prototype.initLayout = function() {
        $('#Form-secondaryTabs .tab-pane.layout-cell')
            .addClass('padded-pane');
    }

    $(document).ready(function() {
        var form = new ProductForm();

        if ($.oc === undefined) {
            $.oc = {}
        }

        $.oc.shopProductForm = form;
    });

})(window.jQuery);


$(document).ready(function(){
    
    function suma(){
        var prices = $("td.list-cell-name-price");
        
        var sum = 0;
        var selector = prices;

        $('table.table.data').find(selector).each(function (index, element) {
            sum += parseFloat($(element).text());
        });

        return parseFloat(sum);
    }  



    $(".result").text("Total: " + suma());


    /**
    $( "table.table.data" ).change(function() {
        $(".result").text("Total: " + suma());
    });

        Add Quantity field;
    $("td.list-cell-name-price").after( '<td><input type="text" name="Product[stock]" id="Form-field-Product-stock" value="1" placeholder="" class="form-control" autocomplete="off" pattern="-?\d+(\.\d+)?" maxlength="255"></td><td>Subtotal</td>' );
    */
});