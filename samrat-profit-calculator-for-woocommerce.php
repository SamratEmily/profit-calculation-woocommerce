<?php
/**
 * Plugin Name: Samrat Profit Calculator for WooCommerce
 * Plugin URI:  https://wordpress.org/plugins/samrat-profit-calculator-for-woocommerce/
 * Description: This plugin helps you to calculate profit for your WooCommerce products.
 * Version: 1.0.0
 * Author: Samrat Hossen
 * Author URI: https://samrat-personal-portfolio.netlify.app
 * Text Domain: samrat-profit-calculator-for-woocommerce
 * WC requires at least: 5.0.0
 * Requires Plugins: woocommerce
 * License: GPL2
 * Tested up to: 6.9
 */

use SamratProfitCalculatorForWooCommerce\ProfitCalculation;

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'SAMRAT_PROFIT_CALCULATION_FILE' ) ) {
    define( 'SAMRAT_PROFIT_CALCULATION_FILE', __FILE__ );
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Load SamratProfitCalculatorForWooCommerce Plugin when all plugins loaded
 *
 * @return \SamratProfitCalculatorForWooCommerce\ProfitCalculation
 */
function samrat_profit_calculator_for_woocommerce_init() {
    return ProfitCalculation::init();
}

// Lets Go....
samrat_profit_calculator_for_woocommerce_init();
