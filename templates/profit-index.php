<div class="wrap">
<?php 
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
    <h1 class="wp-heading-inline"><?php esc_html_e( 'Profit Calculation', 'pcw-profit-calculation' ); ?></h1>
    <form method="get">
        <input type="hidden" name="page" value="<?php echo isset( $_GET['page'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) : ''; /* phpcs:ignore WordPress.Security.NonceVerification.Recommended */ ?>" />
        <?php
        // $table is passed from the calling function
        $table->display();
        ?>
    </form>
    
    <div class="card profit-calculation-card">
        <h2><?php esc_html_e( 'Total Profit', 'pcw-profit-calculation' ); ?></h2>
        <p class="profit-amount">
            <?php echo wp_kses_post( wc_price( $table->get_total_profit() ) ); ?>
        </p>
        <p class="description"><?php esc_html_e( 'Calculated from visible orders', 'pcw-profit-calculation' ); ?></p>
    </div>
</div>
