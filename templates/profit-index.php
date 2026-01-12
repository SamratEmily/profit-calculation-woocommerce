<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e( 'Profit Calculation', 'profit-calculation-woocommerce' ); ?></h1>
    <form method="post">
        <?php
        // $table is passed from the calling function
        $table->display();
        ?>
    </form>
    
    <div class="card profit-calculation-card">
        <h2><?php _e( 'Total Profit', 'profit-calculation-woocommerce' ); ?></h2>
        <p class="profit-amount">
            <?php echo wc_price( $table->get_total_profit() ); ?>
        </p>
        <p class="description"><?php _e( 'Calculated from visible orders', 'profit-calculation-woocommerce' ); ?></p>
    </div>
</div>
