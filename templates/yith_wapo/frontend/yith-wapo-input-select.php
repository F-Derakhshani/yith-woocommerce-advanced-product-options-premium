<?php
/**
 * Input field template
 *
 * @author  Yithemes
 * @package YITH WooCommerce Product Add-Ons Premium
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$classes = array( 'ywapo_select_option' ,  'ywapo_price_'.esc_attr( $price_type ) );

$selected = $checked ? 'selected' : '';

echo sprintf( '<option class="%s" data-typeid="%s" data-price="%s" data-pricetype="%s" data-index="%s" value="%s" data-image-url="%s" %s >%s</option>', implode( ' ' ,$classes ) , esc_attr( $type_id ),esc_attr( $price_calculated ), esc_attr( $price_type ), $key, esc_attr( $key ), $image_url, $selected, $span_label . $price_hmtl);