<?php

namespace Emily\ProfitCalculation;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class ProfitTable {

    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'admin_menu', [ $this, 'register_menu' ] );
    }

    /**
     * Register the submenu
     */
    public function register_menu() {
        // "Profit Calculation with woocommerce by Wp-List-table".
        // Submenu Profit Calculation
        add_submenu_page(
            'woocommerce',
            __( 'Profit Calculation', 'profit-calculation' ),
            __( 'Profit Calculation', 'profit-calculation' ),
            'manage_woocommerce',
            'profit-calculation',
            [ $this, 'render_page' ]
        );
    }

    /**
     * Render the admin page
     */
    public function render_page() {
        $table = new ProfitListTable();
        $table->prepare_items();
        
        $template = PROFIT_CALCULATION_TEMPLATE_DIR . '/profit-index.php';
        
        if ( file_exists( $template ) ) {
            include $template;
        }
    }
}
