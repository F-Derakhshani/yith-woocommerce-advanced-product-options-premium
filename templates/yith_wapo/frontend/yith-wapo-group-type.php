<?php
/**
 * Option group template
 *
 * @author  Yithemes
 * @package YITH WooCommerce Product Add-Ons Premium
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Group Data

$type_id = $single_type['id'];
$title = $single_type['label'];
$description = $single_type['description'];
$conditional =  $yith_wapo_frontend->checkConditionalOptions( $single_type['depend'] );
$conditional_variation =  $single_type['depend_variations'];
$conditional_hidden = ! empty( $conditional ) ? 'ywapo_conditional_hidden' : '';
$conditional_variation_hidden = ! empty( $conditional_variation ) ? 'ywapo_conditional_variation_hidden' : '';
$disabled = ! empty( $conditional ) ? 'disabled' : '';
$image = $single_type['image'];
$type = strtolower( $single_type['type'] ) ;

$required = $single_type['required'];

$required_all_options = $single_type['required_all_options'];

$sold_individually = $single_type['sold_individually'];

$max_item_selected = isset( $single_type['max_item_selected'] ) ? $single_type['max_item_selected'] : 0 ;

$max_input_values_amount = isset( $single_type['max_input_values_amount'] ) ? $single_type['max_input_values_amount'] : 0 ;

$min_input_values_amount = isset( $single_type['min_input_values_amount'] ) ? $single_type['min_input_values_amount'] : 0 ;

$change_featured_image = $single_type['change_featured_image'];

$calculate_quantity_sum = $single_type['calculate_quantity_sum'];

$class_calculate_quantity_sum = $calculate_quantity_sum ? 'yith_wapo_calculate_quantity' : '';

$name = 'ywapo_'.$type.'_'.$type_id;

$value = 'ywapo_value_'.$type_id;

$empty_option_text = apply_filters( 'ywapo_empty_option_text' , __( 'Select an option...' , 'yith-woocommerce-product-add-ons' ) ) ;

// Options Data
$options = maybe_unserialize( $single_type['options'] );

if( !( isset( $options['label'] ) ) || count( $options['label'] ) <= 0 ) return;
?>

<div id="<?php echo $value ?>" class="ywapo_group_container ywapo_group_container_<?php echo $type; ?> form-row form-row-wide <?php echo $class_calculate_quantity_sum.' '.$conditional_hidden.' '.$conditional_variation_hidden; ?>" data-requested="<?php echo $required ? '1' : '0' ; ?>" data-requested-all-options="<?php echo $required_all_options ? '1' : '0' ; ?>" data-type="<?php echo esc_attr( $type ); ?>" data-id="<?php echo esc_attr( $type_id ); ?>" data-condition="<?php echo esc_attr( $conditional ) ?>" data-condition-variations="<?php echo esc_attr( $conditional_variation ) ?>" data-sold-individually="<?php echo $sold_individually ? '1' : '0' ; ?>" data-max-item-selected="<?php echo $max_item_selected > 0 ? esc_attr( $max_item_selected ) : 0 ; ?>" data-change-featured-image="<?php echo $change_featured_image ? '1' : '0' ;?>" data-calculate-quantity-sum="<?php echo $calculate_quantity_sum ? '1' : '0' ;?>" data-max-input-values-amount="<?php echo $max_input_values_amount > 0 ? esc_attr( $max_input_values_amount ) : 0 ; ?>" data-min-input-values-amount="<?php echo $min_input_values_amount > 0 ? esc_attr( $min_input_values_amount ) : 0 ; ?>">
    <?php if( $title && $yith_wapo_frontend->_option_show_label_type == 'yes' ): ?>
    <h3><?php echo wptexturize( $title ); ?><?php echo ( $required ? '<abbr class="required" title="required">*</abbr>' : '' ) ?><?php echo (
        $sold_individually ? '<abbr class="sold_individually"> ( '._x( 'Sold individually' , 'Info shown to users in front end' ,
                'yith-woocommerce-product-add-ons' ).' )</abbr>' : '' ); //@since 1.1.0 ?></h3>
    <?php endif; ?>
    <?php if( $image && $yith_wapo_frontend->_option_show_image_type == 'yes' ): ?>
        <?php echo '<div class="ywapo_product_option_image"><img src="'.esc_attr($image).'" alt="'.esc_attr($title).'"/></div>'; ?>
    <?php endif; ?>
    <?php if( $description && $yith_wapo_frontend->_option_show_description_type == 'yes' ): ?>
    <?php echo '<div class="ywapo_product_option_description">' .wpautop( wptexturize( $description ) ).'</div>'; ?>
    <?php endif; ?>
    <?php

    if( $type=='select' ) {
        echo '<select name="'.$name.'" class="ywapo_input" '.$disabled.' '.( $required ? 'required' : '').' >';
        echo '<option value="">'.$empty_option_text.'</option>';
    }

    if( is_array( $options ) ) {

        $options['label'] = array_map( 'stripslashes', $options['label'] );
        $options['description'] = array_map( 'stripslashes', $options['description'] );

        for( $i=0; $i< count($options['label']) ; $i++ ) {

            //--- WPML ----------
            if( YITH_WAPO::$is_wpml_installed ) {

                $options['label'][$i] = YITH_WAPO_WPML::string_translate( $options['label'][$i] );
                $options['description'][$i] = YITH_WAPO_WPML::string_translate( $options['description'][$i] );

            }
            //---END WPML---------

            $min = isset( $options['min'][$i] ) ? $options['min'][$i] : false;
            $max = isset( $options['max'][$i] ) ? $options['max'][$i] : false;
            $image = isset( $options['image'][$i] ) && $yith_wapo_frontend->_option_show_image_option == 'yes' ? $options['image'][$i] : '';
            $price_type = isset( $options['type'][$i] ) ? $options['type'][$i] : 'fixed';
            $description = isset( $options['description'][$i] ) && $yith_wapo_frontend->_option_show_description_option == 'yes' ? $options['description'][$i] : '';

            $checked = ( isset( $options['default'] ) ) ? ( in_array( $i , $options['default'] ) ) : false;

            $required_option = false;
            if( $required_all_options ) {
                $required_option = $required;
            }

            if( ! $required_option ) {
                $required_option = ( isset( $options['required'] ) ) ? ( in_array( $i , $options['required'] ) ) : false;
            }

            $yith_wapo_frontend->printOptions( $i, $product, $type_id, $type, $name, $value, $options['price'][$i], $options['label'][$i], $image, $price_type, $description, $required_option, $checked, $disabled, 'before' , $min, $max );

        }

    }

    if( $type=='select' ) {
        echo '</select>';
    }

    ?>
</div>