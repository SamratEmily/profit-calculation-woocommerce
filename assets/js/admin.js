jQuery(function($){
    // Validation for Buying Price on Product Edit Page
    $('form#post').submit(function(){
        // Check if the _buying_price field exists
        var $buyingPriceField = $('#_buying_price');
        
        if ( $buyingPriceField.length > 0 ) {
            var buyingPrice = $buyingPriceField.val();
            
            // Allow 0, but not empty
            if ( buyingPrice === '' ) {
                alert('Buying Price is required!');
                
                // Switch to General tab if not active
                $('.general_options').click();
                $buyingPriceField.focus();
                
                return false;
            }
        }
        return true;
    });
});
