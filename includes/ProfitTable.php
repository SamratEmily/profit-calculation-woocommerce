<?php

namespace Samrprca;

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
            __( 'Profit Calculation', 'samrat-profit-calculator-for-woocommerce' ),
            __( 'Profit Calculation', 'samrat-profit-calculator-for-woocommerce' ),
            'manage_woocommerce',
            'samrat-profit-calculator-for-woocommerce',
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
        
        $template = SAMRPRCA_TEMPLATE_DIR . '/profit-index.php';
        
        if ( file_exists( $template ) ) {
            include $template;
        }
    }
}
