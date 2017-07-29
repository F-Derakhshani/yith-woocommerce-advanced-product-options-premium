<?php

/**
 * Database Version Update
 */

//Add support to YITH Product Add-Ons db version 1.0.1
function yith_wapo_update_db_1_0_1() {
    $wapo_db_option = get_option( 'yith_wapo_db_version', '1.0.0' );
    if ( isset( $wapo_db_option ) && version_compare( $wapo_db_option, '1.0.1', '<' ) ) {
        global $wpdb;

        $sql = "ALTER TABLE `{$wpdb->prefix}yith_wapo_types` ADD `sold_individually` BOOLEAN DEFAULT 0";
        $wpdb->query( $sql );

        update_option( 'yith_wapo_db_version', '1.0.1' );
    }
}

function yith_wapo_update_db_1_0_2() {
    $wapo_db_option = get_option( 'yith_wapo_db_version', '1.0.0' );
    if ( isset( $wapo_db_option ) && version_compare( $wapo_db_option, '1.0.2', '<' ) ) {
        global $wpdb;

        $sql = "ALTER TABLE `{$wpdb->prefix}yith_wapo_types` ADD `max_item_selected` INT DEFAULT 0";
        $wpdb->query( $sql );

        update_option( 'yith_wapo_db_version', '1.0.2' );
    }
}

function yith_wapo_update_db_1_0_3() {
    $wapo_db_option = get_option( 'yith_wapo_db_version', '1.0.0' );
    if ( isset( $wapo_db_option ) && version_compare( $wapo_db_option, '1.0.3', '<' ) ) {
        global $wpdb;

        $sql = "ALTER TABLE `{$wpdb->prefix}yith_wapo_types` ADD `depend_variations` VARCHAR(250) DEFAULT ''";
        $wpdb->query( $sql );

        $sql = "ALTER TABLE `{$wpdb->prefix}yith_wapo_groups` ADD `products_exclude_id` VARCHAR(250) DEFAULT ''";
        $wpdb->query( $sql );

        update_option( 'yith_wapo_db_version', '1.0.3' );
    }
}

function yith_wapo_update_db_1_0_4() {
    $wapo_db_option = get_option( 'yith_wapo_db_version', '1.0.0' );
    if ( isset( $wapo_db_option ) && version_compare( $wapo_db_option, '1.0.4', '<' ) ) {
        global $wpdb;

        $sql = "ALTER TABLE `{$wpdb->prefix}yith_wapo_types` ADD `change_featured_image` BOOLEAN DEFAULT 0";
        $wpdb->query( $sql );

        update_option( 'yith_wapo_db_version', '1.0.4' );
    }
}

function yith_wapo_update_db_1_0_5() {
    $wapo_db_option = get_option( 'yith_wapo_db_version', '1.0.0' );
    if ( isset( $wapo_db_option ) && version_compare( $wapo_db_option, '1.0.5', '<' ) ) {
        global $wpdb;

        $sql = "ALTER TABLE `{$wpdb->prefix}yith_wapo_types` ADD `calculate_quantity_sum` BOOLEAN DEFAULT 0";
        $wpdb->query( $sql );

        update_option( 'yith_wapo_db_version', '1.0.5' );
    }
}

function yith_wapo_update_db_1_0_6() {
    $wapo_db_option = get_option( 'yith_wapo_db_version', '1.0.0' );
    if ( isset( $wapo_db_option ) && version_compare( $wapo_db_option, '1.0.6', '<' ) ) {
        global $wpdb;

        $sql = "ALTER TABLE `{$wpdb->prefix}yith_wapo_types` ADD `required_all_options` TINYINT(1) NOT NULL DEFAULT '1'";
        $wpdb->query( $sql );

        update_option( 'yith_wapo_db_version', '1.0.6' );
    }
}

function yith_wapo_update_db_1_0_7() {
    $wapo_db_option = get_option( 'yith_wapo_db_version', '1.0.0' );
    if ( isset( $wapo_db_option ) && version_compare( $wapo_db_option, '1.0.7', '<' ) ) {
        global $wpdb;

        $sql = "ALTER TABLE `{$wpdb->prefix}yith_wapo_types` ADD `max_input_values_amount` INT DEFAULT 0";
        $wpdb->query( $sql );

        update_option( 'yith_wapo_db_version', '1.0.7' );
    }
}

function yith_wapo_update_db_1_0_8() {
    $wapo_db_option = get_option( 'yith_wapo_db_version', '1.0.0' );
    if ( isset( $wapo_db_option ) && version_compare( $wapo_db_option, '1.0.8', '<' ) ) {
        global $wpdb;

        $sql = "ALTER TABLE `{$wpdb->prefix}yith_wapo_types` ADD `min_input_values_amount` INT DEFAULT 0";
        $wpdb->query( $sql );

        update_option( 'yith_wapo_db_version', '1.0.8' );
    }
}

add_action( 'admin_init', 'yith_wapo_update_db_1_0_1' );
add_action( 'admin_init', 'yith_wapo_update_db_1_0_2' );
add_action( 'admin_init', 'yith_wapo_update_db_1_0_3' );
add_action( 'admin_init', 'yith_wapo_update_db_1_0_4' );
add_action( 'admin_init', 'yith_wapo_update_db_1_0_5' );
add_action( 'admin_init', 'yith_wapo_update_db_1_0_6' );
add_action( 'admin_init', 'yith_wapo_update_db_1_0_7' );
add_action( 'admin_init', 'yith_wapo_update_db_1_0_8' );