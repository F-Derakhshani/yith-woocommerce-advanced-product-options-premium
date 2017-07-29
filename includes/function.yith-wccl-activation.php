<?php
/**
 * Activate/Deactivate function
 *
 * @author Yithemes
 * @package YITH WooCommerce Color and Label Variations Premium
 * @version 1.0.0
 */
if ( ! defined( 'YITH_WAPO' ) ) {
	exit;
} // Exit if accessed directly

if( ! function_exists( 'yith_wccl_activation' ) )  {

	/**
	 * Function triggered on activation for create table on db
	 *
	 * @author Francesco Licandro
	 */
	function yith_wccl_activation() {
		global $wpdb;

		$installed_ver = get_option( "yith_wccl_db_version" );

		if ( $installed_ver != YITH_WCCL_DB_VERSION ) {

			$table_name = $wpdb->prefix . 'yith_wccl_meta';

			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE $table_name (
		meta_id bigint(20) NOT NULL AUTO_INCREMENT,
		wc_attribute_tax_id bigint(20) NOT NULL,
		meta_key varchar(255) DEFAULT '',
		meta_value longtext DEFAULT '',
		PRIMARY KEY (meta_id)
		) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

			add_option( 'yith_wccl_db_version', YITH_WCCL_DB_VERSION );
		}
	}

}

if( ! function_exists( 'yith_wccl_deactivation' ) )  {

	/**
	 * Function triggered on deactivation for create table on db
	 *
	 * @author Francesco Licandro
	 */
	function yith_wccl_deactivation() {

		if( ! is_admin() || ! isset( $_REQUEST['action'] ) || $_REQUEST['action'] != 'deactivate' ) {
			return;
		}

		global $wpdb;

		//change to standard select type custom attributes
		$update = "UPDATE {$wpdb->prefix}woocommerce_attribute_taxonomies SET attribute_type = 'select' WHERE attribute_type NOT LIKE 'text'";
		$wpdb->query( $update );
	}

}