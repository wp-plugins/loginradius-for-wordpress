<?php

//if uninstall not called from WordPress, exit
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
    exit();
}

if( !is_multisite()) {
    $loginRadiusSettings = get_option( 'LoginRadius_settings' );
    if ( $loginRadiusSettings['delete_options'] == 1 ) {
        delete_loginradius_options();
    }
} else {
    global $wpdb;
    $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
    $original_blog_id = get_current_blog_id();
    foreach ( $blog_ids as $blog_id ) {
        switch_to_blog( $blog_id );
        $loginRadiusSettings = get_option( 'LoginRadius_settings' );
        if ( $loginRadiusSettings['delete_options'] == 1 ) {
            delete_loginradius_options();
        }
    }
    switch_to_blog( $original_blog_id );
}   

function delete_loginradius_options() {
    global $wpdb;
    delete_option( 'LoginRadius_settings' );
    delete_option( 'loginradius_db_version' );
    $wpdb->query( "delete from $wpdb->usermeta where meta_key like 'loginradius%'" );
}