<div class="wrap">
<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
    <h1 class="wp-heading-inline"><?php esc_html_e( 'Profit Calculation', 'profit-calculation' ); ?></h1>
    <form method="post">
        <?php
        // $table is passed from the calling function
        $table->display();
        ?>
    </form>
    
    <div class="card profit-calculation-card">
        <h2><?php esc_html_e( 'Total Profit', 'profit-calculation' ); ?></h2>
        <p class="profit-amount">
            <?php echo wp_kses_post( wc_price( $table->get_total_profit() ) ); ?>
        </p>
        <p class="description"><?php esc_html_e( 'Calculated from visible orders', 'profit-calculation' ); ?></p>
    </div>
</div>
