<?php
/**
 * Plugin Name: YITH WooCommerce Product Add-Ons Premium
 * Plugin URI: http://yithemes.com/
 * Description: YITH WooCommerce Product Add-Ons Premium.
 * Version: 1.2.6
 * Author: YITHEMES
 * Author URI: http://yithemes.com/
 * Text Domain: yith-woocommerce-product-add-ons
 * Domain Path: /languages/
 *
 * @author  YITHEMES
 * @package YITH WooCommerce Product Add-Ons
 * @version 1.2.6
 */
/*  Copyright 2016  Your Inspiration Themes  (email : plugins@yithemes.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if ( ! function_exists( 'yith_wapo_install_premium_woocommerce_admin_notice' ) ) {
    /**
     * Print an admin notice if woocommerce is deactivated
     *
     * @author Andrea Grillo <andrea.grillo@yithemes.com>
     * @since  1.0
     * @return void
     * @use admin_notices hooks
     */
    function yith_wapo_install_premium_woocommerce_admin_notice() {
        ?>
        <div class="error">
            <p><?php _e( 'YITH WooCommerce Product Add-Ons Premium is enabled but not effective. It requires WooCommerce in order to work.', 'yith-woocommerce-product-add-ons' ); //@since 1.1.0 ?></p>
        </div>
    <?php
    }
}

if ( ! function_exists( 'yith_plugin_registration_hook' ) ) {
    require_once 'plugin-fw/yit-plugin-registration-hook.php';
}
register_activation_hook( __FILE__, 'yith_plugin_registration_hook' );

// Free version deactivation if installed __________________

if ( ! function_exists( 'yit_deactive_free_version' ) ) {
    require_once 'plugin-fw/yit-deactive-plugin.php';
}
yit_deactive_free_version( 'YITH_WCCL_FREE_INIT', plugin_basename( __FILE__ ) );
yit_deactive_free_version( 'YITH_WAPO_FREE_INIT', plugin_basename( __FILE__ ) );

/* Advanced Option Constant */
! defined( 'YITH_WAPO' ) && define( 'YITH_WAPO', true );
! defined( 'YITH_WAPO_FILE' ) && define( 'YITH_WAPO_FILE', __FILE__ );
! defined( 'YITH_WAPO_URL' ) && define( 'YITH_WAPO_URL', plugin_dir_url( __FILE__ ) );
! defined( 'YITH_WAPO_DIR' ) && define( 'YITH_WAPO_DIR', plugin_dir_path( __FILE__ ) );
! defined( 'YITH_WAPO_TEMPLATE_PATH' ) && define( 'YITH_WAPO_TEMPLATE_PATH', YITH_WAPO_DIR . 'templates' );
! defined( 'YITH_WAPO_TEMPLATE_ADMIN_PATH' ) && define( 'YITH_WAPO_TEMPLATE_ADMIN_PATH', YITH_WAPO_TEMPLATE_PATH . '/yith_wapo/admin/' );
! defined( 'YITH_WAPO_TEMPLATE_FRONTEND_PATH' ) && define( 'YITH_WAPO_TEMPLATE_FRONTEND_PATH', YITH_WAPO_TEMPLATE_PATH . '/yith_wapo/frontend/' );
! defined( 'YITH_WAPO_ASSETS_URL' ) && define( 'YITH_WAPO_ASSETS_URL', YITH_WAPO_URL . 'assets' );
! defined( 'YITH_WAPO_VERSION' ) && define( 'YITH_WAPO_VERSION', '1.2.6' );
! defined( 'YITH_WAPO_DB_VERSION' ) && define( 'YITH_WAPO_DB_VERSION', '1.0.8' );
! defined( 'YITH_WAPO_PREMIUM' ) && define( 'YITH_WAPO_PREMIUM', true );
! defined( 'YITH_WAPO_FILE' ) && define( 'YITH_WAPO_FILE', __FILE__ );
! defined( 'YITH_WAPO_SLUG' ) && define( 'YITH_WAPO_SLUG', 'yith-woocommerce-advanced-product-options' );
! defined( 'YITH_WAPO_LOCALIZE_SLUG' ) && define( 'YITH_WAPO_LOCALIZE_SLUG', 'yith-woocommerce-product-add-ons' );
! defined( 'YITH_WAPO_SECRET_KEY' ) && define( 'YITH_WAPO_SECRET_KEY', 'yCVBJvwjwXe2Z9vlqoWo' );
! defined( 'YITH_WAPO_INIT' ) && define( 'YITH_WAPO_INIT', plugin_basename( __FILE__ ) );
! defined( 'YITH_WAPO_WPML_CONTEXT' ) && define( 'YITH_WAPO_WPML_CONTEXT', 'YITH WooCommerce Product Add-Ons' );

if ( ! defined( 'YITH_WCCL_DB_VERSION' ) ) {
    define( 'YITH_WCCL_DB_VERSION', '1.0.0' );
}

/* Plugin Framework Version Check */
if ( ! function_exists( 'yit_maybe_plugin_fw_loader' ) && file_exists( YITH_WAPO_DIR . 'plugin-fw/init.php' ) ) {
    require_once( YITH_WAPO_DIR . 'plugin-fw/init.php' );
}
yit_maybe_plugin_fw_loader( YITH_WAPO_DIR );

if ( ! function_exists( 'YITH_WAPO' ) ) {
    /**
     * Unique access to instance of YITH_Vendors class
     *
     * @return YITH_WAPO
     * @since 1.0.0
     */
    function YITH_WAPO() {
        // Load required classes and functions
        require_once( YITH_WAPO_DIR . 'includes/class.yith-wapo.php' );

        return YITH_WAPO::instance();
    }
}

// activate plugin
if( ! function_exists( 'yith_wapo_wccl_activation_process' ) ) {

    function yith_wapo_wccl_activation_process(){
        if ( ! function_exists( 'yith_wccl_activation' ) ) {
            require_once 'includes/function.yith-wccl-activation.php';
        }

        yith_wccl_activation();
    }

    register_activation_hook( __FILE__, 'yith_wapo_wccl_activation_process' );

}

if( ! function_exists( 'yith_wapo_wccl_deactivation_process' ) ) {

    // deactivate plugin
    function yith_wapo_wccl_deactivation_process(){
        if ( ! function_exists( 'yith_wccl_deactivation' ) ) {
            require_once 'includes/function.yith-wccl-activation.php';
        }

        yith_wccl_deactivation();
    }

    register_deactivation_hook( __FILE__, 'yith_wapo_wccl_deactivation_process' );

}


/**
 * Require core files
 *
 * @author Andrea Frascaspata <andrea.frascaspata@yithemes.com>
 * @since  1.0
 * @return void
 * @use Load plugin core
 */
function yith_wapo_premium_init() {
     
    load_plugin_textdomain( YITH_WAPO_LOCALIZE_SLUG, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

    require_once( 'includes/function.yith-wapo-update.php' );

    if ( ! function_exists( 'YITH_WCCL' ) ) {
        // Load required classes and functions
        require_once('includes/function.yith-wccl.php');
        require_once('includes/class.yith-wccl.php');
        require_once('includes/class.yith-wccl-admin.php');
        require_once('includes/class.yith-wccl-frontend.php');

        // Let's start the game!
        YITH_WCCL();
        ! defined( 'YITH_WAPO_WCCL' ) && define( 'YITH_WAPO_WCCL', true );
    }

    YITH_WAPO();

}

add_action( 'yith_wapo_premium_init', 'yith_wapo_premium_init' );

function yith_wapo_premium_install() {

    require_once( 'includes/class.yith-wapo-group.php' );
    require_once( 'includes/class.yith-wapo-settings.php' );
    require_once( 'includes/class.yith-wapo-type.php' );
    require_once( 'includes/class.yith-wapo-option.php' );

    if ( ! function_exists( 'WC' ) ) {
        add_action( 'admin_notices', 'yith_wapo_install_premium_woocommerce_admin_notice' );
    }
    else {
        do_action( 'yith_wapo_premium_init' );
    }

    // check for update table
    if ( function_exists( 'yith_wccl_update_db_check' ) ) {
        yith_wccl_update_db_check();
    }

}

add_action( 'plugins_loaded', 'yith_wapo_premium_install', 12 );