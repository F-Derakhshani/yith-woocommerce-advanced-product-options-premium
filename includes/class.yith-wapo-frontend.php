<?php
/**
 * Frontend class
 *
 * @author  Your Inspiration Themes
 * @package YITH WooCommerce Ajax Navigation
 * @version 1.3.2
 */

if ( ! defined( 'YITH_WAPO' ) ) {
    exit;
} // Exit if accessed directly

if ( ! class_exists( 'YITH_WAPO_Frontend' ) ) {
    /**
     * Frontend class.
     * The class manage all the frontend behaviors.
     *
     * @since 1.0.0
     */
    class YITH_WAPO_Frontend {
        /**
         * Plugin version
         *
         * @var string
         * @since 1.0.0
         */
        public $version;

        /**
     * @var string
     * @since 1.0.0
     */
        public $_option_show_label_type;

        /**
         * @var string
         * @since 1.0.0
         */
        public $_option_show_description_type;

        /**
         * @var string
         * @since 1.0.0
         */
        public $_option_show_image_type;

        /**
     * @var string
     * @since 1.0.0
     */
        public $_option_show_description_option;

        /**
         * @var string
         * @since 1.0.0
         */
        public $_option_show_image_option;

        /**
         * @var string
         * @since 1.0.0
         */
        public $_option_icon_description_option_url;

        /**
         * @var string
         * @since 1.0.0
         */
        public $_option_upload_folder_name;

        /**
         * @var string
         * @since 1.0.0
         */
        public $_option_upload_allowed_type;

        /**
         * @var string
         * @since 1.0.0
         */
        public $_option_loop_add_to_cart_text;

        /**
         * Constructor
         *
         * @access public
         * @since  1.0.0
         */
        public function __construct( $version ) {

            $this->version = $version;

            // Body class

            add_filter( 'body_class', function( $classes ) {
                return array_merge( $classes, array( 'yith-wapo-frontend' ) );
            } );

            //Settings

            $this->_option_show_label_type = get_option( 'yith_wapo_settings_showlabeltype' , 'yes' );
            $this->_option_show_description_type = get_option( 'yith_wapo_settings_showdescrtype' , 'yes' );
            $this->_option_show_image_type = get_option( 'yith_wapo_settings_showimagetype' , 'yes' );
            $this->_option_show_description_option = get_option( 'yith_wapo_settings_showdescropt' , 'yes' );
            $this->_option_show_image_option = get_option( 'yith_wapo_settings_showimageopt' , 'yes' );
            $this->_option_icon_description_option_url = get_option( 'yith_wapo_settings_tooltip_icon' , YITH_WAPO_ASSETS_URL . '/img/description-icon.png' );
            $this->_option_upload_folder_name = get_option( 'yith_wapo_settings_uploadfolder' , 'yith_advanced_product_options' );

            $this->_option_upload_allowed_type = get_option( 'yith_wapo_settings_filetypes' , '' );
            if( !empty( $this->_option_upload_allowed_type ) ) {
                $this->_option_upload_allowed_type = explode( ',' , $this->_option_upload_allowed_type );

                if( is_array( $this->_option_upload_allowed_type ) ) {

                    foreach( $this->_option_upload_allowed_type as &$extension ) {
                        $extension = trim( $extension , ' ' );
                    }

                }

            }

            $this->_option_loop_add_to_cart_text = get_option( 'yith_wapo_settings_addtocartlabel' ,__( 'Select options' , 'yith-woocommerce-product-add-ons' ) );

            $this->_option_loop_add_to_cart_text = call_user_func( '__' , $this->_option_loop_add_to_cart_text, 'yith-woocommerce-product-add-ons' );

            //Actions

            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles_scripts' ) , 999);

            // Show product addons

            $form_position = get_option( 'yith_wapo_settings_formposition' , 'before' );

            add_action( 'woocommerce_'.$form_position.'_add_to_cart_button', array( $this, 'show_product_options' ) );

            // gift card integration

            add_action( 'yith_gift_cards_template_'.$form_position.'_add_to_cart_button', array( $this, 'show_product_options' ) );

            add_action( 'wc_ajax_yith_wapo_update_variation_price', array( $this, 'yith_wapo_update_variation_price' ) );

            add_action( 'wc_ajax_yith_wapo_get_calculated_display_price', array( $this, 'yith_wapo_get_calculated_display_price' ) );

            // Products Loop

            add_filter( 'woocommerce_product_add_to_cart_url', array( $this, 'add_to_cart_url' ), 50, 1 );

            add_action( 'woocommerce_product_add_to_cart_text', array( $this, 'add_to_cart_text' ), 10, 1 );

            /* yith theme */
            add_action( 'add_to_cart_text', array( $this, 'add_to_cart_text' ), 99, 1 );

            // cart actions

            // Add item data to the cart

            add_filter( 'woocommerce_add_to_cart_validation', array( $this, 'add_to_cart_validation' ), 50, 6 );

            add_filter( 'woocommerce_add_cart_item_data', array( $this, 'add_cart_item_data' ), 10, 2 );

            add_filter( 'woocommerce_cart_item_quantity' , array( $this, 'woocommerce_cart_item_quantity' ) , 10 , 3 );

            // Add to cart
            add_filter( 'woocommerce_add_cart_item', array( $this, 'add_cart_item' ), 20, 1 );

            // Get item data to display
            add_filter( 'woocommerce_get_item_data', array( $this, 'get_item_data' ), 10, 2 );

            // Load cart data per page load
            add_filter( 'woocommerce_get_cart_item_from_session', array( $this, 'get_cart_item_from_session' ), 20, 2 );

            // Add meta to order
            add_action( 'woocommerce_add_order_item_meta', array( $this, 'order_item_meta' ), 10, 2 );
            
            // Email Order
            
            add_filter( 'woocommerce_order_items_meta_get_formatted' , array( $this, 'order_items_meta_display' ) );

            // order again functionality
            add_filter( 'woocommerce_order_again_cart_item_data', array( $this, 're_add_cart_item_data' ), 10, 3 );

            // remove undo link
            add_action( 'woocommerce_cart_item_restored' , array( $this, 'cart_item_restored' ), 10, 2 );

            // sold individually options actions

            add_action( 'woocommerce_add_to_cart', array( $this, 'add_to_cart_sold_individually' ) , 10 , 6 ) ;

            add_filter( 'woocommerce_cart_item_remove_link' , array( $this, 'hide_remove_link' ) , 10 , 2 );

            add_filter( 'woocommerce_cart_item_thumbnail' , array( $this, 'hide_thumbnail_name' ) , 10 , 2 );

            add_filter( 'woocommerce_cart_item_name' , array( $this, 'hide_thumbnail_name' ) , 10 , 2 );

            add_action( 'woocommerce_cart_item_removed', array( $this, 'remove_items_from_cart' ), 10 , 2 );

            // Subscription Cart Price

            add_filter( 'ywsbs_get_price_html' , array( $this , 'ywapo_ywsbs_get_price_html' ) , 10 , 2 );

            add_filter( 'ywsbs_order_formatted_line_subtotal', array( $this , 'ywsbs_order_formatted_line_subtotal') , 10 , 4 );

            add_filter( 'ywsbs_add_cart_item_data' , array( $this , 'ywsbs_add_cart_item_data' ) , 10 , 4 );

            // Product Bundle

            add_action( 'yith_wcpb_after_get_per_item_price_tot_with_params' , array( $this , 'ywcpb_print_calculated_price' ) , 10 , 5 );
            
            add_filter( 'yith_wcpb_woocommerce_cart_item_price' , array( $this, 'ywcpb_woocommerce_cart_item_price' ) ,10 , 3 ) ;

            add_filter( 'yith_wcpb_bundle_pip_bundled_items_subtotal' , array( $this, 'ywcpb_bundle_pip_bundled_items_subtotal' ) ,10 , 3 );

            add_filter( 'woocommerce_order_formatted_line_subtotal' , array( $this, 'order_item_subtotal' ) ,10 , 3 );

            add_filter( 'yith_wcpb_add_cart_item_data_check' , array( $this, 'exclude_sold_individually' ) , 10 , 2 );

            // Composite Products

            add_filter( 'yith_wcp_composite_add_child_items' , array( $this, 'exclude_sold_individually' ) , 10 , 2 );

            add_filter( 'yith_wcp_calculate_subtotals' , array( $this, 'exclude_sold_individually' ) , 10 , 2 );

            // end cart actions

            // YITH WAPO Loaded
            do_action( 'yith_wapo_loaded' );

        }

        /**
         * Enqueue frontend styles and scripts
         *
         * @access public
         * @return void
         * @since  1.0.0
         */
        public  function enqueue_styles_scripts() {

            $suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

            // css

            wp_register_style( 'jquery-ui', YITH_WAPO_ASSETS_URL. '/css/jquery-ui.min.css', false, '1.11.4' );
            wp_enqueue_style( 'jquery-ui' );

            wp_register_style( 'yith_wapo_frontend-colorpicker', YITH_WAPO_ASSETS_URL . '/css/color-picker'.$suffix.'.css' , array( 'yith_wapo_frontend' ), $this->version );
            wp_register_style( 'yith_wapo_frontend', YITH_WAPO_ASSETS_URL . '/css/yith-wapo.css' , false, $this->version );

            wp_enqueue_style( 'yith_wapo_frontend-colorpicker' );
            wp_enqueue_style( 'yith_wapo_frontend' );

            // js

            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'jquery-ui-core' );
            wp_enqueue_script( 'jquery-ui-datepicker' );

            wp_register_script( 'yith_wapo_frontend-jquery-ui', YITH_WAPO_ASSETS_URL . '/js/jquery-ui/jquery-ui'. $suffix .'.js', '', '1.11.4', true );
            wp_register_script( 'yith_wapo_frontend-accounting', YITH_WAPO_ASSETS_URL . '/js/accounting'. $suffix .'.js', '', '0.4.2', true );
            wp_register_script( 'yith_wapo_frontend-iris', YITH_WAPO_ASSETS_URL . '/js/iris.min.js', array( 'jquery'), '1.0.0', true );
            wp_register_script( 'yith_wapo_frontend-colorpicker', YITH_WAPO_ASSETS_URL . '/js/color-picker'. $suffix .'.js', array( 'jquery'), '1.0.0', true );
            wp_register_script( 'yith_wapo_frontend', YITH_WAPO_ASSETS_URL . '/js/yith-wapo-frontend'. $suffix .'.js', array( 'jquery', 'wc-add-to-cart-variation' ), $this->version, true );

            wp_enqueue_script( 'yith_wapo_frontend-jquery-ui' );
            wp_enqueue_script( 'yith_wapo_frontend-accounting' );
            wp_enqueue_script( 'yith_wapo_frontend-iris' );
            wp_enqueue_script( 'yith_wapo_frontend-colorpicker' );
            wp_enqueue_script( 'wc-add-to-cart-variation' );
            wp_enqueue_script( 'yith_wapo_frontend' );

            $script_params = array(
                'ajax_url'                     => admin_url( 'admin-ajax' ) . '.php',
                'wc_ajax_url'                  => WC_AJAX::get_endpoint( "%%endpoint%%" ),
                'tooltip'                      => get_option( 'yith-wapo-enable-tooltip' ) == 'yes',
                'tooltip_pos'                  => get_option( 'yith-wapo-tooltip-position' ),
                'tooltip_ani'                  => get_option( 'yith-wapo-tooltip-animation' ),
                'currency_format_num_decimals' => absint( get_option( 'woocommerce_price_num_decimals' ) ),
                'currency_format_symbol'       => get_woocommerce_currency_symbol(),
                'currency_format_decimal_sep'  => esc_attr( stripslashes( get_option( 'woocommerce_price_decimal_sep' ) ) ),
                'currency_format_thousand_sep' => esc_attr( stripslashes( get_option( 'woocommerce_price_thousand_sep' ) ) ),
                'currency_format'              => esc_attr( str_replace( array( '%1$s', '%2$s' ), array( '%s', '%v' ), get_woocommerce_price_format() ) ),
                'do_submit'                    => true,
                'date_format'                  => get_option( 'yith_wapo_settings_date_format', 'mm/dd/yy' ),
                'keep_price_shown'             => get_option( 'yith_wapo_settings_show_add_ons_price_table' , false ) == 'yes'
            );

            wp_localize_script( 'yith_wapo_frontend', 'yith_wapo_general', $script_params );


            $color_picker_param = array(
                'clear'                                 => __( 'Clear' , 'yith-woocommerce-product-add-ons' ),
                'defaultString'                         => __( 'Default' , 'yith-woocommerce-product-add-ons' ),
                'pick'                                  => __( 'Select color' , 'yith-woocommerce-product-add-ons' ),
                'current'                               => __( 'Current color' , 'yith-woocommerce-product-add-ons' ),
            ) ;

            wp_localize_script( 'yith_wapo_frontend', 'wpColorPickerL10n', $color_picker_param );


            $color      = get_option( 'yith-wapo-tooltip-text-color' );
            $background = get_option( 'yith-wapo-tooltip-background' );

            $inline_css = "
            .ywapo_option_description .yith_wccl_tooltip > span {
                background: {$background};
                color: {$color};
            }
            .ywapo_option_description .yith_wccl_tooltip.bottom span:after {
                border-bottom-color: {$background};
            }
            .ywapo_option_description .yith_wccl_tooltip.top span:after {
                border-top-color: {$background};
            }";

            wp_add_inline_style( 'yith_wapo_frontend', $inline_css );

        }

        /**
         * Show the product advanced options
         *
         * @access public
         * @author Andrea Frascaspata
         * @since  1.0.0
         */
        public function show_product_options() {

              global $product;

              if( is_object($product) ) {

                  $product_id = yit_get_base_product_id( $product );

                  if( $product_id > 0 ) {

                      $product_type_list = YITH_WAPO::getAllowedProductTypes();

                      if( in_array( $product->get_type(), $product_type_list ) ) {

                          $types_list = YITH_WAPO_Type::getAllowedGroupTypes( $product_id );

                          echo '<div id="yith_wapo_groups_container" class="yith_wapo_groups_container">';

                          wc_get_template( 'yith-wapo-group-container.php', array(
                              'yith_wapo_frontend' => $this,
                              'product'            => $product,
                              'types_list'         => $types_list
                          ), '', YITH_WAPO_TEMPLATE_FRONTEND_PATH );

                          echo '</div>';

                      }

                  }

              }

        }

        /**
         * Print the single product options group
         *
         * @access private
         * @author Andrea Frascaspata
         * @since  1.0.0
         */
        public function printSingleGroupType( $product, $single_type ) {

               $single_type = ( array ) $single_type;

                //--- WPML ---
                if( YITH_WAPO::$is_wpml_installed ) {

                    $single_type['label'] = YITH_WAPO_WPML::string_translate( $single_type['label'] );
                    $single_type['description'] = YITH_WAPO_WPML::string_translate( $single_type['description'] );

                }
                //---END WPML---------

                wc_get_template( 'yith-wapo-group-type.php', array(
                    'yith_wapo_frontend' => $this,
                    'product'            => $product,
                    'single_type'          => $single_type,
                ), '', YITH_WAPO_TEMPLATE_FRONTEND_PATH );

        }

        /**
         * @param        $key
         * @param        $product
         * @param        $type_id
         * @param        $type
         * @param        $name
         * @param        $value
         * @param        $price
         * @param string $label
         * @param string $image
         * @param string $price_type
         * @param string $description
         * @param bool   $required
         * @param bool   $checked
         * @param string $disabled
         * @param string $label_position
         * @param bool   $min
         * @param bool   $max
         */
        public function printOptions( $key , $product, $type_id ,$type , $name, $value, $price ,$label='',$image='', $price_type='fixed', $description='', $required=false , $checked=false, $disabled='' , $label_position ='before' , $min = false , $max = false  ){

            // arg type exception

            if( $type=='text' || $type=='number' || $type=='range' ||  $type=='textarea' ||  $type=='color' ||  $type=='date' ) {
                $value='';
            }


            if( $type=='radio' || $type=='checkbox' ) {
                $label_position='after';
            }

            //----Catalog Mode-----------------------------------------

            $product_id = yit_get_base_product_id( $product );

            if( $this->hide_price( $product_id ) ) {
                $price = 0;
            }

            //---------------------------------------------------------

            $price_calculated = $this->get_display_price( $product , $price , $price_type );
            $price_hmtl   = ! empty( $price ) ? sprintf( '<span class="ywapo_label_price"> + %s</span>', wc_price( $price_calculated ? $price_calculated : $price ) ) : '';

            $image_html = '';
            if( $image ) {
                $image_html = '<img src="'.esc_attr( $image ).'" alt="" class="ywapo_single_option_image">';
            }

            $control_id = 'ywapo_ctrl_id_'.$type_id.'_'.$key;
            
            $required_simbol =  $required && ( $type != 'select' && $type != 'labels' && $type != 'multiple_labels' && $type != 'radio' ) ? '<abbr class="required" title="required">*</abbr>' : '' ;
            $span_label   = sprintf( '<label for="%s" class="ywapo_label ywapo_label_tag_position_%s">%s<span class="ywapo_option_label ywapo_label_position_%s">%s</span>%s</label>', $control_id , $label_position , $image_html, $label_position, esc_html( $label ) , $required_simbol );
            $before_label = $label_position == 'before' ? $span_label : '';
            $after_label  = $label_position == 'after' ? $span_label : '';
            
            $min_html = $min !== false && is_numeric($min) ? 'min="' . esc_attr( $min ) . '"' : '';
            $max_html = $max !== false && is_numeric($max) ? 'max="' . esc_attr( $max ) . '"' : '';

            $max_length = $max !== false && is_numeric($max) ? 'maxlength="' . esc_attr( $max ) . '"' : '';

            $default_args = array(
                'yith_wapo_frontend' => $this,
                'control_id'         => $control_id,
                'product'            => $product,
                'key'                => $key,
                'type_id'            => $type_id,
                'type'               => $type,
                'name'               => $name,
                'value'              => $value,
                'price'              => $price,
                'price_hmtl'         => $price_hmtl,
                'price_type'         => $price_type,
                'price_calculated'   => $price_calculated,
                'label'              => $label,
                'span_label'         => $span_label,
                'before_label'       => $before_label,
                'after_label'        => $after_label,
                'description'        => $description,
                'required'           => $required,
                'checked'            => $checked,
                'disabled'           => $disabled,
                'label_position'     => $label_position,
                'min'                => $min ,
                'max'                => $max ,
                'min_html'           => $min_html,
                'max_html'           => $max_html,
                'max_length'         => $max_length,
                'image_url'          => esc_attr( $image )
            );

            switch ( $type ) {

                case 'textarea':

                    wc_get_template( 'yith-wapo-input-textarea.php', $default_args, '', YITH_WAPO_TEMPLATE_FRONTEND_PATH );

                    break;

                case 'select':

                    wc_get_template( 'yith-wapo-input-select.php', $default_args, '', YITH_WAPO_TEMPLATE_FRONTEND_PATH );

                    break;

                case 'labels':

                    wc_get_template( 'yith-wapo-input-labels.php', $default_args, '', YITH_WAPO_TEMPLATE_FRONTEND_PATH );

                    break;

                case 'multiple_labels':

                    wc_get_template( 'yith-wapo-input-multiple-labels.php', $default_args, '', YITH_WAPO_TEMPLATE_FRONTEND_PATH );

                    break;

                case 'color':

                    wc_get_template( 'yith-wapo-input-color.php', $default_args, '', YITH_WAPO_TEMPLATE_FRONTEND_PATH );

                    break;

                default :

                    wc_get_template( 'yith-wapo-input-base.php', $default_args , '', YITH_WAPO_TEMPLATE_FRONTEND_PATH );

            }

        }


        /**
         *@author Andrea Frascaspata
         */
        public function yith_wapo_update_variation_price(){

            if( ! isset( $_REQUEST['variation_id'] ) || ! isset( $_REQUEST['variation_price'] ) || ! isset( $_REQUEST['type_id'] ) || ! isset( $_REQUEST['option_index'] ) ) {
               wp_die();
            }

            $variation_id = intval( $_REQUEST['variation_id'] );

            if( $variation_id > 0 ) {

                $variation = new WC_Product_Variation( $variation_id );

                if( is_object( $variation ) ) {

                    $product_id = yit_get_base_product_id( $variation );

                    $product = wc_get_product( $product_id );

                    if( is_object( $product ) ) {

                        $type_id = intval( $_REQUEST['type_id'] );

                        if( $type_id > 0 ) {

                            $single_group_type = YITH_WAPO_Type::getSingleGroupType( $type_id );

                            if( is_array( $single_group_type ) ) $single_group_type = $single_group_type[0];

                            if( is_object( $single_group_type ) ) {

                                $option_index = $_REQUEST['option_index'];

                                if( $option_index >= 0 ) {

                                    $options = $single_group_type->options;
                                    $options = maybe_unserialize( $options );

                                    if( is_array( $options ) ) {

                                        $price = $options['price'][$option_index];
                                        $price_type = $options['type'][$option_index];
                                        $value = isset( $_REQUEST['option_value'] ) ? $_REQUEST['option_value'] : null;
                                        $price_calculated = $this->get_display_price( $product, $price , $price_type, true, $variation , null , $value);

                                        echo $price_calculated;

                                    }
                                }
                            }
                        }
                    }
                }
            }

            wp_die();

        }

        /**
         * @author Andrea Frascaspata
         */
        public function yith_wapo_get_calculated_display_price(){

            if( ! isset( $_REQUEST['product_id'] ) || ! isset( $_REQUEST['product_price'] ) || ! isset( $_REQUEST['type_id'] ) || ! isset( $_REQUEST['option_index'] ) ) {
                wp_die();
            }
            
            $product_id = intval($_REQUEST['product_id']);

            if( $product_id > 0 ) {

                $type_id = intval( $_REQUEST['type_id'] );

                $product = wc_get_product($product_id);

                $product_price = floatval( $_REQUEST['product_price'] );

                if( is_object($product) && $type_id > 0 && $product_price > 0 ) {

                    $single_group_type = YITH_WAPO_Type::getSingleGroupType( $type_id );

                    if( is_array( $single_group_type ) ) $single_group_type = $single_group_type[0];

                    if( is_object( $single_group_type ) ) {

                        $option_index = $_REQUEST['option_index'];

                        if( $option_index >= 0 ) {

                            $options = $single_group_type->options;
                            $options = maybe_unserialize( $options );

                            if( is_array( $options ) ) {

                                $price = $options['price'][$option_index];
                                $price_type = $options['type'][$option_index];
                                $value = isset( $_REQUEST['option_value'] ) ? $_REQUEST['option_value'] : null;
                                $price_calculated = $this->get_display_price( $product, $price , $price_type, true, null , $product_price , $value );

                                echo $price_calculated;

                            }
                        }
                    }
                }

            }

            wp_die();

        }

        /**
         * @param $description
         * @return string
         */
        public function getTooltip( $description ){

            if( $description ) {

                $icon_url = !empty( $this->_option_icon_description_option_url ) ? $this->_option_icon_description_option_url : YITH_WAPO_ASSETS_URL.'/img/description-icon.png' ;

                $tooltip = '<div class="ywapo_option_description" data-tooltip="'.esc_attr( $description ).'">';

                $tooltip .= '<span><img src="'.esc_url( $icon_url ).'" alt=""></span>';

                $tooltip .= '</div>';

                return $tooltip;
            }

            return '';
        }

        /**
         * @param string $text
         * @return string|void
         */
        public function add_to_cart_text( $text="" ) {

            global $product, $post;

            if ( is_object( $product )  && ! is_single( $post ) ) {

                $product_type_list = YITH_WAPO::getAllowedProductTypes();

                if( in_array( $product->get_type(), $product_type_list ) ) {

                    $product_id = yit_get_base_product_id( $product );

                    $types_list = YITH_WAPO_Type::getAllowedGroupTypes( $product_id );

                    if ( !empty( $types_list ) ) {
                        $text = ! empty( $this->_option_loop_add_to_cart_text ) ? $this->_option_loop_add_to_cart_text : __( 'Select options', 'yith-woocommerce-product-add-ons' );
                    }

                }

            }

            return $text;
        }

        /**
         * @param string $url
         * @return false|string
         */
        public function add_to_cart_url( $url='' ) {

            global $product;

            if ( is_object( $product ) && ( ( is_shop() || is_product_category() || is_product_tag() ) ) ) {

                $product_type_list = YITH_WAPO::getAllowedProductTypes();

                if( in_array( $product->get_type(), $product_type_list ) ) {

                    $product_id = yit_get_base_product_id( $product );

                    $types_list = YITH_WAPO_Type::getAllowedGroupTypes( $product_id );

                    if ( !empty( $types_list ) ) {
                        $url = get_permalink($product_id );
                    }

                }

            }

            return $url;
        }

        /**
         * @param $passed
         * @param $product_id
         * @param $qty
         * @param string $variation_id
         * @param array $variations
         * @param array $cart_item_data
         * @return bool
         */
        public function add_to_cart_validation( $passed, $product_id, $qty, $variation_id = '', $variations = array(), $cart_item_data = array() ) {

            /* disables add_to_cart_button class on shop page */
            if ( is_ajax() && ! isset( $_REQUEST[ 'yith_wapo_is_single' ] ) ) {

                $product = wc_get_product( $product_id );

                $product_type_list = YITH_WAPO::getAllowedProductTypes();

                if( in_array( $product->get_type(), $product_type_list ) ) {

                    $product_parent_id = yit_get_base_product_id( $product );

                    $types_list = YITH_WAPO_Type::getAllowedGroupTypes( $product_parent_id );

                    if ( ! empty( $types_list ) ) {
                        return false;
                    }

                }
            }

            if ( ! empty( $_FILES ) ) {
                $upload_data = $_FILES;

                foreach( $upload_data as $single_data ) {
                    $passed = YITH_WAPO_Type::checkUploadedFilesError( $this , $single_data );
                    if( !$passed ) {
                        break;
                    }
                }

            }

            return $passed;
        }


        /**
         * @param $cart_item_meta
         * @param $product_id
         * @param null $post_data
         * @param bool $sold_individually
         *
         * @author Andrea Frascaspata
         * @return mixed
         * @throws Exception
         */
        public function add_cart_item_data( $cart_item_meta, $product_id, $post_data = null , $sold_individually = false , $cart_item_key_parent = 0 ) {

            /* Others Plugin Exceptions */

            if( isset( $cart_item_meta['bundled_by'] ) || isset( $cart_item_meta['yith_wcp_child_component_data'] ) ) {
                return $cart_item_meta;
            }

            if ( is_null( $post_data ) ) {
                $post_data = $_POST;
            }

            $upload_data = array();
            if ( ! empty( $_FILES ) ) {
                $upload_data = $_FILES;
            }

            $type_list = YITH_WAPO_Type::getAllowedGroupTypes( $product_id , null , $sold_individually );

            if ( empty( $cart_item_meta['yith_wapo_options'] ) ) {
                $cart_item_meta['yith_wapo_options'] = array();
            }

            if ( is_array( $type_list ) && ! empty( $type_list ) ) {

                $product = wc_get_product( $product_id );

                $variation = isset( $post_data['variation_id'] ) ? new WC_Product_Variation( $post_data['variation_id'] ) : null;

                foreach ( $type_list as $single_type ) {

                    $post_name = 'ywapo_'.$single_type->type.'_'.$single_type->id;

                    $value = isset( $post_data[ $post_name ] ) ? $post_data[ $post_name ] : '';

                    $upload_value = isset( $upload_data[ $post_name ] ) ? $upload_data[ $post_name ] : '';

                    if( $value == '' && empty( $upload_value ) ) {
                        continue;
                    }
                    else if ( is_array( $value ) ) {
                        $value = array_map( 'stripslashes', $value );
                    } else {
                        $value = stripslashes( $value );
                    }

                    $data = YITH_WAPO_Type::getCartDataByPostValue( $this , $product , $variation, $single_type , $value , $upload_value );

                    if ( is_wp_error( $data ) ) {

                        // Throw exception for add_to_cart to pickup
                        throw new Exception( $data->get_error_message() );

                    } elseif ( $data ) {
                        $cart_item_meta['yith_wapo_options'] = array_merge( $cart_item_meta['yith_wapo_options'], apply_filters( 'yith_wapo_cart_item_data', $data, $single_type, $product_id, $post_data  ) );
                    }
                }

                if( !isset( $cart_item_meta['yith_wapo_sold_individually'] ) ) {
                    $cart_item_meta['yith_wapo_sold_individually'] = $sold_individually;
                }

                if( $cart_item_key_parent !== 0 && !empty( $cart_item_key_parent ) ) {
                    $cart_item_meta['yith_wapo_cart_item_key_parent'] = $cart_item_key_parent;
                }

            }

            return $cart_item_meta;
        }

        /**
         * @param $product_quantity
         * @param $cart_item_key
         * @param $cart_item
         *
         * @return string
         */
        public function woocommerce_cart_item_quantity ( $product_quantity, $cart_item_key, $cart_item = null ) {

            if ( isset( $cart_item['yith_wapo_options'] ) ) {

                $add_ons = $cart_item['yith_wapo_options'] ;

                foreach ( $add_ons as $add_on ) {

                   if( isset( $add_on['calculate_quantity_sum'] ) && $add_on['calculate_quantity_sum'] ) {
                        if( $add_on['value'] > 0 ) {
                            return woocommerce_quantity_input( array(
                                'input_name'  => "cart[{$cart_item_key}][qty]",
                                'input_value' => $cart_item['quantity'],
                                'max_value'   => $cart_item['quantity'],
                                'min_value'   => $cart_item['quantity']
                            ), $cart_item['data'], false );
                        }
                   }

                }

            }

            return $product_quantity;
        }


        /**
         * @param $cart_item_data
         * @param $item
         * @param $order
         *
         * @author Andrea Frascaspata
         * @return mixed
         */
        public function re_add_cart_item_data( $cart_item_data, $item, $order ) {

            // Disable validation
            remove_filter( 'woocommerce_add_to_cart_validation', array( $this, 'add_to_cart_validation' ), 50, 6 );

            $stored_meta_data = null;

            $extra_info = isset( $item['item_meta']['_ywapo_extra_info'][0] ) ?  maybe_unserialize ( $item['item_meta']['_ywapo_extra_info'][0] ) : array() ;

            if ( isset( $item['item_meta']['_ywapo_meta_data'][0] ) ) {

                $stored_meta_data = maybe_unserialize( $item['item_meta']['_ywapo_meta_data'][0] );

            }
            else if ( isset( $item['item_meta']['_ywraq_wc_ywapo'][0] ) ) { // order by request a quote

                 $stored_meta_data = maybe_unserialize( $item['item_meta']['_ywraq_wc_ywapo'][0] );

            }

            $cart_item_meta = array();

            if( isset( $stored_meta_data ) ) {

                foreach ( $stored_meta_data  as $key => $single_data ) {

                    $type_object = new YITH_WAPO_Type( $single_data['type_id'] );

                    if( is_object( $type_object ) ) {

                        $product = wc_get_product( $item['item_meta']['_product_id'][0] );

                        $variation = isset( $item['item_meta']['_variation_id'][0] ) && $item['item_meta']['_variation_id'][0] > 0  ? new WC_Product_Variation( $item['item_meta']['_variation_id'][0] ) : null;

                        $new_single_data = YITH_WAPO_Type::getCartDataByPostValue( $this, $product , $variation , $type_object, $single_data['original_value'] , array() );

                        $index = isset( $single_data['original_index'] ) ? $single_data['original_index'] : 0;
                        if( isset( $new_single_data[$index] ) ) {
                            $new_single_data = $new_single_data[$index];
                        }

                        if ( empty( $single_data ) || !isset( $new_single_data['name'] ) || ( $new_single_data['name'] != $single_data['name'] ) ) {
                            unset( $single_data[$key] );
                        }
                        else {
                            $stored_meta_data[$key] = $new_single_data;
                        }

                    }

                }

                $cart_item_meta['yith_wapo_options'] =  apply_filters( 'yith_wapo_re_add_cart_item_data', $stored_meta_data, $item );

                $cart_item_meta['yith_wapo_sold_individually'] = isset( $extra_info['yith_wapo_sold_individually'] ) && $extra_info['yith_wapo_sold_individually'] ;
                
            }

            return $cart_item_meta;
        }


        /**
         * @param $cart_item
         *
         * @author Andrea Frascaspata
         * @return mixed
         */
        public function add_cart_item( $cart_item ) {

            if( isset( $cart_item['yith_wcp_child_component_data'] ) ) {
                return $cart_item;
            }

            // Adjust price if addons are set
            $this->cart_adjust_price( $cart_item );

            return $cart_item;
        }

        /**
         * @param $other_data
         * @param $cart_item
         *
         * @author Andrea Frascaspata
         * @return array
         */
        public function get_item_data( $other_data, $cart_item ) {

            if ( ! empty( $cart_item['yith_wapo_options'] ) ) {

                $product_parent_id = yit_get_base_product_id( $cart_item['data']);
                
                if( isset( $cart_item['variation_id'] ) && $cart_item['variation_id'] > 0 ) {
                    $base_product = new WC_Product_Variation( $cart_item['variation_id'] );
                } else {
                    $base_product = wc_get_product( $product_parent_id );
                }


                if( ( is_object( $base_product ) && get_option( 'yith_wapo_settings_show_product_price_cart' ) == 'yes' ) && ( isset( $cart_item['yith_wapo_sold_individually'] ) && ! $cart_item['yith_wapo_sold_individually'] ) ) {

                    if( $base_product->get_type() == 'yith_bundle' && $base_product->per_items_pricing == true && function_exists('YITH_WCPB_Frontend_Premium') && method_exists( YITH_WCPB_Frontend_Premium() , 'format_product_subtotal' ) ) {
                        $price = YITH_WCPB_Frontend_Premium()->calculate_bundled_items_price_by_cart( $cart_item );
                    } else {
                        $price = yit_get_display_price( $base_product ); 
                    }

                    $price_html = wc_price( $price );

                    $other_data[] = array(
                        'name'    => __( 'Base price' , 'yith-woocommerce-product-add-ons' ) ,
                        'value'   => $price_html,
                    );

                }

                $type_list = $this->getCartWapoOptions( $cart_item , 'all' );

                foreach ( $type_list as $single_type_options ) {

                    $name = $single_type_options['name'] ;
                    $value = $single_type_options['value'];
                    $price = $single_type_options['price'];

                    if ( YITH_WAPO::$is_wpml_installed ) {

                        $name = YITH_WAPO_WPML::string_translate( $name );

                        if ( isset( $single_type_options['add_on_type'] ) ) {

                            switch ( $single_type_options['add_on_type'] ) {
                                case 'checkbox':
                                case 'labels':
                                case 'multiple_labels':
                                case 'select':
                                case 'radio':
                                    $value = YITH_WAPO_WPML::string_translate( $value );
                                    break;
                            }

                        }

                    }

                    if ( $price >= 0 ) {

                        if( $price > 0 && ! $this->hide_price( $product_parent_id ) ) {
                            $name .= ' (' . wc_price( $price ) . ')';
                        }

                        if( ! isset( $single_type_options['uploaded_file'] ) ) {
                            $value = esc_html( strip_tags( $value ) );
                        }

                        $other_data[] = array(
                            'name'    => $name,
                            'value'   => $value,
                        );

                    }
                }
            }

            return $other_data;
        }

        /**
         * @param $cart_item
         * @param $values
         *
         * @author Andrea Frascaspata
         * @return mixed
         */
        public function get_cart_item_from_session( $cart_item, $values ) {

            if ( ! empty( $values['yith_wapo_options'] ) ) {

                $cart_item['yith_wapo_options'] = $values['yith_wapo_options'];
                $cart_item = $this->add_cart_item( $cart_item );

            }
            return $cart_item;
        }

        /**
         * @param $item_id
         *
         * @author Andrea Frascaspata
         * @param $values
         */
        public function order_item_meta( $item_id, $values ) {

            if ( ! empty( $values['yith_wapo_options'] ) ) {

                $type_list = $this->getCartWapoOptions( $values , 'all' );


                foreach ( $type_list as $single_type_options ) {


                        if( $single_type_options['price'] >= 0 ) {

                            $name = '<span id="'.$single_type_options['type_id'].'">'.$single_type_options['name'].'</span>';

                            if( $single_type_options['price'] > 0 ) {
                                $name .= ' (' . wc_price( $single_type_options['price'] ) . ')';

                                if( isset( $values['yith_wapo_sold_individually'] ) && $values['yith_wapo_sold_individually'] ) {
                                    $name .=' '._x( '*Sold invidually' , 'notice on admin order item meta' , 'yith-woocommerce-product-add-ons' );
                                    //@since 1.1.0
                                }

                            }

                            wc_add_order_item_meta( $item_id, strip_tags( $name ) , $single_type_options['value'] );

                        }


                    $extra_info['yith_wapo_sold_individually'] = $values['yith_wapo_sold_individually'];

                    wc_add_order_item_meta( $item_id, '_ywapo_meta_data', $values['yith_wapo_options'] );
                    wc_add_order_item_meta( $item_id, '_ywapo_extra_info',  $extra_info );

                 }

            }
        }


        /**
         * @param $formatted_meta
         *
         * @return mixed
         */
        public function order_items_meta_display( $formatted_meta ) {

            if ( YITH_WAPO::$is_wpml_installed ) {

                foreach ( $formatted_meta as $key => &$meta_values ) {

                    $label = &$meta_values['label'];

                    if ( !strstr( $label, '<dl class="variation">' ) ) {

                        // translate label

                        if ( strstr( $label, '(' ) ) {

                            $display = explode( '(', $label );

                            if ( count( $display ) > 1 ) {

                                $name = trim( $display[0] );

                                $translate_name = YITH_WAPO_WPML::string_translate( $name );

                                if ( ! empty( $translate_name ) && $translate_name != $name ) {

                                    $meta_values['label'] =  $translate_name;

                                }

                            }

                        }  else {

                            $name = $label;

                            $translate_name = YITH_WAPO_WPML::string_translate( $name );

                            if ( ! empty( $translate_name ) && $translate_name != $name ) {

                                $meta_values['label'] =  $translate_name;

                            }

                        }

                        // translate value

                        $value = &$meta_values['value'];

                        $translate_value = YITH_WAPO_WPML::string_translate( $value );

                        if ( ! empty( $translate_value ) && $translate_value != $value ) {

                            $meta_values['value'] =  $translate_value;

                        }

                    }

                }

            }

            return $formatted_meta;
        }

        /**
         * @param $cart_item_key
         * @param $cart
         * @author Andrea Frascaspata
         */
        public function cart_item_restored( $cart_item_key, $cart ) {

            if( isset( $cart->cart_contents[$cart_item_key] ) ) {

                $cart_item = $cart->cart_contents[$cart_item_key];

                $this->cart_adjust_price( $cart_item ) ;

                // search for sold individually item

                $removed_cart_contents = $cart->removed_cart_contents;

                if( isset( $_REQUEST['undo_item'] ) ) {

                    remove_action( 'woocommerce_cart_item_restored' , array( $this, 'cart_item_restored' ), 10 );

                    foreach ( $removed_cart_contents as $cart_item_key_removed => $values ) {

                        if( isset( $values['yith_wapo_cart_item_key_parent']  ) && $values['yith_wapo_cart_item_key_parent'] == $cart_item_key && $cart_item_key == $_REQUEST['undo_item'] ) {
                            $cart->restore_cart_item( $cart_item_key_removed );
                        }

                    }

                    add_action( 'woocommerce_cart_item_restored' , array( $this, 'cart_item_restored' ), 10, 2 );

                }

            }

        }

        /**
         * @param $cart_item
         * @author Andrea Frascaspata
         */
        public function cart_adjust_price( $cart_item ) {

            // Adjust price if addons are set
            if ( ! empty( $cart_item['yith_wapo_options'] ) && apply_filters( 'yith_wapo_adjust_price', true, $cart_item ) ) {

                if( isset( $cart_item['yith_wapo_sold_individually'] ) && $cart_item['yith_wapo_sold_individually'] ) {
                    $cart_item['data']->price = 0;
                    $cart_item['data']->manage_stock = 'no';
                    $cart_item['data']->sold_individually = 'yes';
                }

                $types_total_price = $this->get_total_add_ons_price( $cart_item );

                if( defined('YWCRBP_PREMIUM') ){
                   $cart_item['data']->price = $cart_item['data']->get_price()+$types_total_price;

                } else {
                   //$cart_item['data']->adjust_price( $types_total_price );
                    $cart_item['data']->set_price( yit_get_prop( $cart_item['data'] , '_price' , true, 'edit' ) +  $types_total_price  );
                }

                $cart_item['data']->yith_wapo_adjust_price = true;
            }

        }

        /**
         * @param $cart_item
         *
         * @return int
         */
        public function get_total_add_ons_price( $cart_item ) {

            $type_list = $this->getCartWapoOptions( $cart_item , 'all' );

            $types_total_price = $this->get_total_by_add_ons_list($type_list);

            return $types_total_price;

        }

        /**
         * @param $type_list
         *
         * @return int
         */
        private function get_total_by_add_ons_list( $type_list ) {

            $types_total_price = 0;

            foreach ( $type_list as $single_type_option ) {

                if ( isset( $single_type_option['price_original'] ) && $single_type_option['price_original'] >= 0 ) {
                    $types_total_price += $single_type_option['price_original'];
                }

            }

            return $types_total_price;

        }

        /**
         * @param      $product
         * @param      $price
         * @param      $price_type
         * @param bool $use_display
         * @param null $variation
         * @param null $forced_product_price
         *
         * @return float|int|mixed
         */
        public function get_display_price( $product , $price , $price_type , $use_display = true , $variation = null , $forced_product_price = null , $value = null ) {

            // price calculation
            $price_calculated = 0;
            $decimal_separator = wc_get_price_decimal_separator();
            $price = str_replace( $decimal_separator ,'.',$price);

            $product_display_price = yit_get_display_price( $product , $price );

            if( $price > 0 ) {

                switch( $price_type ) {

                    case 'fixed':
                        $price_calculated = $use_display ? $product_display_price : $price;
                        break;
                    case 'percentage':

                        if( $forced_product_price > 0 ) {

                            $product_final_price = $forced_product_price;

                        } else {

                            $product_object = isset( $variation ) ? $variation : $product ;

                            $product_object_price = yit_get_prop( $product_object , '_price' , true , 'edit');

                            $product_price = defined('YWCRBP_PREMIUM') ? $product_object->get_price() : $product_object_price;

                            $product_final_price = ( $use_display ? yit_get_display_price( $product_object ) : $product_price );

                        }

                        $price_calculated = ( $product_final_price  / 100 ) * $price;

                        break;

                    case 'calculated_multiplication':

                        if( isset($value) && is_numeric( $value ) ) {
                            $value = str_replace( $decimal_separator , '.' , $value);
                            $price_calculated =  $price * floatval($value);
                        }

                        break;

                    case 'calculated_character_count':

                        if( isset($value) ) {
                            $price_calculated =  $price * strlen($value);
                        }

                        break;
                }

            }

            return $price_calculated;

        }

        /**
         * @param $param
         * @return mixed
         */
        public function upload_dir( $param ) {

            $path_dir = '/'.$this->_option_upload_folder_name.'/';

            $unique_dir = md5( WC()->session->get_customer_id() );
            $subdir = $path_dir . $unique_dir;

            if ( empty( $param['subdir'] ) ) {
                $param['path']   = $param['path'] . $subdir;
                $param['url']    = $param['url']. $subdir;
                $param['subdir'] = $subdir;
            } else {
                $param['path']   = str_replace( $param['subdir'], $subdir, $param['path'] );
                $param['url']    = str_replace( $param['subdir'], $subdir, $param['url'] );
                $param['subdir'] = str_replace( $param['subdir'], $subdir, $param['subdir'] );
            }
            return $param;

            return ;

        }

        /**
         * @param $depend
         * @return string
         */
        public function checkConditionalOptions( $depend ) {

            global $wpdb;

            $options_list = explode( ',' , $depend );

            foreach ( $options_list as $key => $option_id ) {

                if( strpos( $option_id , 'option_' ) !== false ) {

                    $depend_value_info = explode( '_' , $option_id );

                    if( is_array( $depend_value_info ) && count( $depend_value_info ) == 3 ){

                        $type_id = $depend_value_info[1];
                        $option_index = $depend_value_info[2] ;

                        $result = $wpdb->get_row( "SELECT options FROM {$wpdb->prefix}yith_wapo_types WHERE id='$type_id' and del=0" );

                        if( isset( $result->options ) ) {

                            $options = maybe_unserialize( $result->options );

                            if( isset( $options['label'][ $option_index ] ) ) {
                                $option_data  = $option_index;
                            }

                        }
                    }

                } else {

                    $option_data = $wpdb->get_row( "SELECT id FROM {$wpdb->prefix}yith_wapo_types WHERE id='$option_id' and del=0" );

                }

                if( ! isset( $option_data ) ) {
                    unset( $options_list[ $key ] );
                }

            }

            return implode( ',' , $options_list );

        }


        /**
         * @param $cart_item
         * @param string $type
         * @return array
         */
        public function getCartWapoOptions( $cart_item , $type = 'all' ) {

            $cart_item_filtered =  array();

            if( isset( $cart_item['yith_wapo_options'] ) ) {

                if( isset( $cart_item['yith_wapo_sold_individually'] ) ) {
                    if( $cart_item['yith_wapo_sold_individually'] ) {
                        $type = 'sold_individually';
                    } else {
                        $type = 'simple';
                    }
                }

                foreach ( $cart_item['yith_wapo_options'] as $key => $single_type_option ) {

                    if( $type == 'all' ) {
                        $cart_item_filtered [$key ] = $single_type_option;
                    }
                    else if( $type == 'sold_individually' && isset( $single_type_option['sold_individually'] ) && $single_type_option['sold_individually'] ) {
                        $cart_item_filtered [$key ] = $single_type_option;
                    } else  if( $type == 'simple' && ( ! isset( $single_type_option['sold_individually'] ) || ( isset( $single_type_option['sold_individually'] ) && ! $single_type_option['sold_individually'] ) ) ) {
                        $cart_item_filtered[ $key ] = $single_type_option;
                    }

                }

            }

            return $cart_item_filtered;
        }

        /**
         * @param $cart_item_key
         * @param $product_id
         * @param $quantity
         * @param $variation_id
         * @param $variation
         * @param $cart_item_data
         * @throws Exception
         */
        public function add_to_cart_sold_individually(  $cart_item_key, $product_id, $quantity, $variation_id, $variation, $cart_item_data ) {

                if( $product_id > 0 && isset(  $_REQUEST['add-to-cart'] ) ) {

                    $cart_item_meta = $this->add_cart_item_data( array() , $product_id , $_REQUEST , true , $cart_item_key ) ;
                    if( isset( $cart_item_meta ) && isset( $cart_item_meta['yith_wapo_options'] ) && ! empty( $cart_item_meta['yith_wapo_options'] ) ) {
                        remove_action( 'woocommerce_add_to_cart', array( $this, 'add_to_cart_sold_individually' ) , 10 ) ;
                        remove_filter( 'woocommerce_add_cart_item_data', array( $this, 'add_cart_item_data' ), 10 );
                        //-----------------------------------
                        $res = WC()->cart->add_to_cart( $product_id , 1 , $variation_id , $variation ,  $cart_item_meta );
                        //----------------------------------
                        add_action( 'woocommerce_add_to_cart', array( $this, 'add_to_cart_sold_individually' ) , 10 , 6 ) ;
                        add_filter( 'woocommerce_add_cart_item_data', array( $this, 'add_cart_item_data' ), 10, 2 );
                    }

                }

        }

        /**
         * @param $html
         * @param $cart_item_key
         * @return mixed
         */
        public function hide_remove_link ( $html , $cart_item_key ) {

            $cart = WC()->cart->cart_contents;

            if ( isset( $cart[ $cart_item_key ] ) ) {

               $cart_item_meta = $cart[ $cart_item_key ];

               if( isset( $cart_item_meta['yith_wapo_sold_individually'] ) && $cart_item_meta['yith_wapo_sold_individually'] && isset( $cart_item_meta['yith_wapo_cart_item_key_parent'] ) && $cart_item_meta['yith_wapo_cart_item_key_parent'] !==0 )  {

                  return '';

               }

            }

            return $html;

        }

        /**
         * @param $html
         * @param $cart_item
         * @return string
         */
        public function hide_thumbnail_name ( $html , $cart_item = array() ) {

            if( isset( $cart_item['yith_wapo_sold_individually'] ) && $cart_item['yith_wapo_sold_individually'] ) {

                return '';

            }

            return $html;

        }

        /**
         * @param $cart_item_key
         * @param $cart
         */
        public function remove_items_from_cart( $cart_item_key , $cart )  {

            remove_action( 'woocommerce_cart_item_removed', array( $this, 'remove_items_from_cart' ), 10 );

            foreach( $cart->get_cart() as $cart_item_key_ass => $value ) {

                if( isset( $value['yith_wapo_cart_item_key_parent'] ) && $value['yith_wapo_cart_item_key_parent'] == $cart_item_key ) {


                    WC()->cart->remove_cart_item( $cart_item_key_ass );

                }

            }

            add_action( 'woocommerce_cart_item_removed', array( $this, 'remove_items_from_cart' ), 10 , 2 );

        }

        /**
         * @param $product_id
         *
         * @return bool
         */
        public function hide_price( $product_id ) {

            // Catalog Mode

            if( function_exists('YITH_WCTM') ) {

                $catalog_mode_object =  YITH_WCTM();

                if( method_exists( $catalog_mode_object , 'check_price_hidden' ) ) {
                    return YITH_WCTM()->check_price_hidden( false , $product_id );
                }

            }

            // Request a Quote

            if( function_exists('YITH_Request_Quote_Premium') ) {

                return get_option( 'ywraq_hide_price' ) == 'yes';

            }

            return false;

        }

        /**
         * @param $price_html
         * @param $cart_item
         *
         * @return mixed
         */
        public function ywapo_ywsbs_get_price_html( $price_html , $cart_item ) {

            return $price_html;

            if( isset( $cart_item['yith_wapo_options'] ) ) {

                if( isset( $cart_item['data'] ) && is_object( $cart_item['data'] ) ) {
                    return  $cart_item['data']->get_price_html();
                }

            }

            return $price_html;

        }

        /**
         * @param $subtotal
         * @param $item
         * @param $subscription
         * @param $product
         *
         * @return mixed
         */
        public function ywsbs_order_formatted_line_subtotal( $subtotal , $item , $subscription , $product) {

            if( isset( $item['item_meta'] ) && isset( $item['item_meta']['_ywapo_meta_data'] ) && isset( $item['item_meta']['_line_total'] ) ) {

                if( method_exists( $subscription , 'change_price_html' ) )  {

                    $tax_inc = get_option( 'woocommerce_prices_include_tax' ) === 'yes';

                    if ( wc_tax_enabled() && $tax_inc ) {
                        $subtotal = $item['item_meta']['_line_total'][0] + $item['item_meta']['_line_tax'][0];

                    } else {
                        $subtotal = $item['item_meta']['_line_total'][0];
                    }

                    $subtotal =  $subscription->change_price_html( wc_price( $subtotal ) , $product ) ;

                }

            }

            return $subtotal;

        }

        /**
         * @param $new_cart
         * @param $new_cart_item_key
         * @param $cart_item
         *
         * @return mixed
         */
        public function ywsbs_add_cart_item_data( $new_cart, $new_cart_item_key, $cart_item ){

            return $new_cart;

            if (isset( $new_cart->cart_contents[$new_cart_item_key] )){
                $new_cart->cart_contents[$new_cart_item_key]['data'] = $cart_item['data'];
            }

            return $new_cart;
        }

        /**
         * @param $array_quantity
         * @param $array_opt
         * @param $array_var
         * @param $html
         * @param $price
         */
        public function ywcpb_print_calculated_price( $array_quantity, $array_opt, $array_var, $html, $price ) {
            $price = str_replace( wc_get_price_decimal_separator() ,'.',$price);
            echo '<input type="hidden" name="yith-wcpb-wapo-bundle-product-price" class="yith-wcpb-wapo-bundle-product-price" value="'. esc_html( $price ).'" />';
        }

        /**
         * @param $price
         * @param $bundled_items_price
         * @param $cart_item
         *
         * @return string
         */
        public function ywcpb_woocommerce_cart_item_price ( $price, $bundled_items_price, $cart_item ) {

            if( isset( $cart_item['yith_wapo_options'] ) ) {

                $types_total_price = $this->get_total_add_ons_price( $cart_item );

                if( isset( $cart_item['yith_wapo_sold_individually'] ) && $cart_item['yith_wapo_sold_individually'] ) {
                    $bundled_items_price = 0;
                }

                $price = wc_price( $bundled_items_price + $types_total_price );

            }

            return $price;
        }

        /**
         * @param $subtotal
         * @param $cart_item
         * @param $bundle_price
         *
         * @return mixed
         */
        public function ywcpb_bundle_pip_bundled_items_subtotal( $subtotal, $cart_item, $bundle_price ) {

            if( isset( $cart_item['yith_wapo_options'] ) ) {

                if( method_exists( YITH_WCPB_Frontend_Premium() , 'format_product_subtotal' ) ) {
                    $types_total_price = $this->get_total_add_ons_price( $cart_item );

                    if( isset( $cart_item['yith_wapo_sold_individually'] ) && $cart_item['yith_wapo_sold_individually'] ) {
                        $bundle_price = 0;
                    }

                    $subtotal = YITH_WCPB_Frontend_Premium()->format_product_subtotal( $cart_item[ 'data' ], $bundle_price + $types_total_price );
                }

            }

            return $subtotal;
        }

        /**
         * @param $product_sub_total
         * @param $item
         * @param $order
         *
         * @return string
         */
        public function order_item_subtotal( $product_sub_total , $item , $order ) {

            if ( isset( $item['item_meta']['_ywapo_meta_data'][0] ) && isset( $item['item_meta']['_bundled_items'][0] ) ) {

                $type_list = maybe_unserialize( $item['item_meta']['_ywapo_meta_data'][0] );

                $types_total_price = $this->get_total_by_add_ons_list($type_list);

                $tax_display = $order->tax_display_cart;

                if ( 'excl' == $tax_display ) {
                    $ex_tax_label = $order->prices_include_tax ? 1 : 0;
                    $product_sub_total = wc_price( $order->get_line_subtotal( $item ) + $types_total_price, array( 'ex_tax_label' => $ex_tax_label, 'currency' => $order->get_order_currency() ) );
                } else {
                    $product_sub_total = wc_price( $order->get_line_subtotal( $item, true ) + $types_total_price, array('currency' => $order->get_order_currency() ) );
                }
            }

          return $product_sub_total;
        }


        /**
         * @param $add_items
         * @param $cart_item
         * @return bool
         */
        public function exclude_sold_individually( $add_items , $cart_item ) {

            if( isset( $cart_item['yith_wapo_sold_individually'] ) && $cart_item['yith_wapo_sold_individually'] ) {
                $add_items = false;
            }

            return $add_items;
        }


    }

}
