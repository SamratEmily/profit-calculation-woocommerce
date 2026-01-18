<?php

namespace Emily\EcommerceProfitCalculation;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Assets {

    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_assets' ] );
    }

    /**
     * Enqueue admin assets
     *
     * @param string $hook
     */
    public function enqueue_admin_assets( $hook ) {
        // Enqueue on Product Edit Page
        if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
            $screen = get_current_screen();
            if ( $screen && 'product' === $screen->post_type ) {
                wp_enqueue_script(
                    'ecommerce-profit-calculation-admin',
                    ECOMMERCE_PROFIT_CALCULATION_PLUGIN_ASSET . '/js/admin.js',
                    [ 'jquery', 'wp-i18n' ],
                    ECOMMERCE_PROFIT_CALCULATION_VERSION,
                    true
                );
                wp_set_script_translations( 'ecommerce-profit-calculation-admin', 'ecommerce-profit-calculation', ECOMMERCE_PROFIT_CALCULATION_DIR . '/languages' );
            }
        }

        // Enqueue on Profit Calculation Submenu Page
        // Hook for submenu page usually looks like 'woocommerce_page_ecommerce-profit-calculation'
        // We can check $_GET['page'] or the $hook string.
        if ( strpos( $hook, 'ecommerce-profit-calculation' ) !== false ) {
             wp_enqueue_style(
                'ecommerce-profit-calculation-admin',
                ECOMMERCE_PROFIT_CALCULATION_PLUGIN_ASSET . '/css/admin.css',
                [],
                ECOMMERCE_PROFIT_CALCULATION_VERSION
            );

            // Enqueue datepicker
            wp_enqueue_script( 'jquery-ui-datepicker' );
            wp_enqueue_style( 'jquery-ui-style', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css' );

            wp_enqueue_script(
                'ecommerce-profit-calculation-admin',
                ECOMMERCE_PROFIT_CALCULATION_PLUGIN_ASSET . '/js/admin.js',
                [ 'jquery', 'jquery-ui-datepicker', 'wp-i18n' ],
                ECOMMERCE_PROFIT_CALCULATION_VERSION,
                true
            );
            wp_set_script_translations( 'ecommerce-profit-calculation-admin', 'ecommerce-profit-calculation', ECOMMERCE_PROFIT_CALCULATION_DIR . '/languages' );
        }
    }
}
