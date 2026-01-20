<?php

namespace Emily\PcwProfitCalculation;

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
                    'pcw-profit-calculation-admin',
                    PCW_PROFIT_CALCULATION_PLUGIN_ASSET . '/js/admin.js',
                    [ 'jquery', 'wp-i18n' ],
                    PCW_PROFIT_CALCULATION_VERSION,
                    true
                );
                wp_set_script_translations( 'pcw-profit-calculation-admin', 'pcw-profit-calculation', PCW_PROFIT_CALCULATION_DIR . '/languages' );
            }
        }

        // Enqueue on Profit Calculation Submenu Page
        // Hook for submenu page usually looks like 'woocommerce_page_pcw-profit-calculation'
        // We can check $_GET['page'] or the $hook string.
        if ( strpos( $hook, 'pcw-profit-calculation' ) !== false ) {
             wp_enqueue_style(
                'pcw-profit-calculation-admin',
                PCW_PROFIT_CALCULATION_PLUGIN_ASSET . '/css/admin.css',
                [],
                PCW_PROFIT_CALCULATION_VERSION
            );

            // Enqueue datepicker
            wp_enqueue_script( 'jquery-ui-datepicker' );
            wp_enqueue_style(
                'jquery-ui-style',
                PCW_PROFIT_CALCULATION_PLUGIN_ASSET . '/css/jquery-ui.css',
                [],
                '1.12.1'
            );

            wp_enqueue_script(
                'pcw-profit-calculation-admin',
                PCW_PROFIT_CALCULATION_PLUGIN_ASSET . '/js/admin.js',
                [ 'jquery', 'jquery-ui-datepicker', 'wp-i18n' ],
                PCW_PROFIT_CALCULATION_VERSION,
                true
            );
            wp_set_script_translations( 'pcw-profit-calculation-admin', 'pcw-profit-calculation', PCW_PROFIT_CALCULATION_DIR . '/languages' );
        }
    }
}
