<?php
/**
 * Plugin Name: Ecommerce Profit Calculation
 * Plugin URI:  https://wordpress.org/plugins/ecommerce-profit-calculation/
 * Description: This plugin helps you to calculate profit for your WooCommerce products.
 * Version: 0.0.1
 * Author: Samrat Hossen
 * Author URI: https://samrat-personal-portfolio.netlify.app
 * Text Domain: ecommerce-profit-calculation
 * WC requires at least: 5.0.0
 * Requires Plugins: woocommerce
 * License: GPL2
 * Tested up to: 6.9
 */

use Emily\EcommerceProfitCalculation\ProfitCalculation;

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'ECOMMERCE_PROFIT_CALCULATION_FILE' ) ) {
    define( 'ECOMMERCE_PROFIT_CALCULATION_FILE', __FILE__ );
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Load EcommerceProfitCalculation Plugin when all plugins loaded
 *
 * @return \Emily\EcommerceProfitCalculation\ProfitCalculation
 */
function emily_ecommerce_profit_calculation_init() {
    return ProfitCalculation::init();
}

// Lets Go....
emily_ecommerce_profit_calculation_init();
