<?php

namespace SamratProfitCalculatorForWooCommerce;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use SamratProfitCalculatorForWooCommerce\ProductMeta;
use SamratProfitCalculatorForWooCommerce\ProfitTable;
use SamratProfitCalculatorForWooCommerce\Assets;

/**
 * ProfitCalculation class
 */
final class ProfitCalculation {

    /**
     * Plugin version
     *
     * @var string
     */
    public $version = '1.0.0';

    /**
     * Instance of self
     *
     * @var ProfitCalculation
     */
    private static $instance = null;

    /**
     * Holds various class instances
     *
     * @var array
     */
    private $container = [];

    /**
     * Constructor for the ProfitCalculation class
     */
    private function __construct() {
        $this->define_constants();

        add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
    }

    /**
     * Initializes the ProfitCalculation() class
     *
     * @return ProfitCalculation
     */
    public static function init() {
        if ( self::$instance === null ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Magic getter to bypass referencing objects
     *
     * @param string $prop
     *
     * @return mixed
     */
    public function __get( $prop ) {
        if ( array_key_exists( $prop, $this->container ) ) {
            return $this->container[ $prop ];
        }
        return null;
    }

    /**
     * Define all constants
     *
     * @return void
     */
    public function define_constants() {
        defined( 'SAMRAT_PROFIT_CALCULATION_VERSION' ) || define( 'SAMRAT_PROFIT_CALCULATION_VERSION', $this->version );
        defined( 'SAMRAT_PROFIT_CALCULATION_FILE' ) || define( 'SAMRAT_PROFIT_CALCULATION_FILE', dirname( __DIR__ ) . '/samrat-profit-calculator-for-woocommerce.php' );
        defined( 'SAMRAT_PROFIT_CALCULATION_DIR' ) || define( 'SAMRAT_PROFIT_CALCULATION_DIR', dirname( SAMRAT_PROFIT_CALCULATION_FILE ) );
        defined( 'SAMRAT_PROFIT_CALCULATION_INC_DIR' ) || define( 'SAMRAT_PROFIT_CALCULATION_INC_DIR', SAMRAT_PROFIT_CALCULATION_DIR . '/includes' );
        defined( 'SAMRAT_PROFIT_CALCULATION_TEMPLATE_DIR' ) || define( 'SAMRAT_PROFIT_CALCULATION_TEMPLATE_DIR', SAMRAT_PROFIT_CALCULATION_DIR . '/templates' );
        defined( 'SAMRAT_PROFIT_CALCULATION_PLUGIN_ASSET' ) || define( 'SAMRAT_PROFIT_CALCULATION_PLUGIN_ASSET', plugins_url( 'assets', SAMRAT_PROFIT_CALCULATION_FILE ) );
    }

    /**
     * Load the plugin files
     *
     * @return void
     */
    public function init_plugin() {
        // load_plugin_textdomain( 'samrat-profit-calculator-for-woocommerce', false, dirname( plugin_basename( SAMRAT_PROFIT_CALCULATION_FILE ) ) . '/languages' );

        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        $this->init_classes();
    }

    /**
     * Init all the classes
     *
     * @return void
     */
    public function init_classes() {
        $this->container['assets'] = new Assets();
        $this->container['product_meta'] = new ProductMeta();
        $this->container['profit_table'] = new ProfitTable();
    }
}