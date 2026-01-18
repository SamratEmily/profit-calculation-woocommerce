<div class="wrap">
<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
    <h1 class="wp-heading-inline"><?php esc_html_e( 'Profit Calculation', 'ecommerce-profit-calculation' ); ?></h1>
    <form method="get">
        <input type="hidden" name="page" value="<?php echo esc_attr( $_REQUEST['page'] ); ?>" />
        <?php
        // $table is passed from the calling function
        $table->display();
        ?>
    </form>
    
    <div class="card profit-calculation-card">
        <h2><?php esc_html_e( 'Total Profit', 'ecommerce-profit-calculation' ); ?></h2>
        <p class="profit-amount">
            <?php echo wp_kses_post( wc_price( $table->get_total_profit() ) ); ?>
        </p>
        <p class="description"><?php esc_html_e( 'Calculated from visible orders', 'ecommerce-profit-calculation' ); ?></p>
    </div>
</div>
