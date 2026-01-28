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

use Samrprca\ProfitCalculation;

// don't call the file directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! defined( 'SAMRPRCA_FILE' ) ) {
    define( 'SAMRPRCA_FILE', __FILE__ );
}

require_once __DIR__ . '/vendor/autoload.php';

/**
 * Load SamratProfitCalculatorForWooCommerce Plugin when all plugins loaded
 *
 * @return \Samrprca\ProfitCalculation
 */
function samrprca_init() {
    return ProfitCalculation::init();
}

// Lets Go....
samrprca_init();
