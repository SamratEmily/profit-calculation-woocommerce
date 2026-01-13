<?php

namespace Emily\ProfitCalculation;
    
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'WP_List_Table' ) ) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class ProfitListTable extends \WP_List_Table {

    /**
     * Total profit calculated from the current items
     * @var int
     */
    public $total_profit = 0;

    public function __construct() {
        parent::__construct( [
            'singular' => __( 'Profit', 'profit-calculation' ),
            'plural'   => __( 'Profits', 'profit-calculation' ),
            'ajax'     => false,
        ] );
    }

    public function get_columns() {
        return [
            'cb'      => '<input type="checkbox" />',
            'order'   => __( 'Order', 'profit-calculation' ),
            'date'    => __( 'Date', 'profit-calculation' ),
            'selling' => __( 'Selling Price', 'profit-calculation' ),
            'buying'  => __( 'Buying Price', 'profit-calculation' ),
            'profit'  => __( 'Profit', 'profit-calculation' ),
        ];
    }

    public function get_sortable_columns() {
        return [
            'order' => [ 'order', true ],
            'date'  => [ 'date', false ],
        ];
    }

    protected function column_default( $item, $column_name ) {
        switch ( $column_name ) {
            case 'order':
                $order = wc_get_order( $item['ID'] );
                if ( ! $order ) return '#' . $item['ID'];
                $edit_link = admin_url( 'post.php?post=' . $item['ID'] . '&action=edit' );
                return '<a href="' . esc_url( $edit_link ) . '">#' . $order->get_order_number() . ' ' . esc_html( $order->get_billing_first_name() ) . ' ' . esc_html( $order->get_billing_last_name() ) . '</a>';
            case 'date':
                return get_the_date( 'F j, Y', $item['ID'] );
            case 'selling':
                return wc_price( $item['selling_price'] );
            case 'buying':
                return wc_price( $item['buying_price'] );
            case 'profit':
                $color = $item['profit'] >= 0 ? 'green' : 'red';
                return '<span style="color:' . $color . '">' . wc_price( $item['profit'] ) . '</span>';
            default:
                return esc_html__( 'Not Applicable', 'profit-calculation' );
        }
    }

    protected function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="post[]" value="%s" />',
            $item['ID']
        );
    }

    public function prepare_items() {
        $profit_paged = $this->get_pagenum();
        $per_page = 20;
        // Fetch ALL orders to filter them correctly and calculate total profit
        $args = [
            'status' => 'any', 
            'limit' => -1,
        ];

        $orders = wc_get_orders( $args );

        $data = [];
        $this->total_profit = 0; // Global total for relevant orders
        
        foreach ( $orders as $order ) {
            $order_id = $order->get_id();
            
            $items_total_selling = 0;
            $items_total_buying = 0;
            $has_buying_price = false;

            foreach ( $order->get_items() as $item_id => $item ) {
                if ( ! is_a( $item, 'WC_Order_Item_Product' ) ) {
                    continue;
                }
                $product = $item->get_product();
                if ( $product ) {
                    $qty = $item->get_quantity();
                    $line_total = $item->get_total(); 
                    
                    // Get buying price from order item (historical data) ONLY
                    $buying_price = $item->get_meta( '_buying_price' );
                    
                    if ( $buying_price !== '' && $buying_price !== false ) {
                        $has_buying_price = true;
                        $items_total_selling += floatval( $line_total );
                        $items_total_buying += ( floatval( $buying_price ) * $qty );
                    }
                }
            }

            // Only add to list if at least one item had a buying price
            if ( $has_buying_price ) {
                $profit = $items_total_selling - $items_total_buying;
                $this->total_profit += $profit;

                $data[] = [
                    'ID' => $order_id,
                    'selling_price' => $items_total_selling,
                    'buying_price' => $items_total_buying,
                    'profit' => $profit,
                ];
            }
        }
        
        // Use manual pagination on the filtered data
        $total_items = count( $data );
        $this->items = array_slice( $data, ( ( $profit_paged - 1 ) * $per_page ), $per_page );

        $this->set_pagination_args( [
            'total_items' => $total_items,
            'per_page'    => $per_page,
            'total_pages' => ceil( $total_items / $per_page ),
        ] );

        $columns = $this->get_columns();
        $hidden = [];
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = [ $columns, $hidden, $sortable ];
    }
    
    /**
     * Get the total profit calculated from the current items
     * @return float
     */
    public function get_total_profit() {
        return isset( $this->total_profit ) ? $this->total_profit : 0;
    }
}
