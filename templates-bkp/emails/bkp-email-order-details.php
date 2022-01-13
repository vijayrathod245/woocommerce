<?php
/**
 * Admin new order email hacked by me
 */

 if ( ! defined( 'ABSPATH' ) ) {
    exit;
 }



//Output the email header
  do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

 <p><?php printf( __( 'You have received an order from %s. The order is as follows:', 'woocommerce' ), $order->get_formatted_billing_full_name() ); ?></p>

 <?php

$show_purchase_note = true; // ADDED

//order details
 $text_align = is_rtl() ? 'right' : 'left';

do_action( 'woocommerce_email_before_order_table', $order, $sent_to_admin, $plain_text, $email ); ?>

<?php if ( ! $sent_to_admin ) : ?>
    <h2><?php printf( __( 'Order #%s', 'woocommerce' ), $order->get_order_number() ); ?> (<?php printf( '<time datetime="%s">%s</time>', $order->get_date_created()->format( 'c' ), wc_format_datetime( $order->get_date_created() ) ); ?>)</h2>
<?php else : ?>
    <h2><a class="link" href="<?php echo esc_url( admin_url( 'post.php?post=' . $order->get_id() . '&action=edit' ) ); ?>"><?php printf( __( 'Order #%s', 'woocommerce' ), $order->get_order_number() ); ?></a> (<?php printf( '<time datetime="%s">%s</time>', $order->get_date_created()->format( 'c' ), wc_format_datetime( $order->get_date_created() ) ); ?>)</h2>
<?php endif; ?>

<div style="margin-bottom: 40px;">
    <table class="td" cellspacing="0" cellpadding="6" style="width: 100%; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;" border="1">
        <thead>
            <tr>
                <th class="td" scope="col" style="text-align:<?php echo $text_align; ?>;"><?php _e( 'Product', 'woocommerce' ); ?></th>
                <th class="td" scope="col" style="text-align:<?php echo $text_align; ?>;"><?php _e( 'Quantity', 'woocommerce' ); ?></th>
                <th class="td" scope="col" style="text-align:<?php echo $text_align; ?>;"><?php _e( 'Item Price', 'woocommerce' ); ?></th>
                <th class="td" scope="col" style="text-align:<?php echo $text_align; ?>;"><?php _e( 'Product Code', 'woocommerce' ); ?></th>
                <th class="td" scope="col" style="text-align:<?php echo $text_align; ?>;"><?php _e( 'Line Totals', 'woocommerce' ); ?></th>
            </tr>
        </thead>
        <tbody>

            <?php
            /*
                echo wc_get_email_order_items( $order, array(
                'show_sku'      => $sent_to_admin,
                'show_image'    => false,
                'image_size'    => array( 32, 32 ),
                'plain_text'    => $plain_text,
                'sent_to_admin' => $sent_to_admin,
            ) );
            */

            do_action( 'woocommerce_email_order_items', $order, $sent_to_admin, $plain_text, $email );
        

            foreach ( $order->get_items() as $item_id => $item ) : // CHANGED from "$items" to "$order->get_items()"
                //global $woocommerce, $product;
                $product = $item->get_product();
                if ( apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
                   // global $product;
                    ?>
                    <tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'order_item', $item, $order ) ); ?>">
                        <td class="td" style="text-align:<?php echo $text_align; ?>; vertical-align:middle; border: 1px solid #eee; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif; word-wrap:break-word;"><?php


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
                        <td class="td" style="text-align:<?php echo $text_align; ?>; vertical-align:middle; border: 0px solid #eee; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;"><?php echo '  ' . apply_filters( 'woocommerce_email_order_item_quantity', $item->get_quantity(), $item ); ?></td>
                        <td class="td" style="text-align:<?php echo $text_align; ?>; vertical-align:middle; border: 1px solid #eee; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;"><?php echo '  ' . $order->get_formatted_line_subtotal( $item ) . "\n"; ?></td>
                        <td class="td" style="text-align:<?php echo $text_align; ?>; vertical-align:middle; border: 1px solid #eee; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;"><?php echo '  ' . $product->get_sku() . "\n"; ?></td>
                        
                        <td class="td" style="text-align:<?php echo $text_align; ?>; vertical-align:middle; border: 1px solid #eee; font-family: 'Helvetica Neue', Helvetica, Roboto, Arial, sans-serif;"><?php echo wc_price($item->get_total() * $item->get_quantity()); ?></td>
                        
                    
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
                        ?><tr>
                            <th class="td" scope="row" colspan="2" style="text-align:<?php echo $text_align; ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>"><?php echo $total['label']; ?></th>
                            <td class="td" style="text-align:<?php echo $text_align; ?>; <?php echo ( 1 === $i ) ? 'border-top-width: 4px;' : ''; ?>"><?php echo $total['value']; ?></td>
                        </tr><?php
                    }
                }
                if ( $order->get_customer_note() ) {
                    ?><tr>
                        <th class="td" scope="row" colspan="2" style="text-align:<?php echo $text_align; ?>;"><?php _e( 'Note:', 'woocommerce' ); ?></th>
                        <td class="td" style="text-align:<?php echo $text_align; ?>;"><?php echo wptexturize( $order->get_customer_note() ); ?></td>
                    </tr><?php
                }
            ?>
        </tfoot>
    </table>
</div>

<?php do_action( 'woocommerce_email_after_order_table', $order, $sent_to_admin, $plain_text, $email ); 

// fim de order details?>
<?php
// linhas 40 até ao fim do ficheiro original
/**
  * @hooked WC_Emails::order_meta() Shows order meta data.
  */
 do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

 /**
  * @hooked WC_Emails::customer_details() Shows customer details
  * @hooked WC_Emails::email_address() Shows email address
  */
 do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

 /**
  * @hooked WC_Emails::email_footer() Output the email footer
  */
 do_action( 'woocommerce_email_footer', $email );