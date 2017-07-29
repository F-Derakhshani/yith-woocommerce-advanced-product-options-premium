<tr>
    <td class="option-sort"><i class="dashicons dashicons-move" style="color: #aaa; line-height: 30px; margin-right: 5px;"></i></td>
    <td>
        <div id="option-image-new" class="option-image">
            <input class="opt-image" type="hidden" name="options[image][]" size="60" value="">
            <p class="save-first"><?php _e( 'Save to set image!', 'yith-woocommerce-product-add-ons' ) ?></p>
        </div>
    </td>
    <td colspan="6">
        <div class="option-label"><input type="text" name="options[label][]" value="" placeholder="Label" /></div>
        <div class="option-type">
            <select name="options[type][]">
                <option value="fixed"><?php _e( 'Fixed amount', 'yith-woocommerce-product-add-ons' ); ?></option>
                <option value="percentage"><?php _e( '% markup', 'yith-woocommerce-product-add-ons' ); ?></option>
                <option value="calculated_multiplication"><?php _e( 'Price multiplied by value', 'yith-woocommerce-product-add-ons' ); ?></option>
                <option value="calculated_character_count"><?php _e( 'Price multiplied by string length', 'yith-woocommerce-product-add-ons' ); ?></option>
            </select>
        </div>
        <div class="option-price"><input type="text" name="options[price][]" value="" placeholder="0" /></div>
        <div class="option-min"><input type="text" name="options[min][]" value="" placeholder="0" /></div>
        <div class="option-max"><input type="text" name="options[max][]" value="" placeholder="0" /></div>
        <div class="option-delete"><a class="button remove-row">Delete</a></div>
        <div class="option-description"><input type="text" name="options[description][]" value="" placeholder="<?php _e( 'Description', 'yith-woocommerce-product-add-ons' ) ?>" /></div>
        <div class="option-default"><input type="checkbox" name="options[default][]" value="<?php echo $i ;?>" class="new_default" /><?php _e( 'Checked', 'yith-woocommerce-product-add-ons' ) ?></div>
        <div class="option-required"><input type="checkbox" name="options[required][]" value="<?php echo $i ;?>" class="new_required" /><?php _e( 'Required', 'yith-woocommerce-product-add-ons' ) ?></div>
    </td>
</tr>