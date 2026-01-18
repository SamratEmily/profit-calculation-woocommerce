<?php

namespace Emily\EcommerceProfitCalculation;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use Emily\EcommerceProfitCalculation\ProductMeta;
use Emily\EcommerceProfitCalculation\ProfitTable;
use Emily\EcommerceProfitCalculation\Assets;

/**
 * ProfitCalculation class
 */
final class ProfitCalculation {

    /**
     * Plugin version
     *
     * @var string
     */
    public $version = '0.0.1';

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
        defined( 'ECOMMERCE_PROFIT_CALCULATION_VERSION' ) || define( 'ECOMMERCE_PROFIT_CALCULATION_VERSION', $this->version );
        defined( 'ECOMMERCE_PROFIT_CALCULATION_FILE' ) || define( 'ECOMMERCE_PROFIT_CALCULATION_FILE', dirname( __DIR__ ) . '/ecommerce-profit-calculation.php' );
        defined( 'ECOMMERCE_PROFIT_CALCULATION_DIR' ) || define( 'ECOMMERCE_PROFIT_CALCULATION_DIR', dirname( ECOMMERCE_PROFIT_CALCULATION_FILE ) );
        defined( 'ECOMMERCE_PROFIT_CALCULATION_INC_DIR' ) || define( 'ECOMMERCE_PROFIT_CALCULATION_INC_DIR', ECOMMERCE_PROFIT_CALCULATION_DIR . '/includes' );
        defined( 'ECOMMERCE_PROFIT_CALCULATION_TEMPLATE_DIR' ) || define( 'ECOMMERCE_PROFIT_CALCULATION_TEMPLATE_DIR', ECOMMERCE_PROFIT_CALCULATION_DIR . '/templates' );
        defined( 'ECOMMERCE_PROFIT_CALCULATION_PLUGIN_ASSET' ) || define( 'ECOMMERCE_PROFIT_CALCULATION_PLUGIN_ASSET', plugins_url( 'assets', ECOMMERCE_PROFIT_CALCULATION_FILE ) );
    }

    /**
     * Load the plugin files
     *
     * @return void
     */
    public function init_plugin() {
        // load_plugin_textdomain( 'ecommerce-profit-calculation', false, dirname( plugin_basename( ECOMMERCE_PROFIT_CALCULATION_FILE ) ) . '/languages' );

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