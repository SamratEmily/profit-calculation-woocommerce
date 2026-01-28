<?php

namespace Samrprca;
    
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
            // Translators: Singular name for profit item
            'singular' => __('Profit', 'samrat-profit-calculator-for-woocommerce' ),
            // Translators: Plural name for profit items
            'plural'   => __( 'Profits', 'samrat-profit-calculator-for-woocommerce' ),
            'ajax'     => false,
        ] );
    }

    public function get_columns() {
        return [
            'cb'      => '<input type="checkbox" />',
            'order'   => __( 'Order', 'samrat-profit-calculator-for-woocommerce' ),
            'date'    => __( 'Date', 'samrat-profit-calculator-for-woocommerce' ),
            'selling' => __( 'Selling Price', 'samrat-profit-calculator-for-woocommerce' ),
            'buying'  => __( 'Buying Price', 'samrat-profit-calculator-for-woocommerce' ),
            'profit'  => __( 'Profit', 'samrat-profit-calculator-for-woocommerce' ),
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
                $class = $item['profit'] >= 0 ? 'profit-text-green' : 'profit-text-red';
                return '<span class="' . $class . '">' . wc_price( $item['profit'] ) . '</span>';
            default:
                return esc_html__( 'Not Applicable', 'samrat-profit-calculator-for-woocommerce' );
        }
    }

    protected function column_cb( $item ) {
        return sprintf(
            '<input type="checkbox" name="post[]" value="%s" />',
            $item['ID']
        );
    }

    public function extra_tablenav( $which ) {
        if ( $which == 'top' ) {
            // Nonce verification: Check filter action after sanitization in the next block
            if ( ! empty( $_REQUEST['filter_action'] ) && ( empty( $_REQUEST['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['_wpnonce'] ) ), 'pcw_profit_filter' ) ) ) {
                wp_die( esc_html__( 'Nonce verification failed.', 'samrat-profit-calculator-for-woocommerce' ) );
            }

            $from  = isset( $_REQUEST['from'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['from'] ) ) : '';
            $to    = isset( $_REQUEST['to'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['to'] ) ) : '';
            $year  = isset( $_REQUEST['filter_year'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['filter_year'] ) ) : '';
            $month = isset( $_REQUEST['filter_month'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['filter_month'] ) ) : '';
            $week  = isset( $_REQUEST['filter_week'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['filter_week'] ) ) : '';

            ?>
            <div class="alignleft actions">
                <input type="text" name="from" class="pcw-datepicker" placeholder="<?php esc_attr_e( 'From Date', 'samrat-profit-calculator-for-woocommerce' ); ?>" value="<?php echo esc_attr( $from ); ?>">
                <input type="text" name="to" class="pcw-datepicker" placeholder="<?php esc_attr_e( 'To Date', 'samrat-profit-calculator-for-woocommerce' ); ?>" value="<?php echo esc_attr( $to ); ?>">
                
                <select name="filter_year">
                    <option value=""><?php esc_html_e( 'Select Year', 'samrat-profit-calculator-for-woocommerce' ); ?></option>
                    <?php
                    $current_year = wp_date('Y');
                    for ($i = $current_year; $i >= $current_year - 5; $i--) {
                        echo '<option value="' . esc_attr( $i ) . '" ' . selected($year, $i, false) . '>' . esc_html( $i ) . '</option>';
                    }
                    ?>
                </select>

                <select name="filter_month">
                    <option value=""><?php esc_html_e( 'Select Month', 'samrat-profit-calculator-for-woocommerce' ); ?></option>
                    <?php
                    for ($m = 1; $m <= 12; $m++) {
                        $month_name = wp_date('F', mktime(0, 0, 0, $m, 1));
                        echo '<option value="' . esc_attr( sprintf('%02d', $m) ) . '" ' . selected($month, sprintf('%02d', $m), false) . '>' . esc_html( $month_name ) . '</option>';
                    }
                    ?>
                </select>

                <select name="filter_week">
                    <option value=""><?php esc_html_e( 'Select Week', 'samrat-profit-calculator-for-woocommerce' ); ?></option>
                    <?php
                    for ($w = 1; $w <= 52; $w++) {
                        // Translators: %d: Week number
                        echo '<option value="' . esc_attr( $w ) . '" ' . selected($week, $w, false) . '>' . esc_html( sprintf( __( 'Week %d', 'samrat-profit-calculator-for-woocommerce' ), $w ) ) . '</option>';
                    }
                    ?>
                </select>

                <?php wp_nonce_field( 'pcw_profit_filter', '_wpnonce' ); ?>
                <input type="submit" name="filter_action" id="post-query-submit" class="button" value="<?php esc_attr_e( 'Filter', 'samrat-profit-calculator-for-woocommerce' ); ?>">
                <a href="<?php echo esc_url( admin_url( 'admin.php?page=samrat-profit-calculator-for-woocommerce' ) ); ?>" class="button"><?php esc_html_e( 'Reset', 'samrat-profit-calculator-for-woocommerce' ); ?></a>
                <input type="submit" name="export_pdf" class="button button-primary" value="<?php esc_attr_e( 'Export PDF', 'samrat-profit-calculator-for-woocommerce' ); ?>">
            </div>
            <?php
        }
    }

    public function prepare_items() {
        $profit_paged = $this->get_pagenum();
        $per_page = 20;

        $from  = isset( $_REQUEST['from'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['from'] ) ) : '';
        $to    = isset( $_REQUEST['to'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['to'] ) ) : '';
        $year  = isset( $_REQUEST['filter_year'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['filter_year'] ) ) : '';
        $month = isset( $_REQUEST['filter_month'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['filter_month'] ) ) : '';
        $week  = isset( $_REQUEST['filter_week'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['filter_week'] ) ) : '';

        // Fetch ALL orders to filter them correctly and calculate total profit
        $args = [
            'status' => 'any', 
            'limit' => -1,
        ];

        if ( ! empty( $from ) || ! empty( $to ) ) {
            $args['date_created'] = '';
            if ( ! empty( $from ) ) {
                $args['date_created'] .= $from . '...';
            } else {
                $args['date_created'] .= '1000-01-01...';
            }

            if ( ! empty( $to ) ) {
                $args['date_created'] .= $to;
            } else {
                $args['date_created'] .= wp_date('Y-m-d');
            }
        } elseif ( ! empty( $week ) ) {
            $year_for_week = ! empty( $year ) ? $year : wp_date('Y');
            $dto = new \DateTime();
            $dto->setISODate($year_for_week, $week);
            $start = $dto->format('Y-m-d');
            $dto->modify('+6 days');
            $end = $dto->format('Y-m-d');
            $args['date_created'] = $start . '...' . $end;
        } elseif ( ! empty( $year ) ) {
            if ( ! empty( $month ) ) {
                $args['date_created'] = $year . '-' . $month . '-01...' . wp_date( 'Y-m-t', strtotime( $year . '-' . $month . '-01' ) );
            } else {
                $args['date_created'] = $year . '-01-01...' . $year . '-12-31';
            }
        }

        $orders = wc_get_orders( $args );

        $data = [];
        $this->total_profit = 0; // Global total for relevant orders
        
        foreach ( $orders as $order ) {
            $order_id = $order->get_id();
            
            $items_total_selling = 0;
            $items_total_buying = 0;
            $has_samrprca_buying_price = false;

            foreach ( $order->get_items() as $item_id => $item ) {
                if ( ! is_a( $item, 'WC_Order_Item_Product' ) ) {
                    continue;
                }
                $product = $item->get_product();
                if ( $product ) {
                    $qty = $item->get_quantity();
                    $line_total = $item->get_total(); 
                    
                    // Get buying price from order item (historical data) ONLY
                    $buying_price = $item->get_meta( '_samrprca_buying_price' );
                    
                    if ( $buying_price !== '' && $buying_price !== false ) {
                        $has_samrprca_buying_price = true;
                        $items_total_selling += floatval( $line_total );
                        $items_total_buying += ( floatval( $buying_price ) * $qty );
                    }
                }
            }

            // Only add to list if at least one item had a buying price
            if ( $has_samrprca_buying_price ) {
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
        
        // If export PDF is requested, we don't want to slice for pagination
        if ( isset( $_REQUEST['export_pdf'] ) && check_admin_referer( 'pcw_profit_filter' ) ) {
            $this->items = $data;
        } else {
            $this->items = array_slice( $data, ( ( $profit_paged - 1 ) * $per_page ), $per_page );
        }

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
