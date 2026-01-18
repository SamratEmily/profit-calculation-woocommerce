<?php

namespace Emily\EcommerceProfitCalculation;

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
            __( 'Profit Calculation', 'ecommerce-profit-calculation' ),
            __( 'Profit Calculation', 'ecommerce-profit-calculation' ),
            'manage_woocommerce',
            'ecommerce-profit-calculation',
            [ $this, 'render_page' ]
        );
    }

    /**
     * Render the admin page
     */
    public function render_page() {
        $table = new ProfitListTable();
        $table->prepare_items();

        if ( isset( $_REQUEST['export_pdf'] ) ) {
            $exporter = new PDFExporter();
            $exporter->export( $table->items, $table->get_total_profit() );
        }
        
        $template = ECOMMERCE_PROFIT_CALCULATION_TEMPLATE_DIR . '/profit-index.php';
        
        if ( file_exists( $template ) ) {
            include $template;
        }
    }
}
