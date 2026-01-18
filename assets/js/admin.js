jQuery(function($){
    // Validation for Buying Price on Product Edit Page
    $('form#post').submit(function(){
        // Check if the _buying_price field exists
        var $buyingPriceField = $('#_buying_price');
        
        if ( $buyingPriceField.length > 0 ) {
            var buyingPrice = $buyingPriceField.val();
            
            // Allow 0, but not empty
            if ( buyingPrice === '' ) {
                alert( wp.i18n.__( 'Buying Price is required!', 'profit-calculation-woocommerce' ) );
                
                // Switch to General tab if not active
                $('.general_options').click();
                $buyingPriceField.focus();
                
                return false;
            }
        }
        return true;
    });
    // Initialize datepicker
    $('.ecommerce-datepicker').datepicker({
        dateFormat: 'yy-mm-dd'
    });
});
