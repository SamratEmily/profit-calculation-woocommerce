<?php

namespace Samrprca;

use Dompdf\Dompdf;
use Dompdf\Options;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class PDFExporter {

    public function export( $items, $total_profit ) {
        if ( ! class_exists( 'Dompdf\Dompdf' ) ) {
            wp_die( message: esc_html__( 'PDF library not found.', 'samrat-profit-calculator-for-woocommerce' ) );
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
        
        $filename = 'profit-report-' . wp_date('Y-m-d') . '.pdf';
        
        // Clear any previous output
        if (ob_get_length()) ob_end_clean();
        
        $dompdf->stream($filename, ['Attachment' => 1]);
        exit;
    }

    private function get_html( $items, $total_profit ) {
        $css_file = SAMRPRCA_DIR . '/assets/css/pdf-style.css';
        $styles   = file_exists( $css_file ) ? file_get_contents( $css_file ) : '';
        
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
            <?php if ( $styles ) : ?>
                <style><?php echo $styles; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></style>
            <?php endif; ?>
        </head>
        <body>
            <div class="container">
                <table class="header-table">
                    <tr>
                        <td class="header-left">
                            <div class="company-name"><?php bloginfo('name'); ?></div>
                            <h1><?php esc_html_e( 'Profit Calculation Report', 'samrat-profit-calculator-for-woocommerce' ); ?></h1>
                        </td>
                        <td class="header-right">
                            <?php
                            // Translators: %s: report date range
                            printf( esc_html__( 'Report Period: %s', 'samrat-profit-calculator-for-woocommerce' ), esc_html( wp_date('F j, Y') ) ); ?><br>
                            <?php
                            // Translators: %s: generation date and time
                            printf( esc_html__( 'Generated on %s', 'samrat-profit-calculator-for-woocommerce' ), esc_html( wp_date('F j, Y H:i') ) ); ?>
                        </td>
                    </tr>
                </table>
                
                <table>
                    <thead>
                        <tr>
                            <th align="left"><?php esc_html_e( 'Order Information', 'samrat-profit-calculator-for-woocommerce' ); ?></th>
                            <th align="left"><?php esc_html_e( 'Order Date', 'samrat-profit-calculator-for-woocommerce' ); ?></th>
                            <th align="right"><?php esc_html_e( 'Selling Price', 'samrat-profit-calculator-for-woocommerce' ); ?></th>
                            <th align="right"><?php esc_html_e( 'Buying Price', 'samrat-profit-calculator-for-woocommerce' ); ?></th>
                            <th align="right"><?php esc_html_e( 'Net Profit', 'samrat-profit-calculator-for-woocommerce' ); ?></th>
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
                                <td align="left"><?php echo esc_html( $date ); ?></td>
                                <td align="right"><?php echo esc_html( $selling_price ); ?></td>
                                <td align="right"><?php echo esc_html( $buying_price ); ?></td>
                                <td align="right" class="<?php echo esc_attr( $profit_class ); ?>"><?php echo esc_html( $profit ); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <div class="total-box">
                    <table class="total-box-table">
                        <tr>
                            <td><strong><?php esc_html_e( 'Total Summary Profit', 'samrat-profit-calculator-for-woocommerce' ); ?></strong></td>
                            <td align="right"><span class="total-amount"><?php echo esc_html( $this->format_price( wc_price( $total_profit ) ) ); ?></span></td>
                        </tr>
                    </table>
                </div>
                <div class="clearfix"></div>

                <div class="footer">
                    <?php
                    // Translators: %1$s: year, %2$s: site name
                    printf( esc_html__( '&copy; %1$s %2$s. All rights reserved.', 'samrat-profit-calculator-for-woocommerce' ), esc_html( wp_date('Y') ), esc_html( get_bloginfo('name') ) ); ?>
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
