<?php
/**
 * Group container template
 *
 * @author  Yithemes
 * @package YITH WooCommerce Product Add-Ons Premium
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<?php
foreach( $types_list as $single_type ) {
    $yith_wapo_frontend->printSingleGroupType( $product , $single_type );
}

$product_id = yit_get_base_product_id( $product );
$product_display_price = yit_get_display_price( $product );
?>

<div class="yith_wapo_group_total <?php echo ( get_option( 'yith_wapo_settings_show_add_ons_price_table' , 'no' ) == 'yes' ? 'yith_wapo_keep_show' : '' ); ?>" data-product-price="<?php echo esc_attr( $product_display_price ); ?>" data-product-id="<?php echo esc_attr( $product_id ); ?>">
    <table>
        <tr class="ywapo_tr_product_base_price"><td><?php _e( 'Product price:' , 'yith-woocommerce-product-add-ons' ) ?></td><td><div class="yith_wapo_group_product_price_total"><span class="price amount"></span></div></td></tr>
        <tr class="ywapo_tr_additional_options"><td><?php _e( 'Additional options total:' , 'yith-woocommerce-product-add-ons' ) ?></td><td><div class="yith_wapo_group_option_total"><span class="price amount"></span></div></td></tr>
        <tr class="ywapo_tr_order_totals"><td><?php _e( 'Order total:' , 'yith-woocommerce-product-add-ons' ) ?></td><td><div class="yith_wapo_group_final_total"><span class="price amount"></span></div></td></tr>
    </table>
</div>

<!-- Hidden input for checking single page -->
<input type="hidden" name="yith_wapo_is_single" id="yith_wapo_is_single" value="1">