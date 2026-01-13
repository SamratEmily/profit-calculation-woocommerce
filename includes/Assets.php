<?php

namespace Emily\ProfitCalculation;

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
                    'profit-calculation-admin',
                    PROFIT_CALCULATION_PLUGIN_ASSET . '/js/admin.js',
                    [ 'jquery', 'wp-i18n' ],
                    PROFIT_CALCULATION_VERSION,
                    true
                );
                wp_set_script_translations( 'profit-calculation-admin', 'profit-calculation-woocommerce', PROFIT_CALCULATION_DIR . '/languages' );
            }
        }

        // Enqueue on Profit Calculation Submenu Page
        // Hook for submenu page usually looks like 'woocommerce_page_profit-calculation'
        // We can check $_GET['page'] or the $hook string.
        if ( strpos( $hook, 'profit-calculation' ) !== false ) {
             wp_enqueue_style(
                'profit-calculation-admin',
                PROFIT_CALCULATION_PLUGIN_ASSET . '/css/admin.css',
                [],
                PROFIT_CALCULATION_VERSION
            );
        }
    }
}
