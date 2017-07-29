<?php
/**
 * Admin Type Form
 *
 * @author  Yithemes
 * @package YITH WooCommerce Product Add-Ons
 * @version 1.0.0
 */

defined( 'ABSPATH' ) or exit;

$is_edit = isset( $type );
$act = 'new';
$priority = 0;
$field_type = '';
$field_image_url = YITH_WAPO_URL . '/assets/img/placeholder.png';
$field_image = '';
$field_id_img_class = 'form-add';
$field_label = '';
$field_description = '';
$field_required = false;
$field_required_all_options = true;
$field_qty_individually = false;
$field_max_item_selected = 0;
$field_max_input_values_amount = 0;
$field_min_input_values_amount = 0;
$field_change_featured_image = false;
$field_calculate_quantity_sum = false;
$field_description = '';
$field_priority = '';

$dependencies_query = YITH_WAPO_Admin::getDependeciesQuery( $wpdb, $group , $type, $is_edit );

if( $is_edit ) {
    $act            = 'update';
    $field_priority = $type->priority;
    $field_type     = $type->type;
    if ( $type->image ) {
        $field_image_url = $field_image = $type->image;
    }
    $field_id_img_class           = $type->id;
    $field_label                  = $type->label;
    $field_required               = $type->required;
    $field_required_all_options   = $type->required_all_options;
    $field_description            = $type->description;
    $field_qty_individually       = $type->sold_individually;
    $field_max_item_selected      = $type->max_item_selected;
    $field_max_input_values_amount = $type->max_input_values_amount;
    $field_min_input_values_amount = $type->min_input_values_amount;
    $field_change_featured_image  = $type->change_featured_image;
    $field_calculate_quantity_sum = $type->calculate_quantity_sum;
}
?>

<form action="edit.php?post_type=product&page=yith_wapo_group_addons" method="post" class="<?php echo $field_type; ?>"<?php if ( $is_edit && $field_label == '' ) { echo 'style="display: block;"'; } ?>>

    <?php if( $is_edit ) : ?>

        <input type="hidden" name="id" value="<?php echo $type->id; ?>">

    <?php endif; ?>

    <input type="hidden" name="act" value="<?php echo $act; ?>">
    <input type="hidden" name="class" value="YITH_WAPO_Type">
    <input type="hidden" name="group_id" value="<?php echo $group->id; ?>">
    <input type="hidden" name="priority" value="<?php echo $field_priority; ?>">

    <div class="form-left"<?php if ( ! $is_edit ) { echo ' style="margin: 5px 0px;"'; } ?>>
        <div class="form-row">

            <div class="type">
                <?php if( $is_edit ) : ?><label for="label"><?php echo __( 'Type', 'yith-woocommerce-product-add-ons' ); ?></label><?php endif; ?>
                <select name="type">
                    <option value="checkbox" <?php selected( $field_type , 'checkbox' ); ?>><?php _e( 'Checkbox' , 'yith-woocommerce-product-add-ons' )  ?></option>
                    <option value="color" <?php selected( $field_type , 'color'); ?>><?php _e( 'Color' , 'yith-woocommerce-product-add-ons' )  ?></option>
                    <option value="date" <?php selected( $field_type , 'date'); ?>><?php _e( 'Date' , 'yith-woocommerce-product-add-ons' )  ?></option>
                    <option value="labels" <?php selected( $field_type , 'labels'); ?>><?php _e( 'Labels' , 'yith-woocommerce-product-add-ons' )  ?></option>
                    <option value="multiple_labels" <?php selected( $field_type , 'multiple_labels'); ?>><?php _e( 'Multiple Labels' , 'yith-woocommerce-product-add-ons' )  ?></option>
                    <option value="number" <?php selected( $field_type , 'number'); ?>><?php _e( 'Number' , 'yith-woocommerce-product-add-ons' )  ?></option>
                    <option value="select" <?php selected( $field_type , 'select'); ?>><?php _e( 'Select' , 'yith-woocommerce-product-add-ons' )  ?></option>
                    <option value="radio" <?php selected( $field_type , 'radio'); ?>><?php _e( 'Radio Button' , 'yith-woocommerce-product-add-ons' )  ?></option>
                    <option value="text" <?php selected( $field_type , 'text'); ?>><?php _e( 'Text' , 'yith-woocommerce-product-add-ons' )  ?></option>
                    <option value="textarea" <?php selected( $field_type , 'textarea'); ?>><?php _e( 'Textarea' , 'yith-woocommerce-product-add-ons' )  ?></option>
                    <option value="file" <?php selected( $field_type , 'file'); ?>><?php _e( 'Upload' , 'yith-woocommerce-product-add-ons' )  ?></option>
                </select>
            </div>

        </div>
        <?php if( $is_edit ) : ?>
            <div class="form-row">

                <div class="image">
                    <label for="image"><?php echo __( 'Image', 'yith-woocommerce-product-add-ons' ); ?></label>
                    <input class="image" type="hidden" name="image" size="60" value="<?php echo $field_image; ?>">
                    <img class="thumb image image-upload" src="<?php echo $field_image_url; ?>" height="100" />
                    <span class="dashicons dashicons-no remove"></span>
                </div>

            </div>
        <?php endif; ?>
    </div>

    <div class="form-right">

        <?php if( $is_edit ) : ?>
        
            <div class="form-row">
                
                <div class="label">
                    <label for="label"><?php _e( 'Title', 'yith-woocommerce-product-add-ons' ); ?></label>
                    <input name="label" type="text" value="<?php echo $field_label; ?>" class="regular-text">
                </div>

                <div class="depend">
                    <label for="depend"><?php _e( 'Requirements', 'yith-woocommerce-product-add-ons' ); //@since 1.1.0 ?><span class="woocommerce-help-tip" data-tip="<?php _e( 'Show this add-on to users only if they have first selected the following options.', 'yith-woocommerce-product-add-ons' ); //@since 1.1.0 ?>"></span></label>
                    <select name="depend[]" class="depend-select2" multiple="multiple" placeholder="<?php echo __( 'Choose required add-ons', 'yith-woocommerce-product-add-ons' ); ?>..."><?php
                        $dependencies = $wpdb->get_results( $dependencies_query );
                        foreach ( $dependencies as $key => $item ) {
                            if ( $item->label != '' ) {
                                $depend_array = explode( ',', $type->depend );
                                $options_values = maybe_unserialize( $item->options );
                                if( isset( $options_values['label'] ) ) {
                                    foreach ( $options_values['label'] as $option_key => $option_value ) {
                                        $attribute_value = 'option_' . $item->id . '_'.$option_key;
                                        echo '<option value="'.esc_attr( $attribute_value ).'" '.( in_array( $attribute_value, $depend_array ) ? 'selected="selected"' : '' ).'>' . esc_html( $item->label ).' [ '.$option_value . ' ]</option>';
                                    }
                                }
                            }
                        }
                    ?></select>
                </div>

                <div class="variations">
                    <?php if ( isset( $type ) ) { $depend_variations_array = explode( ',', $type->depend_variations ); } else { $depend_variations_array = array(); } ?>

                    <label for="variations"><?php _e( 'Variations Requirements', 'yith-woocommerce-product-add-ons' ); //@since 1.1.0 ?><span class="woocommerce-help-tip" data-tip="<?php _e( 'Show this add-on to users only if they have first selected one of the following variations.', 'yith-woocommerce-product-add-ons' ); //@since 1.1.0 ?>"></span></label>

                    <select name="depend_variations[]" class="depend-select2" multiple="multiple" placeholder="<?php echo __( 'Choose required variations', 'yith-woocommerce-product-add-ons' ); ?>...">
                        <?php YITH_WAPO_Admin::echo_product_chosen_list( $group->products_id , $group->categories_id , $depend_variations_array ); ?>
                    </select>

                </div>
                
            </div>
            <div class="form-row">

                <div class="description">
                    <label for="description"><?php echo __( 'Description', 'yith-woocommerce-product-add-ons' ); ?></label>
                    <textarea name="description" id="description" rows="3" style="width: 100%; height: 120px; margin-top: 3px;"><?php echo $field_description; ?></textarea>
                </div>

            </div>
            <div class="form-row">

                <div class="max_item_selected">
                    <input name="max_item_selected" type="number" value="<?php echo $field_max_item_selected; ?>" class="regular-text" min="0">
                    <?php echo __( 'Limit selectable elements', 'yith-woocommerce-product-add-ons' ); //@since 1.1.3 ?><span class="woocommerce-help-tip" data-tip="<?php _e( 'Set the maximum number of elements that users can select for this add-on, 0 means no limits (works only with checkboxes)', 'yith-woocommerce-product-add-ons' ); //@since 1.1.3 ?>"></span>
                </div>
                <div class="max_input_values_amount">
                    <input name="max_input_values_amount" type="number" value="<?php echo $field_max_input_values_amount; ?>" class="regular-text" min="0">
                    <?php echo __( 'Max input values amount', 'yith-woocommerce-product-add-ons' ); //@since 1.1.3 ?><span class="woocommerce-help-tip" data-tip="<?php _e( 'Set the maximum amount for the sum of the input values', 'yith-woocommerce-product-add-ons' ); //@since 1.1.3 ?>"></span>
                </div>
                <div class="min_input_values_amount">
                    <input name="min_input_values_amount" type="number" value="<?php echo $field_min_input_values_amount; ?>" class="regular-text" min="0">
                    <?php echo __( 'Min input values amount', 'yith-woocommerce-product-add-ons' ); //@since 1.1.3 ?><span class="woocommerce-help-tip" data-tip="<?php _e( 'Set the minimum amount for the sum of the input values', 'yith-woocommerce-product-add-ons' ); //@since 1.1.3 ?>"></span>
                </div>
                <div class="sold_individually">
                    <input type="checkbox" name="sold_individually" value="1" <?php echo $field_qty_individually ? 'checked="checked"' : ''; ?>>
                    <?php echo __( 'Sold individually', 'yith-woocommerce-product-add-ons' ); //@since 1.1.0 ?><span
                            class="woocommerce-help-tip" data-tip="<?php _e( 'Check this box if you want that the selected add-ons are not increased as
                            the product quantity changes.', 'yith-woocommerce-product-add-ons' ); //@since 1.1.0 ?>"></span>
                </div>
                <div class="change_featured_image">
                    <input type="checkbox" name="change_featured_image" value="1" <?php echo $field_change_featured_image ? 'checked="checked"' : ''; ?>>
                    <?php echo __( 'Replace the product image', 'yith-woocommerce-product-add-ons' ); //@since 1.1.0 ?><span
                        class="woocommerce-help-tip" data-tip="<?php _e( 'Check this box if you want that the selected add-ons replace
                            the product image.', 'yith-woocommerce-product-add-ons' ); //@since 1.1.0 ?>"></span>
                </div>
                <div class="calculate_quantity_sum">
                    <input type="checkbox" name="calculate_quantity_sum" value="1" <?php echo $field_calculate_quantity_sum ? 'checked="checked"' : ''; ?>>
                    <?php echo __( 'Calculate quantity by values amout', 'yith-woocommerce-product-add-ons' ); //@since 1.1.0 ?><span
                        class="woocommerce-help-tip" data-tip="<?php _e( 'Check this box if you want that the quanity input will be updated with the sum of all add-ons values.', 'yith-woocommerce-product-add-ons' ); //@since 1.1.0 ?>"></span>
                </div>
                <div class="required">
                    <input type="checkbox" name="required" value="1" <?php echo $field_required ? 'checked="checked"' : ''; ?>>
                    <?php echo __( 'Required', 'yith-woocommerce-product-add-ons' ); ?><span class="woocommerce-help-tip" data-tip="<?php _e( 'Check this option if you want that the add-on have to be selected', 'yith-woocommerce-product-add-ons' ); //@since 1.1.3 ?>"></span>
                </div>
                <div class="required_all_options">
                    <input type="checkbox" name="required_all_options" value="1" <?php echo $field_required_all_options ? 'checked="checked"' : ''; ?>>
                    <?php echo __( 'All options required', 'yith-woocommerce-product-add-ons' ); ?><span class="woocommerce-help-tip" data-tip="<?php _e( 'Check this option if you want that the add-on have to be all options required', 'yith-woocommerce-product-add-ons' ); //@since 1.1.3 ?>"></span>
                </div>

            </div>

        <?php endif; ?>

    </div>

    <div class="clear"></div>

    <?php if( $is_edit ) : ?>

    <div class="form-row">
        <div class="options">
            <table class="wp-list-table widefat fixed yith_wapo_option_table">
                <thead>
                <tr>
                    <th class="option-sort"><?php echo __( 'Sort', 'yith-woocommerce-product-add-ons' );?></th>
                    <th class="option-image"><?php echo __( 'Image', 'yith-woocommerce-product-add-ons' );?></th>
                    <th class="option-label"><?php echo __( 'Option Label', 'yith-woocommerce-product-add-ons' );?></th>
                    <th class="option-type"><?php echo __( 'Type', 'yith-woocommerce-product-add-ons' );?></th>
                    <th class="option-price"><?php echo __( 'Price', 'yith-woocommerce-product-add-ons' );?></th>
                    <th class="option-min"><?php echo __( 'Min', 'yith-woocommerce-product-add-ons' );?></th>
                    <th class="option-max"><?php echo __( 'Max', 'yith-woocommerce-product-add-ons' );?></th>
                    <th class="option-delete"></th>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 0;
                $array_options = maybe_unserialize( $type->options );
                if ( ! isset($array_options['description'] ) ) { $array_options['description'] = ''; }
                if ( isset( $array_options['label'] ) && is_array( $array_options['label'] ) ) {
                    $array_default = isset( $array_options['default'] ) ? $array_options['default'] : array();
                    $array_required = isset( $array_options['required'] ) ? $array_options['required'] : array();
                    foreach ( $array_options['label'] as $key => $value ) : ?>
                        <tr class="yith_wapo_option_row">
                            <td class="option-sort"><i class="dashicons dashicons-move" style="color: #aaa; line-height: 30px; margin-right: 5px;"></i></td>
                            <td>
                                <div id="option-image-<?php echo $i; ?>" class="option-image">
                                    <div class="image">
                                        <?php

                                        $image_url = YITH_WAPO_URL . '/assets/img/placeholder.png';

                                        $isset_image = isset( $array_options['image'] ) && isset( $array_options['image'][$i] ) && $array_options['image'][$i];

                                        if ( $isset_image ) { $image_url = $array_options['image'][$i]; }
                                        ?>

                                        <input class="opt-image" type="hidden" name="options[image][]" size="60" value="<?php echo ( $isset_image ) ? $array_options['image'][$i] : ''; ?>">
                                        <img class="thumb opt-image opt-image-upload" src="<?php echo $image_url; ?>" height="100" />
                                        <span class="dashicons dashicons-no opt-remove"></span>

                                    </div>
                                </div>
                            </td>
                            <td colspan="6">
                                <div class="option-label"><input type="text" name="options[label][]" value="<?php echo stripslashes( $array_options['label'][$i] ); ?>" placeholder="Label" /></div>
                                <div class="option-type">
                                    <select name="options[type][]">
                                        <option value="fixed" <?php echo isset( $array_options['type'][$i] ) && $array_options['type'][$i] == 'fixed' ? 'selected="selected"' : ''; ?>><?php _e( 'Fixed amount', 'yith-woocommerce-product-add-ons' ); ?></option>
                                        <option value="percentage" <?php echo isset( $array_options['type'][$i] ) && $array_options['type'][$i] == 'percentage' ? 'selected="selected"' : ''; ?>><?php _e( '% markup', 'yith-woocommerce-product-add-ons' ); ?></option>
                                        <option value="calculated_multiplication" <?php echo isset( $array_options['type'][$i] ) && $array_options['type'][$i] == 'calculated_multiplication' ? 'selected="selected"' : ''; ?>><?php _e( 'Price multiplied by value', 'yith-woocommerce-product-add-ons' ); ?></option>
                                        <option value="calculated_character_count" <?php echo isset( $array_options['type'][$i] ) && $array_options['type'][$i] == 'calculated_character_count' ? 'selected="selected"' : ''; ?>><?php _e( 'Price multiplied by string length', 'yith-woocommerce-product-add-ons' ); ?></option>
                                    </select>
                                </div>
                                <div class="option-price"><input type="text" name="options[price][]" value="<?php echo $array_options['price'][$i]; ?>" placeholder="0" /></div>
                                <div class="option-min"><input type="text" name="options[min][]" value="<?php echo isset( $array_options['min'][$i] ) ? $array_options['min'][$i] : ''; ?>" placeholder="0" /></div>
                                <div class="option-max"><input type="text" name="options[max][]" value="<?php echo isset( $array_options['max'][$i] ) ? $array_options['max'][$i] : ''; ?>" placeholder="0" /></div>
                                <div class="option-delete"><a class="button remove-row"><?php echo __( 'Delete', 'yith-woocommerce-product-add-ons' ); ?></a></div>
                                <div class="option-description" colspan="6"><input type="text" name="options[description][]" value="<?php echo stripslashes( $array_options['description'][$i] ); ?>" placeholder="<?php _e( 'Description', 'yith-woocommerce-product-add-ons' ) ?>" /></div>
                                <div class="option-default">
                                    <input type="checkbox" name="options[default][]" value="<?php echo $i; ?>"
                                        <?php foreach ( $array_default as $key_def => $value_def ) { echo $i == $value_def ? 'checked="checked"' : ''; } ?> />
                                    <?php _e( 'Checked', 'yith-woocommerce-product-add-ons' );?>
                                </div>
                                <div class="option-required">
                                    <input type="checkbox" name="options[required][]" value="<?php echo $i; ?>"
                                        <?php foreach ( $array_required as $key_def => $value_def ) { echo $i == $value_def ? 'checked="checked"' : ''; } ?> />
                                    <?php _e( 'Required', 'yith-woocommerce-product-add-ons' );?>
                                </div>
                            </td>
                        </tr>
                        <?php $i++;
                    endforeach;
                }
                ?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="7">
                        <a class="button add_option"><span class="dashicons dashicons-plus" style="line-height: 28px;"></span> <?php echo __( 'Add new option', 'yith-woocommerce-product-add-ons' ); ?></a>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <?php endif; ?>

    <div class="form-row">
        <div class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php $is_edit ? _e( 'Save this add-on', 'yith-woocommerce-product-add-ons' ) : _e( 'Continue', 'yith-woocommerce-product-add-ons' );?>">
            <?php if( ! $is_edit ) : ?>
                <a href="#" class="button cancel"><?php echo __( 'Cancel', 'yith-woocommerce-product-add-ons' );?></a>
            <?php endif; ?>
        </div>
    </div>

</form>
