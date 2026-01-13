<?php
/**
 * Plugin Name: Profit Calculation Ecommerce
 * Plugin URI:  https://wordpress.org/plugins/search/profit-calculation/
 * Description: This plugin helps you to calculate profit for your WooCommerce products.
 * Version: 0.0.1
 * Author: Samrat Hossen
 * Author URI: https://samrat-personal-portfolio.netlify.app
 * Text Domain: profit-calculation
 * WC requires at least: 5.0.0
 * Requires Plugins: woocommerce
 * License: GPL2
 * Tested up to: 6.9
 */

use Emily\ProfitCalculation\ProfitCalculation;

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'PROFIT_CALCULATION_FILE' ) ) {
    define( 'PROFIT_CALCULATION_FILE', __FILE__ );
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Load ProfitCalculation Plugin when all plugins loaded
 *
 * @return \Emily\ProfitCalculation\ProfitCalculation
 */
function emily_profit_calculation_init() {
    return ProfitCalculation::init();
}

// Lets Go....
emily_profit_calculation_init();
