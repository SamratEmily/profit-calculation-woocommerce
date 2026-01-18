<?php

namespace Emily\EcommerceProfitCalculation;

use Dompdf\Dompdf;
use Dompdf\Options;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PDFExporter {

    public function export( $items, $total_profit ) {
        if ( ! class_exists( 'Dompdf\Dompdf' ) ) {
            wp_die( __( 'PDF library not found.', 'ecommerce-profit-calculation' ) );
        }

        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'sans-serif');
        
        $dompdf = new Dompdf($options);
        
        $html = $this->get_html( $items, $total_profit );
        
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $filename = 'profit-report-' . date('Y-m-d') . '.pdf';
        
        // Clear any previous output
        if (ob_get_length()) ob_end_clean();
        
        $dompdf->stream($filename, ['Attachment' => 1]);
        exit;
    }

    private function get_html( $items, $total_profit ) {
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <style>
                body { font-family: 'DejaVu Sans', sans-serif; font-size: 10px; color: #444; line-height: 1.5; }
                .container { padding: 10px; }
                
                .header-table { width: 100%; border: none; margin-bottom: 20px; border-bottom: 2px solid #46b450; padding-bottom: 10px; }
                .header-table td { border: none; padding: 0; vertical-align: bottom; }
                .header-left .company-name { color: #46b450; font-weight: bold; font-size: 14px; margin-bottom: 5px; }
                .header-left h1 { color: #1d2327; margin: 0; font-size: 22px; }
                .header-right { text-align: right; color: #777; font-size: 9px; }
                
                table { width: 100%; border-collapse: collapse; margin-top: 10px; border: 1px solid #eee; }
                th { background-color: #f8f8f8; color: #333; font-weight: bold; text-transform: uppercase; font-size: 9px; border-bottom: 2px solid #46b450; padding: 10px; }
                td { padding: 8px 10px; border-bottom: 1px solid #eee; }
                
                .footer { margin-top: 30px; text-align: center; color: #999; font-size: 8px; }
                
                .total-box { margin-top: 20px; float: right; width: 220px; padding: 10px; background-color: #f9fff9; border: 1px solid #46b450; border-radius: 4px; }
                .total-amount { color: #46b450; font-size: 16px; font-weight: bold; }
                
                .profit-green { color: #2e7d32; font-weight: bold; }
                .profit-red { color: #d32f2f; font-weight: bold; }
                .order-id { color: #1d2327; font-weight: bold; font-size: 11px; }
                .customer { color: #666; font-size: 9px; }
            </style>
        </head>
        <body>
            <div class="container">
                <table class="header-table">
                    <tr>
                        <td class="header-left">
                            <div class="company-name"><?php bloginfo('name'); ?></div>
                            <h1><?php _e( 'Profit Calculation Report', 'ecommerce-profit-calculation' ); ?></h1>
                        </td>
                        <td class="header-right">
                            <?php printf( __( 'Report Period: %s', 'ecommerce-profit-calculation' ), date('F j, Y') ); ?><br>
                            <?php printf( __( 'Generated on %s', 'ecommerce-profit-calculation' ), date('F j, Y H:i') ); ?>
                        </td>
                    </tr>
                </table>
                
                <table>
                    <thead>
                        <tr>
                            <th align="left"><?php _e( 'Order Information', 'ecommerce-profit-calculation' ); ?></th>
                            <th align="left"><?php _e( 'Order Date', 'ecommerce-profit-calculation' ); ?></th>
                            <th align="right"><?php _e( 'Selling Price', 'ecommerce-profit-calculation' ); ?></th>
                            <th align="right"><?php _e( 'Buying Price', 'ecommerce-profit-calculation' ); ?></th>
                            <th align="right"><?php _e( 'Net Profit', 'ecommerce-profit-calculation' ); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ( $items as $item ) : 
                            $order = wc_get_order( $item['ID'] );
                            $order_num = $order ? '#' . $order->get_order_number() : '#' . $item['ID'];
                            $customer_name = $order ? $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() : '';
                            $date = get_the_date( 'F j, Y', $item['ID'] );
                            $profit_class = $item['profit'] >= 0 ? 'profit-green' : 'profit-red';
                            
                            $selling_price = $this->format_price( wc_price( $item['selling_price'] ) );
                            $buying_price = $this->format_price( wc_price( $item['buying_price'] ) );
                            $profit = $this->format_price( wc_price( $item['profit'] ) );
                        ?>
                            <tr>
                                <td>
                                    <span class="order-id"><?php echo esc_html( $order_num ); ?></span><br>
                                    <span class="customer"><?php echo esc_html( $customer_name ); ?></span>
                                </td>
                                <td><?php echo esc_html( $date ); ?></td>
                                <td align="right"><?php echo $selling_price; ?></td>
                                <td align="right"><?php echo $buying_price; ?></td>
                                <td align="right" class="<?php echo $profit_class; ?>"><?php echo $profit; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="total-box">
                    <table style="margin:0; border:none; width: 100%;">
                        <tr style="border:none;">
                            <td style="border:none; padding: 0;"><strong><?php _e( 'Total Summary Profit', 'ecommerce-profit-calculation' ); ?></strong></td>
                            <td align="right" style="border:none; padding: 0;"><span class="total-amount"><?php echo $this->format_price( wc_price( $total_profit ) ); ?></span></td>
                        </tr>
                    </table>
                </div>
                <div style="clear: both;"></div>

                <div class="footer">
                    <?php printf( __( '&copy; %s %s. All rights reserved.', 'ecommerce-profit-calculation' ), date('Y'), get_bloginfo('name') ); ?>
                </div>
            </div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }

    /**
     * Clean up price string for PDF output
     * 
     * @param string $price_html
     * @return string
     */
    private function format_price( $price_html ) {
        // Fix BDT Taka sign specifically before stripping tags/entities
        // The Taka sign (৳) often fails to render in standard PDF fonts.
        // Replacing it with 'Tk' or 'BDT' is the standard robust fix for PDFs.
        $price_html = str_replace( array( '৳', '&#2547;', '&#x09f3;' ), 'Tk. ', $price_html );

        // Remove HTML tags
        $price = wp_strip_all_tags( $price_html );
        
        // Remove non-breaking spaces (both entity and UTF-8 versions)
        $price = str_replace( array( '&nbsp;', "\xc2\xa0" ), ' ', $price );
        
        // Remove LRM (Left-to-Right Mark) and RLM (Right-to-Left Mark)
        // These are often added by WooCommerce for RTL support but render as boxes in PDF
        $price = str_replace( array( '&lrm;', '&rlm;', "\xe2\x80\x8e", "\xe2\x80\x8f" ), '', $price );
        
        // Decode entities to render remaining currency symbols (like $, ₹, etc.)
        $price = html_entity_decode( $price, ENT_QUOTES, 'UTF-8' );
        
        return trim( $price );
    }
}
