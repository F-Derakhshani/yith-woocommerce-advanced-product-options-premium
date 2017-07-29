<?php
/**
 * Type class
 *
 * @author  Your Inspiration Themes
 * @package YITH WooCommerce Product Add-Ons
 * @version 1.0.0
 */

defined( 'ABSPATH' ) or exit;

/*
 *  
 */

if ( !class_exists( 'YITH_WAPO_Type' ) ) {
    /**
     * Admin class.
     * The class manage all the admin behaviors.
     *
     * @since 1.0.0
     */
    class YITH_WAPO_Type {

        public $id = 0;
        public $group_id = 0;
        public $type = '';
        public $label = '';
        public $image = '';
        public $description = '';
        public $depend = '';
        public $depend_variations = '';
        public $options = '';
        public $required = 0;
        public $required_all_options = 1;
        public $sold_individually = 0;
        public $change_featured_image = 0;
        public $calculate_quantity_sum = 0;
        public $max_item_selected = 0;
        public $max_input_values_amount = 0;
        public $min_input_values_amount = 0;
        public $step = 0;
        public $priority = 0;
        public $reg_date = '0000-00-00 00:00:00';
        public $del = 0;

        /**
         * Constructor
         *
         * @access public
         * @since  1.0.0
         */
        public function __construct( $id = 0 ) {

            global $wpdb;

            if ( $id > 0 ) {

                $row = $wpdb->get_row( "SELECT * FROM {$wpdb->prefix}yith_wapo_types WHERE id='$id'" );

                if ( $row->id == $id ) {

                    $this->id                     = $row->id;
                    $this->group_id               = $row->group_id;
                    $this->type                   = $row->type;
                    $this->label                  = $row->label;
                    $this->image                  = $row->image;
                    $this->description            = $row->description;
                    $this->depend                 = $row->depend;
                    $this->depend_variations      = $row->depend_variations;
                    $this->options                = $row->options;
                    $this->required               = $row->required;
                    $this->required_all_options   = $row->required_all_options;
                    $this->step                   = $row->step;
                    $this->priority               = $row->priority;
                    $this->reg_date               = $row->reg_date;
                    $this->del                    = $row->del;
                    $this->sold_individually      = $row->sold_individually;
                    $this->max_item_selected      = $row->max_item_selected;
                    $this->max_input_values_amount = $row->max_input_values_amount;
                    $this->min_input_values_amount = $row->min_input_values_amount;
                    $this->change_featured_image  = $row->change_featured_image;
                    $this->calculate_quantity_sum = $row->calculate_quantity_sum;

                }

            }

        }

        function save( $id = 0 ) {

            global $wpdb;
            $wpdb->hide_errors();

            $new_group_id               = isset( $_POST['group_id'] ) ? $_POST['group_id'] : '';
            $new_type                   = isset( $_POST['type'] ) ? $_POST['type'] : '';
            $new_label                  = isset( $_POST['label'] ) ? htmlspecialchars( $_POST['label'] ) : '';
            $new_image                  = isset( $_POST['image'] ) ? $_POST['image'] : '';
            $new_description            = isset( $_POST['description'] ) ? $_POST['description'] : '';
            $new_depend                 = isset( $_POST['depend'] ) ? $_POST['depend'] : '';
            $new_depend_variations      = isset( $_POST['depend_variations'] ) ? $_POST['depend_variations'] : '';
            $new_options                = isset( $_POST['options'] ) ? $_POST['options'] : '';
            $new_required               = isset( $_POST['required'] ) ? $_POST['required'] : 0;
            $new_required_all_options   = isset( $_POST['required_all_options'] ) ? $_POST['required_all_options'] : 0;
            $new_sold_individually      = isset( $_POST['sold_individually'] ) ? $_POST['sold_individually'] : 0;
            $new_max_item_selected      = isset( $_POST['max_item_selected'] ) ? $_POST['max_item_selected'] : 0;
            $new_max_input_values_amount = isset( $_POST['max_input_values_amount'] ) ? $_POST['max_input_values_amount'] : 0;
            $new_min_input_values_amount = isset( $_POST['min_input_values_amount'] ) ? $_POST['min_input_values_amount'] : 0;
            $new_change_featured_image  = isset( $_POST['change_featured_image'] ) ? $_POST['change_featured_image'] : 0;
            $new_calculate_quantity_sum = isset( $_POST['calculate_quantity_sum'] ) ? $_POST['calculate_quantity_sum'] : 0;
            $new_step                   = isset( $_POST['step'] ) ? $_POST['step'] : 0;
            $new_priority               = isset( $_POST['priority'] ) ? $_POST['priority'] : 0;

            $new_depend = is_array( $new_depend ) ? implode( ',', $new_depend ) : $new_depend;
            $new_depend_variations = is_array( $new_depend_variations ) ? implode( ',', $new_depend_variations ) : $new_depend_variations;

            if ( is_array( $new_options ) ) {
                foreach ( $new_options as $key => $value ) {
                    foreach ( $value as $key_2 => $value_2 ) {
                        $new_options[$key][$key_2] = htmlspecialchars( $value_2 );
                    }
                }
                $new_options = serialize( $new_options );
            }

            if ( $id > 0 ) {

                $sql = "UPDATE {$wpdb->prefix}yith_wapo_types SET
                    group_id             = '$new_group_id',
                    type                 = '$new_type',
                    label                = '$new_label',
                    image                = '$new_image',
                    description          = '$new_description',
                    depend               = '$new_depend',
                    depend_variations    = '$new_depend_variations',
                    options              = '" . addslashes( $new_options ) . "',
                    required             = '$new_required',
                    required_all_options = '$new_required_all_options',
                    sold_individually    = '$new_sold_individually',
                    max_item_selected    = '$new_max_item_selected',
                    max_input_values_amount = '$new_max_input_values_amount',
                    min_input_values_amount = '$new_min_input_values_amount',
                    change_featured_image = '$new_change_featured_image',
                    calculate_quantity_sum = '$new_calculate_quantity_sum',
                    step                 = '$new_step',
                    priority             = '$new_priority'
                    WHERE id='$id'";

            } else {

                $sql = "INSERT INTO {$wpdb->prefix}yith_wapo_types (
                        id,
                        group_id,
                        type,
                        label,
                        image,
                        description,
                        depend,
                        depend_variations,
                        options,
                        required,
                        required_all_options,
                        sold_individually,
                        max_item_selected,
                        max_input_values_amount,
                        min_input_values_amount,
                        change_featured_image,
                        calculate_quantity_sum,
                        step,
                        priority,
                        reg_date,
                        del
                    ) VALUES (
                        '',
                        '$new_group_id',
                        '$new_type',
                        '$new_label',
                        '$new_image',
                        '$new_description',
                        '$new_depend',
                        '$new_depend_variations',
                        '$new_options',
                        '$new_required',
                        '$new_required_all_options',
                        '$new_sold_individually',
                        '$new_max_item_selected',
                        '$new_max_input_values_amount',
                        '$new_min_input_values_amount',
                        '$new_change_featured_image',
                        '$new_calculate_quantity_sum',
                        '$new_step',
                        '$new_priority',
                        CURRENT_TIMESTAMP,
                        '0'
                    )";

            }

            $wpdb->query( $sql );

            if ( YITH_WAPO::$is_wpml_installed ) {

                YITH_WAPO_WPML::register_option_type( $new_label, $new_description, $new_options );

            }

        }

        /**
         * @author Corrado Porzio
         */
        function insert() { $this->save(); }

        /**
         * @author Corrado Porzio
         *
         * @param $ids
         */
        public static function update_priorities( $ids ) {
            global $wpdb;
            $ids      = explode( ',', $ids );
            $priority = 1;
            foreach ( $ids as $key => $value ) {
                if ( $value > 0 ) {
                    $wpdb->query( "UPDATE {$wpdb->prefix}yith_wapo_types SET  priority='$priority' WHERE id='$value'" );
                    $priority ++;
                }
            }
        }

        /**
         * @author Corrado Porzio
         *
         * @param $id
         */
        function update( $id ) { $this->save( $id ); }

        /**
         * @author Corrado Porzio
         *
         * @param $id
         */
        function delete( $id ) {

            global $wpdb;
            $wpdb->hide_errors();
            $sql = "UPDATE {$wpdb->prefix}yith_wapo_types SET del = '1' WHERE id='$id'";
            $wpdb->query( $sql );

        }

        /**
         * @author Corrado Porzio
         */
        public static function create_tables() {

            /**
             * Check if dbDelta() exists
             */
            if ( !function_exists( 'dbDelta' ) ) {
                require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            }

            global $wpdb;
            $charset_collate = $wpdb->get_charset_collate();

            $create = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}yith_wapo_types (
                        id              BIGINT(20) NOT NULL AUTO_INCREMENT,
                        group_id        BIGINT(20),
                        type            VARCHAR(250),
                        label           VARCHAR(250),
                        image           VARCHAR(250),
                        description     VARCHAR(250),
                        depend          VARCHAR(250),
                        depend_variations          VARCHAR(250),
                        options         TEXT,
                        required        TINYINT(1) NOT NULL DEFAULT '0',
                        required_all_options        TINYINT(1) NOT NULL DEFAULT '1',
                        sold_individually BOOLEAN DEFAULT 0,
                        change_featured_image BOOLEAN DEFAULT 0,
                        calculate_quantity_sum BOOLEAN DEFAULT 0,
                        max_item_selected INT DEFAULT 0,
                        max_input_values_amount INT DEFAULT 0,
                        min_input_values_amount INT DEFAULT 0,
                        step            INT(2),
                        priority        INT(2),
                        reg_date        TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
                        del             TINYINT(1) NOT NULL DEFAULT '0',
                        PRIMARY KEY     (id)
                    ) $charset_collate;";
            dbDelta( $create );

        }

        /**
         * @param int  $product_id
         * @param null $wpdb
         * @param null $sold_individually
         *
         * @return array
         *
         * @author Andrea Frascaspata
         *
         */
        public static function getAllowedGroupTypes( $product_id = 0, $wpdb = null, $sold_individually = null ) {

            if ( !( $product_id > 0 ) ) {
                return array();
            }

            if ( !isset( $wpdb ) ) {
                global $wpdb;
            }

            //exclude global
            $exclude_global = get_post_meta( $product_id, '_wapo_disable_global', true ) === 'yes' ? 1 : 0;

            //visibility
            $is_administrator = current_user_can( 'administrator' ) ? 1 : 0;

            //category filter
            $category_query         = '';
            $product_categories_ids = wc_get_product_cat_ids( $product_id );

            for ( $i = 0; $i < count( $product_categories_ids ); $i ++ ) {
                $category_query .= "FIND_IN_SET( {$product_categories_ids[$i]} , ywg.categories_id )";
                if ( $i < ( count( $product_categories_ids ) - 1 ) ) {
                    $category_query .= ' or ';
                }
            }

            if ( !empty( $category_query ) ) {
                $category_query = "OR ( {$exclude_global}=0 and ( {$category_query} ) )";
            }

            //quantity type
            $sold_individually_condition = '';
            if ( isset( $sold_individually ) ) {

                if ( $sold_individually ) {
                    $sold_individually_condition = ' and ywt.sold_individually = 1 ';
                }
                else {
                    $sold_individually_condition = ' and ywt.sold_individually = 0 ';
                }

            }

            //vendor

            $vendor_filter = 'AND ( ywg.user_id=0 OR ywg.user_id IS NULL )';
            $vendor        = YITH_WAPO::get_multivendor_by_id( $product_id, 'product' );
            if ( isset( $vendor ) && is_object( $vendor ) && YITH_WAPO::is_plugin_enabled_for_vendors() ) {
                $vendor_filter = " AND ( (ywg.user_id=0 OR ywg.user_id IS NULL ) OR ywg.user_id={$vendor->id} ) ";

                // visibility

                if ( $is_administrator == 0 ) {
                    $current_logged_vendor = YITH_WAPO::get_current_multivendor();
                    $is_administrator      = isset( $current_logged_vendor ) && is_object( $current_logged_vendor ) && $current_logged_vendor->id == $vendor->id ? 1 : 0;
                }

            }

            $query = "SELECT distinct ywt.* FROM {$wpdb->prefix}yith_wapo_groups ywg join {$wpdb->prefix}yith_wapo_types ywt on ywg.id=ywt.group_id
            WHERE ywg.del='0' and ywt.del='0' and ( ( ( {$exclude_global}=0 and ( ywg.products_id='' and ywg.categories_id='' ) ) || ( FIND_IN_SET( {$product_id} , ywg.products_id ) ) {$category_query} ) and (  ywg.products_exclude_id='' || NOT FIND_IN_SET( {$product_id} , ywg.products_exclude_id ) )  and (ywg.visibility=9 or ( ywg.visibility=1 and {$is_administrator}=1 ) )) $sold_individually_condition $vendor_filter
            ORDER BY ywg.priority ASC, ywt.priority ASC";

            //var_dump($query);

            $rows = $wpdb->get_results( $query );

            return $rows;

        }

        public static function getSingleGroupType( $group_id = 0, $wpdb = null ) {

            if ( !( $group_id > 0 ) ) {
                return array();
            }

            if ( !isset( $wpdb ) ) {
                global $wpdb;
            }

            $query = "SELECT ywt.* FROM {$wpdb->prefix}yith_wapo_types ywt WHERE ywt.del='0' and ywt.id={$group_id}";

            $rows = $wpdb->get_results( $query );

            return $rows;

        }

        /**
         * @param $yith_wapo_frontend
         * @param $product
         * @param $single_type
         * @param $value
         * @param $upload_value
         *
         * @return array
         */
        public static function getCartDataByPostValue( $yith_wapo_frontend, $product, $variation, $single_type, $value, $upload_value ) {

            $cart_item_data = array();

            switch ( $single_type->type ) {

                case 'select' :

                    $cart_item_data[] = YITH_WAPO_Type::getCartDataByPostValueSelect( $yith_wapo_frontend, $product, $variation, $single_type, $value );

                    break;

                case 'checkbox' :

                    YITH_WAPO_Type::getCartDataByPostValueCheckbox( $yith_wapo_frontend, $product, $variation, $single_type, $value, $cart_item_data );

                    break;

                case 'radio' :

                    YITH_WAPO_Type::getCartDataByPostValueRadio( $yith_wapo_frontend, $product, $variation, $single_type, $value, $cart_item_data );

                    break;

                case 'file' :

                    YITH_WAPO_Type::getCartDataByPostValueFile( $yith_wapo_frontend, $product, $variation, $single_type, $upload_value, $cart_item_data );

                    break;

                case 'labels' :

                    $item_data = YITH_WAPO_Type::getCartDataByPostValueLabels( $yith_wapo_frontend, $product, $variation, $single_type, $value );

                    if( isset( $item_data ) ) {
                        $cart_item_data[] =  $item_data;
                    }

                    break;

                case 'multiple_labels' :

                    $item_data = YITH_WAPO_Type::getCartDataByPostValueMultipleLabels( $yith_wapo_frontend, $product, $variation, $single_type, $value, $cart_item_data );

                    if( isset( $item_data ) ) {
                        $cart_item_data[] =  $item_data;
                    }

                    break;

                default :

                    YITH_WAPO_Type::getCartDataByPostValueDefault( $yith_wapo_frontend, $product, $variation, $single_type, $value, $cart_item_data );

                    break;

            }

            return $cart_item_data;

        }

        /**
         * @param $yith_wapo_frontend
         * @param $product
         * @param $variation
         * @param $single_type
         * @param $value
         *
         * @return array
         */
        private static function getCartDataByPostValueSelect( $yith_wapo_frontend, $product, $variation, $single_type, $value ) {

            $price      = YITH_WAPO_Option::getOptionDataByValueSelect( $single_type, $value, 'price' );
            $price_type = YITH_WAPO_Option::getOptionDataByValueSelect( $single_type, $value, 'type' );

            return array(
                'name'                   => $single_type->label,
                'value'                  => YITH_WAPO_Option::getOptionDataByValueSelect( $single_type, $value, 'label' ),
                'price'                  => $yith_wapo_frontend->get_display_price( $product, $price, $price_type, true, $variation ),
                'price_original'         => $yith_wapo_frontend->get_display_price( $product, $price, $price_type, false, $variation ),
                'price_type'             => $price_type,
                'type_id'                => $single_type->id,
                'original_value'         => $value,
                'sold_individually'      => $single_type->sold_individually,
                'calculate_quantity_sum' => $single_type->calculate_quantity_sum,
                'add_on_type'            => $single_type->type
            );

        }

        /**
         * @param $yith_wapo_frontend
         * @param $product
         * @param $variation
         * @param $single_type
         * @param $value
         *
         * @return array
         */
        private static function getCartDataByPostValueLabels( $yith_wapo_frontend, $product, $variation, $single_type, $value ) {

            $price      = YITH_WAPO_Option::getOptionDataByValueLabels( $single_type, $value, 'price' );
            $price_type = YITH_WAPO_Option::getOptionDataByValueLabels( $single_type, $value, 'type' );

            $selected_value = YITH_WAPO_Option::getOptionDataByValueLabels( $single_type, $value, 'label' );

            if ($selected_value != '') {

                return array(
                    'name' => $single_type->label,
                    'value' => $selected_value,
                    'price' => $yith_wapo_frontend->get_display_price($product, $price, $price_type, true, $variation),
                    'price_original' => $yith_wapo_frontend->get_display_price($product, $price, $price_type, false, $variation),
                    'price_type' => $price_type,
                    'type_id' => $single_type->id,
                    'original_value' => $value,
                    'sold_individually' => $single_type->sold_individually,
                    'calculate_quantity_sum' => $single_type->calculate_quantity_sum,
                    'add_on_type' => $single_type->type
                );
            } else {

                return null;

            }

        }

        /**
         * @param $yith_wapo_frontend
         * @param $product
         * @param $variation
         * @param $single_type
         * @param $value
         * @param $cart_item_data
         */
        private static function getCartDataByPostValueMultipleLabels( $yith_wapo_frontend, $product, $variation, $single_type, $value, &$cart_item_data ) {

            if ( is_array( $value ) ) {

                $i = 0;

                foreach ( $value as $key => $single_value ) {

                    $single_value = trim( $single_value, ' ' );

                    if ( $single_value != '' ) {

                        $selected_value = YITH_WAPO_Option::getOptionDataByValueMultipleLabels( $single_type, $single_value, 'label' );

                        if( $selected_value != '' ) {

                            $price            = YITH_WAPO_Option::getOptionDataByValueKey( $single_type, $key, 'price' );
                            $price_type       = YITH_WAPO_Option::getOptionDataByValueKey( $single_type, $key, 'type' );
                            $cart_item_data[] = array(
                                'name'                   => $single_type->label,
                                'value'                  => $selected_value,
                                'price'                  => $yith_wapo_frontend->get_display_price( $product, $price, $price_type, true, $variation , null , $single_value ),
                                'price_original'         => $yith_wapo_frontend->get_display_price( $product, $price, $price_type, false, $variation , null , $single_value ),
                                'price_type'             => $price_type,
                                'type_id'                => $single_type->id,
                                'original_value'         => $value,
                                'original_index'         => $i,
                                'sold_individually'      => $single_type->sold_individually,
                                'calculate_quantity_sum' => $single_type->calculate_quantity_sum,
                                'add_on_type'            => $single_type->type
                            );

                            $i ++;

                        }


                    }

                }

            }

        }

        /**
         * @param $yith_wapo_frontend
         * @param $product
         * @param $variation
         * @param $single_type
         * @param $value
         * @param $cart_item_data
         */
        private static function getCartDataByPostValueCheckbox( $yith_wapo_frontend, $product, $variation, $single_type, $value, &$cart_item_data ) {

            if ( is_array( $value ) ) {

                $i = 0;

                foreach ( $value as $key => $single_value ) {

                    $price            = YITH_WAPO_Option::getOptionDataByValueKey( $single_type, $key, 'price' );
                    $price_type       = YITH_WAPO_Option::getOptionDataByValueKey( $single_type, $key, 'type' );
                    $cart_item_data[] = array(
                        'name'              => $single_type->label,
                        'value'             => YITH_WAPO_Option::getOptionDataByValueKey( $single_type, $key, 'label' ),
                        'price'             => $yith_wapo_frontend->get_display_price( $product, $price, $price_type, true, $variation ),
                        'price_original'    => $yith_wapo_frontend->get_display_price( $product, $price, $price_type, false, $variation ),
                        'price_type'        => $price_type,
                        'type_id'           => $single_type->id,
                        'original_value'    => $value,
                        'original_index'    => $i,
                        'sold_individually' => $single_type->sold_individually,
                        'calculate_quantity_sum' => $single_type->calculate_quantity_sum,
                        'add_on_type'       => $single_type->type
                    );

                    $i ++;

                }

            }

        }

        /**
         * @param $yith_wapo_frontend
         * @param $product
         * @param $variation
         * @param $single_type
         * @param $value
         * @param $cart_item_data
         */
        private static function getCartDataByPostValueRadio( $yith_wapo_frontend, $product, $variation, $single_type, $value, &$cart_item_data ) {

            if ( is_array( $value ) ) {

                $i = 0;

                foreach ( $value as $key => $single_value ) {

                    if ( $single_value != '' ) {

                        $price            = YITH_WAPO_Option::getOptionDataByValueRadio( $single_type, $single_value, 'price' );
                        $price_type       = YITH_WAPO_Option::getOptionDataByValueRadio( $single_type, $single_value, 'type' );
                        $cart_item_data[] = array(
                            'name'                   => $single_type->label,
                            'value'                  => YITH_WAPO_Option::getOptionDataByValueRadio( $single_type, $single_value, 'label' ),
                            'price'                  => $yith_wapo_frontend->get_display_price( $product, $price, $price_type, true, $variation ),
                            'price_original'         => $yith_wapo_frontend->get_display_price( $product, $price, $price_type, false, $variation ),
                            'price_type'             => $price_type,
                            'type_id'                => $single_type->id,
                            'original_value'         => $value,
                            'original_index'         => $i,
                            'sold_individually'      => $single_type->sold_individually,
                            'calculate_quantity_sum' => $single_type->calculate_quantity_sum,
                            'add_on_type'            => $single_type->type
                        );

                    }

                    $i ++;

                }

            }

        }

        /**
         * @param $yith_wapo_frontend
         * @param $product
         * @param $variation
         * @param $single_type
         * @param $upload_value
         * @param $cart_item_data
         */
        private static function getCartDataByPostValueFile( $yith_wapo_frontend, $product, $variation, $single_type, $upload_value, &$cart_item_data ) {

            if ( is_array( $upload_value ) ) {

                if ( !isset( $upload_value['name'] ) ) {
                    return;
                }

                for ( $i = 0; $i < count( $upload_value['name'] ); $i ++ ) {

                    if ( isset( $upload_value['name'][$i] ) && !empty( $upload_value['name'][$i] ) ) {
                        // allowed upload types
                        $extension = '';
                        $pathinfo  = pathinfo( $upload_value['name'][$i] );
                        if ( is_array( $pathinfo ) ) {
                            $extension = '.' . $pathinfo['extension'];
                        }

                        if ( !is_array( $yith_wapo_frontend->_option_upload_allowed_type ) || !in_array( $extension, $yith_wapo_frontend->_option_upload_allowed_type ) ) {
                            wc_add_notice( sprintf( __( 'Uploading error: %s extension is not allowed', 'yith-woocommerce-product-add-ons' ), $extension ) );
                            continue;
                        }

                        $file_data['name']     = $upload_value['name'][$i];
                        $file_data['type']     = $upload_value['type'][$i];
                        $file_data['tmp_name'] = $upload_value['tmp_name'][$i];
                        $file_data['error']    = $upload_value['error'][$i];
                        $file_data['size']     = $upload_value['size'][$i];

                        $uploaded_file = YITH_WAPO_Type::getUploadedFile( $yith_wapo_frontend, $file_data );

                        $value = '';
                        if ( empty( $uploaded_file['error'] ) && !empty( $uploaded_file['file'] ) ) {
                            $value = '<a href="' . esc_url( $uploaded_file['url'] ) . '" target="_blank">' . $uploaded_file['type'] . '</a>';
                        }
                        else {
                            wc_add_notice( $uploaded_file['error'] );
                            continue;
                        }

                        $price      = YITH_WAPO_Option::getOptionDataByValueKey( $single_type, $i, 'price' );
                        $price_type = YITH_WAPO_Option::getOptionDataByValueKey( $single_type, $i, 'type' );

                        $cart_item_data[] = array(
                            'name'                   => YITH_WAPO_Option::getOptionDataByValueKey( $single_type, $i, 'label' ),
                            'value'                  => $value,
                            'price'                  => $yith_wapo_frontend->get_display_price( $product, $price, $price_type, true, $variation ),
                            'price_original'         => $yith_wapo_frontend->get_display_price( $product, $price, $price_type, false, $variation ),
                            'price_type'             => $price_type,
                            'type_id'                => $single_type->id,
                            'original_value'         => $uploaded_file,
                            'original_index'         => $i,
                            'uploaded_file'          => true,
                            'sold_individually'      => $single_type->sold_individually,
                            'calculate_quantity_sum' => $single_type->calculate_quantity_sum,
                            'add_on_type'            => $single_type->type
                        );

                    }

                }

            }

        }

        /**
         * @param $yith_wapo_frontend
         * @param $upload_value
         *
         * @return bool
         */
        public static function checkUploadedFilesError( $yith_wapo_frontend, $upload_value ) {

            if ( is_array( $upload_value ) ) {

                $max_allowed_size = get_option( 'yith_wapo_settings_upload_size', 0 ) * 1024 * 1024;

                for ( $i = 0; $i < count( $upload_value['name'] ); $i ++ ) {

                    if ( isset( $upload_value['name'][$i] ) && !empty( $upload_value['name'][$i] ) ) {
                        // allowed upload types
                        $extension = '';
                        $pathinfo  = pathinfo( $upload_value['name'][$i] );
                        if ( is_array( $pathinfo ) ) {
                            $extension = '.' . $pathinfo['extension'];
                        }

                        if ( !is_array( $yith_wapo_frontend->_option_upload_allowed_type ) || !in_array( $extension, $yith_wapo_frontend->_option_upload_allowed_type ) ) {
                            wc_add_notice( sprintf( __( 'Uploading error: %s extension is not allowed', 'yith-woocommerce-product-add-ons' ), $extension ) );
                            return false;
                        }

                        // check max size
                        if ( $upload_value['size'][$i] > $max_allowed_size ) {

                            wc_add_notice( sprintf( __( 'Uploading error: %s exceeded max size allowed for this file',
                                'yith-woocommerce-product-add-ons' ),
                                $extension ) ); //@since 1.1.0
                            return false;

                        }

                    }

                }

            }

            return true;

        }

        /**
         * @param $yith_wapo_frontend
         * @param $file
         *
         * @return array
         */
        private static function getUploadedFile( $yith_wapo_frontend, $file ) {

            include_once( ABSPATH . 'wp-admin/includes/file.php' );
            include_once( ABSPATH . 'wp-admin/includes/media.php' );

            add_filter( 'upload_dir', array( $yith_wapo_frontend, 'upload_dir' ) );

            $upload = wp_handle_upload( $file, array( 'test_form' => false ) );

            remove_filter( 'upload_dir', array( $yith_wapo_frontend, 'upload_dir' ) );

            return $upload;

        }

        /**
         * @param $yith_wapo_frontend
         * @param $product
         * @param $variation
         * @param $single_type
         * @param $value
         * @param $cart_item_data
         */
        private static function getCartDataByPostValueDefault( $yith_wapo_frontend, $product, $variation, $single_type, $value, &$cart_item_data ) {

            if ( is_array( $value ) ) {

                $i = 0;

                foreach ( $value as $key => $single_value ) {

                    $single_value = trim( $single_value, ' ' );

                    if ( $single_value != '' || ( is_array( $single_value ) && ! empty( $single_value ) ) ) {

                        $price            = YITH_WAPO_Option::getOptionDataByValueKey( $single_type, $key, 'price' );
                        $price_type       = YITH_WAPO_Option::getOptionDataByValueKey( $single_type, $key, 'type' );
                        $cart_item_data[] = array(
                            'name'                   => YITH_WAPO_Option::getOptionDataByValueKey( $single_type, $key, 'label' ),
                            'value'                  => $single_value,
                            'price'                  => $yith_wapo_frontend->get_display_price( $product, $price, $price_type, true, $variation , null , $single_value ),
                            'price_original'         => $yith_wapo_frontend->get_display_price( $product, $price, $price_type, false, $variation , null , $single_value ),
                            'price_type'             => $price_type,
                            'type_id'                => $single_type->id,
                            'original_value'         => $value,
                            'original_index'         => $i,
                            'sold_individually'      => $single_type->sold_individually,
                            'calculate_quantity_sum' => $single_type->calculate_quantity_sum,
                            'add_on_type'            => $single_type->type
                        );

                    }

                    $i ++;

                }

            }

        }

        /**
         * @param $wpdb
         * @param $group
         * @param null $type
         */
        public static function printOptionTypeForm( $wpdb, $group, $type = null ) {

            include( YITH_WAPO_TEMPLATE_ADMIN_PATH . 'yith-wapo-form-option-type.php' );

        }


    }

}