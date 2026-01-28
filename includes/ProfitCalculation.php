<?php

namespace Samrprca;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

use Samrprca\ProductMeta;
use Samrprca\ProfitTable;
use Samrprca\Assets;

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
        defined( 'SAMRPRCA_VERSION' ) || define( 'SAMRPRCA_VERSION', $this->version );
        defined( 'SAMRPRCA_FILE' ) || define( 'SAMRPRCA_FILE', dirname( __DIR__ ) . '/samrat-profit-calculator-for-woocommerce.php' );
        defined( 'SAMRPRCA_DIR' ) || define( 'SAMRPRCA_DIR', dirname( SAMRPRCA_FILE ) );
        defined( 'SAMRPRCA_INC_DIR' ) || define( 'SAMRPRCA_INC_DIR', SAMRPRCA_DIR . '/includes' );
        defined( 'SAMRPRCA_TEMPLATE_DIR' ) || define( 'SAMRPRCA_TEMPLATE_DIR', SAMRPRCA_DIR . '/templates' );
        defined( 'SAMRPRCA_PLUGIN_ASSET' ) || define( 'SAMRPRCA_PLUGIN_ASSET', plugins_url( 'assets', SAMRPRCA_FILE ) );
    }

    /**
     * Load the plugin files
     *
     * @return void
     */
    public function init_plugin() {
        // load_plugin_textdomain( 'samrat-profit-calculator-for-woocommerce', false, dirname( plugin_basename( SAMRPRCA_FILE ) ) . '/languages' );

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