<?php
/**
 * Plugin Name: PCW - Profit Calculation
 * Plugin URI:  https://wordpress.org/plugins/pcw-profit-calculation/
 * Description: This plugin helps you to calculate profit for your WooCommerce products.
 * Version: 1.0.0
 * Author: Samrat Hossen
 * Author URI: https://samrat-personal-portfolio.netlify.app
 * Text Domain: pcw-profit-calculation
 * WC requires at least: 5.0.0
 * Requires Plugins: woocommerce
 * License: GPL2
 * Tested up to: 6.9
 */

use Emily\PcwProfitCalculation\ProfitCalculation;

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'PCW_PROFIT_CALCULATION_FILE' ) ) {
    define( 'PCW_PROFIT_CALCULATION_FILE', __FILE__ );
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Load PCWProfitCalculation Plugin when all plugins loaded
 *
 * @return \Emily\PcwProfitCalculation\ProfitCalculation
 */
function emily_pcw_profit_calculation_init() {
    return ProfitCalculation::init();
}

// Lets Go....
emily_pcw_profit_calculation_init();
