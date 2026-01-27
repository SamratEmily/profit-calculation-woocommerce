<?php

namespace SamratProfitCalculatorForWooCommerce;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class ProductMeta {

    /**
     * Constructor
     */
    public function __construct() {
        add_action( 'woocommerce_product_options_pricing', [ $this, 'add_buying_price_field' ] );
        add_action( 'woocommerce_admin_process_product_object', [ $this, 'save_buying_price_field' ] );
        add_action( 'woocommerce_checkout_create_order_line_item', [ $this, 'add_buying_price_to_order_item' ], 10, 4 );

        add_action( 'woocommerce_variation_options_pricing', [ $this, 'add_variation_buying_price_field' ], 10, 3 );
        add_action( 'woocommerce_save_product_variation', [ $this, 'save_variation_buying_price_field' ], 10, 2 );
    }

    /**
     * Save Buying Price to Order Item Meta when order is created
     *
     * @param \WC_Order_Item_Product $item
     * @param string $cart_item_key
     * @param array $values
     * @param \WC_Order $order
     */
    public function add_buying_price_to_order_item( $item, $cart_item_key, $values, $order ) {
        if ( isset( $values['data'] ) ) {
            $product = $values['data'];
            $buying_price = $product->get_meta( '_buying_price' );

            if ( $buying_price ) {
                $item->add_meta_data( '_buying_price', $buying_price );
            }
        }
    }

    /**
     * Add Buying Price field
     */
    public function add_buying_price_field() {
        global $post;
        wp_nonce_field( 'pcw_profit_calculation_save_data', 'pcw_profit_calculation_meta_nonce' );

        $product      = wc_get_product( $post->ID );
        $last_updated = $product ? $product->get_meta( '_buying_price_last_updated' ) : '';
        $description  = '';

        if ( $last_updated ) {
            /* translators: %s: date and time of last update */
            $description = '<strong>' . sprintf( esc_html__( 'Last updated: %s', 'samrat-profit-calculator-for-woocommerce' ), date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $last_updated ) ) ) . '</strong>';
        }

        woocommerce_wp_text_input(
            [
                'id'          => '_buying_price',
                'label'       => __( 'Buying Price(inc. tax)', 'samrat-profit-calculator-for-woocommerce' ) . ' (' . get_woocommerce_currency_symbol() . ')',
                'placeholder' => '',
                'desc_tip'    => true,
                'description' => __( 'Enter the buying price to calculate profit.', 'samrat-profit-calculator-for-woocommerce' ),
                'type'        => 'number',
                'custom_attributes' => [
                    'step' => 'any',
                    'min'  => '0',
                    // 'required' => 'required',
                ],
            ]
        );

        if ( $description ) {
            echo '<p class="form-field _buying_price_last_updated_field" style="padding-left: 162px; margin-top: -10px; margin-bottom: 10px;">';
            echo '<span class="description">' . wp_kses_post( $description ) . '</span>';
            echo '</p>';
        }
    }

    /**
     * Save Buying Price field
     * 
     * @param \WC_Product $product
     */
    public function save_buying_price_field( $product ) {
        // Nonce validation
        if ( ! isset( $_POST['pcw_profit_calculation_meta_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['pcw_profit_calculation_meta_nonce'] ) ), 'pcw_profit_calculation_save_data' ) ) {
            return;
        }

        // Check if our custom field is set
        if ( isset( $_POST['_buying_price'] ) ) {
            $buying_price = sanitize_text_field( wp_unslash( $_POST['_buying_price'] ) );
            
            // Validation: Custom field is required
            // if ( empty( $buying_price ) && '0' !== $buying_price ) {
            //      \WC_Admin_Meta_Boxes::add_error( __( 'Buying Price is required.', 'samrat-profit-calculator-for-woocommerce' ) );
            // }
            
            $product->update_meta_data( '_buying_price', $buying_price );
            $product->update_meta_data( '_buying_price_last_updated', current_time( 'mysql' ) );
        }
    }
    
    public function add_variation_buying_price_field( $loop, $variation_data, $variation ) {
        $product = wc_get_product( $variation->ID );
        $buying_price = $product->get_meta( '_buying_price' );

        woocommerce_wp_text_input(
            [
                'id'            => "_variation_buying_price_{$loop}",
                'name'          => "_variation_buying_price[{$loop}]",
                'value'         => $buying_price,
                'label'         => __( 'Buying Price', 'profit-report' ) . ' (' . get_woocommerce_currency_symbol() . ')',
                'desc_tip'      => true,
                'description'   => __( 'Enter the buying price for this variation.', 'profit-report' ),
                'type'          => 'number',
                'wrapper_class' => 'form-row form-row-full',
                'custom_attributes' => [
                    'step' => 'any',
                    'min'  => '0',
                ],
            ]
        );
    }

    public function save_variation_buying_price_field( $variation_id, $i ) {
        if ( isset( $_POST['_variation_buying_price'][ $i ] ) ) {
            $buying_price = sanitize_text_field( wp_unslash( $_POST['_variation_buying_price'][ $i ] ) );
            $product = wc_get_product( $variation_id );

            if ( $product ) {
                $product->update_meta_data( '_buying_price', $buying_price );
                $product->update_meta_data( '_buying_price_last_updated', current_time( 'mysql' ) );
                $product->save();
            }
        }
    }

}
