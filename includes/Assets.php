<?php

namespace Samrprca;

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
                    'samrat-profit-calculator-for-woocommerce-admin',
                    SAMRPRCA_PLUGIN_ASSET . '/js/admin.js',
                    [ 'jquery', 'wp-i18n' ],
                    SAMRPRCA_VERSION,
                    true
                );
                wp_set_script_translations( 'samrat-profit-calculator-for-woocommerce-admin', 'samrat-profit-calculator-for-woocommerce', SAMRPRCA_DIR . '/languages' );
            }
        }

        // Enqueue on Profit Calculation Submenu Page
        // Hook for submenu page usually looks like 'woocommerce_page_samrat-profit-calculator-for-woocommerce'
        // We can check $_GET['page'] or the $hook string.
        if ( strpos( $hook, 'samrat-profit-calculator-for-woocommerce' ) !== false ) {
             wp_enqueue_style(
                'samrat-profit-calculator-for-woocommerce-admin',
                 SAMRPRCA_PLUGIN_ASSET . '/css/admin.css',
                [],
                SAMRPRCA_VERSION
            );
 
            // Enqueue datepicker
            wp_enqueue_script( 'jquery-ui-datepicker' );
            wp_enqueue_style(
                'jquery-ui-style',
                SAMRPRCA_PLUGIN_ASSET . '/css/jquery-ui.css',
                [],
                '1.12.1'
            );
 
            wp_enqueue_script(
                'samrat-profit-calculator-for-woocommerce-admin',
                SAMRPRCA_PLUGIN_ASSET . '/js/admin.js',
                [ 'jquery', 'jquery-ui-datepicker', 'wp-i18n' ],
                SAMRPRCA_VERSION,
                true
            );
            wp_set_script_translations( 'samrat-profit-calculator-for-woocommerce-admin', 'samrat-profit-calculator-for-woocommerce', SAMRPRCA_DIR . '/languages' );
        }
    }
}
