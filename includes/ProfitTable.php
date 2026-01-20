<?php

namespace Emily\PcwProfitCalculation;

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
            __( 'Profit Calculation', 'pcw-profit-calculation' ),
            __( 'Profit Calculation', 'pcw-profit-calculation' ),
            'manage_woocommerce',
            'pcw-profit-calculation',
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
            check_admin_referer( 'pcw_profit_filter' );
            $exporter = new PDFExporter();
            $exporter->export( $table->items, $table->get_total_profit() );
        }
        
        $template = PCW_PROFIT_CALCULATION_TEMPLATE_DIR . '/profit-index.php';
        
        if ( file_exists( $template ) ) {
            include $template;
        }
    }
}
