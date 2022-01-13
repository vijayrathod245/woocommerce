<?php
/**
 * WooCommerce Order Status Manager
 *
 * This source file is subject to the GNU General Public License v3.0
 * that is bundled with this package in the file license.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl-3.0.html
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@skyverge.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade WooCommerce Order Status Manager to newer
 * versions in the future. If you wish to customize WooCommerce Order Status Manager for your
 * needs please refer to http://docs.woocommerce.com/document/woocommerce-order-status-manager/ for more information.
 *
 * @author      SkyVerge
 * @copyright   Copyright (c) 2015-2020, SkyVerge, Inc. (info@skyverge.com)
 * @license     http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License v3.0
 */

defined( 'ABSPATH' ) or exit;

/**
 * Default customer order status email template.
 *
 * Note: the .td class used in table is from WooCommerce core (see email-styles.php).
 *
 * @type string $email_heading The email heading.
 * @type string $email_body_text The email body.
 * @type \WC_Order $order The order object.
 * @type bool $sent_to_admin Whether email is sent to admin.
 * @type bool $plain_text Whether email is plain text.
 * @type bool $show_download_links Whether to show download links.
 * @type bool $show_purchase_note Whether to show purchase note.
 * @type \WC_Email $email The email object.
 *
 * @since 1.0.0
 * @version 1.10.0
 */
?>

<?php do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php if ( $email_body_text ) : ?>
<div id="body_text"><?php echo $email_body_text; ?></div>
<?php endif; ?>

<?php do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>

<h2><?php echo esc_html__( 'Order:', 'woocommerce-order-status-manager' ) . ' ' . $order->get_order_number(); ?></h2>

<table class="td" cellspacing="0" cellpadding="6" style="border:0 !important; width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="0">
	<thead>
		<tr>
			<th class="td" colspan="3" style="text-align:<?php echo $text_align; ?>;"><?php esc_html_e( 'Product', 'woocommerce-order-status-manager' ); ?></th>
			<th class="td" colspan="1" style="text-align:<?php echo $text_align; ?>;"><?php esc_html_e( 'SKU', 'woocommerce-order-status-manager' ); ?></th>
			<th class="td" colspan="1" scope="col" style="text-align:<?php echo $text_align; ?>;"><?php esc_html_e( 'Quantity', 'woocommerce-order-status-manager' ); ?></th>
			<th class="td" colspan="1" scope="col" style="text-align:<?php echo $text_align; ?>;"><?php esc_html_e( 'Item-Price', 'woocommerce-order-status-manager' ); ?></th>
			<th class="td" colspan="1" scope="col" style="text-align:<?php echo $text_align; ?>;"><?php esc_html_e( 'Line-Total', 'woocommerce-order-status-manager' ); ?></th>
		</tr>
	</thead>
	<tbody>
		
		<?php 
			foreach ( $order->get_items() as $item_id => $item ) : // CHANGED from "$items" to "$order->get_items()"
                //global $woocommerce, $product;
                $product = $item->get_product();

                if ( apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
                   // global $product;
                    ?>
                    <tr>
                        <td class="td" colspan="1" style="text-align:<?php echo $text_align; ?>; vertical-align:middle; border: 1px solid #eee; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; word-wrap:break-word;"><?php


                            // Product name
                            echo apply_filters( 'woocommerce_order_item_name', $item->get_name(), $item, false ) . "\n";
                            //echo ' X ' . apply_filters( 'woocommerce_email_order_item_quantity', $item->get_quantity(), $item );
                            //echo ' = ' . $order->get_formatted_line_subtotal( $item ) . "\n";

                            // allow other plugins to add additional product information here
                            do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, $plain_text );

                            wc_display_item_meta( $item );

                            // allow other plugins to add additional product information here
                            do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, $plain_text );

                        ?></td>
                        <td class="td" colspan="3" style="text-align:<?php echo $text_align; ?>; vertical-align:middle; border: 1px solid #eee; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; padding: 8px !important;"><?php echo '' . $product->get_sku() . "\n"; ?></td>
                        <td class="td" colspan="1" style="text-align:<?php echo $text_align; ?>; vertical-align:middle; border: 1px solid #eee; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; padding: 8px !important;"><?php echo '' . apply_filters( 'woocommerce_email_order_item_quantity', $item->get_quantity(), $item ); ?></td>
                        <td class="td" colspan="1" style="text-align:<?php echo $text_align; ?>; vertical-align:middle; border: 1px solid #eee; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; padding: 8px !important;"><span class="woocommerce-Price-amount amount"><span class="woocommerce-Price-currencySymbol">£</span><?php echo '' . $product->get_price() . "\n"; ?></span></td>
                        <td class="td" colspan="1" style="text-align:<?php echo $text_align; ?>; vertical-align:middle; border: 1px solid #eee; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; padding: 8px !important;"><span class="woocommerce-Price-amount amount">£</span><?php echo '' . $item->get_quantity() * $product->get_price()  . "\n"; ?></td>
                     </tr>   
                    
                    <?php
                }

                if ( $show_purchase_note && is_object( $product ) && ( $purchase_note = $product->get_purchase_note() ) ) : ?>
                    <tr>
                        <td colspan="3" style="text-align:<?php echo $text_align; ?>; vertical-align:middle; border: 1px solid #eee; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;"><?php echo wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) ); ?></td>
                    </tr>
                <?php // CHANGES Below (removed a closing php tag)
                    endif;
                endforeach;
		?>
		
	</tbody>
	<tfoot>
		<?php
                if ( $totals = $order->get_order_item_totals() ) {
                    $i = 0;
                    foreach ( $totals as $total ) {
                        $i++;
                        ?>
                       
                        <tr>
                            <th class="td" colspan="3" style="border:0 !important; text-align:<?php echo $text_align; ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>"></th>
                            <th class="td" colspan="2" style="text-align:<?php echo $text_align; ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 1px;' : ''; ?>"><?php echo $total['label']; ?></th>
                            <td class="td" colspan="2" style="text-align:<?php echo $text_align; ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 1px;' : ''; ?>"><?php echo $total['value']; ?></td>
                        </tr><?php
                    }
                }
                if ( $order->get_customer_note() ) {
                    ?><tr>
                        <th class="td" scope="row" colspan="4" style="text-align:<?php echo $text_align; ?>;"><?php _e( 'Note:', 'woocommerce' ); ?></th>
                        <td class="td" colspan="3" style="text-align:<?php echo $text_align; ?>;"><?php echo wptexturize( $order->get_customer_note() ); ?></td>
                    </tr><?php
                }
            ?>
	</tfoot>
</table>

<?php do_action( 'woocommerce_email_after_order_table', $order, true, false, $email ); ?>
<?php do_action( 'woocommerce_email_order_meta', $order, true, false, $email ); ?>

<h2><?php esc_html_e( 'Customer details', 'woocommerce-order-status-manager' ); ?></h2>

<?php if ( $billing_email = $order->get_billing_email() ) : ?>
	<p><strong><?php esc_html_e( 'Email:', 'woocommerce-order-status-manager' ); ?></strong> <?php echo $billing_email; ?></p>
<?php endif; ?>

<?php if ( $billing_phone = $order->get_billing_phone() ) : ?>
	<p><strong><?php esc_html_e( 'Tel:', 'woocommerce-order-status-manager' ); ?></strong> <?php echo $billing_phone; ?></p>
<?php endif; ?>

<?php wc_get_template( 'emails/email-addresses.php', array( 'order' => $order ) ); ?>

<?php do_action( 'woocommerce_email_footer', $email ); ?>