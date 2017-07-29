<?php
/**
 * Text area template
 *
 * @author  Yithemes
 * @package YITH WooCommerce Product Add-Ons Premium
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$classes = array( 'ywapo_input ywapo_input_' . $type ,  'ywapo_price_'.esc_attr( $price_type ) );

echo '<div class="ywapo_input_container ywapo_input_container_'.$type.'">';

echo sprintf( '%s<textarea data-typeid="%s" data-price="%s" data-pricetype="%s" data-index="%s" name="%s[%s]" cols="20" rows="4"  %s %s class="%s" %s>%s</textarea>%s',
    $before_label . $price_hmtl . $yith_wapo_frontend->getTooltip( $description ),
    esc_attr( $type_id ),
    esc_attr( $price_calculated ),
    esc_attr( $price_type ),
    $key,
    esc_attr( $name ),
    $key,
    $max_length,
    ( $required ? 'required' : '' ),
    implode( ' ' ,$classes ),
    $disabled,
    esc_html( $value ),
    $after_label );

echo '</div>';