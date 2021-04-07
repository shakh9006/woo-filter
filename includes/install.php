<?php
function wf_plugin_activation()
{
    wf_plugin_create_table();
    if ( empty(get_option('wf_installed')) ){
        add_option( 'wf_installed',  date( 'Y-m-d h:i:s' ) );
    }
}

function wf_plugin_deactivation()
{

}

function wf_plugin_uninstall()
{

}

function wf_plugin_create_table() {
    global $wpdb;
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    $charset_collate = $wpdb->get_charset_collate();
    $table_name_attribute = $wpdb->prefix . 'wf_field';

    $sql = "CREATE TABLE $table_name_attribute (
		id int(11) NOT NULL AUTO_INCREMENT,
		title varchar(100) NOT NULL,
		name varchar(100) NOT NULL,
		type varchar(100) NOT NULL,
		tag varchar(100) NOT NULL,
		description varchar(255) NOT NULL,
		label_toggle varchar(100) NOT NULL,
		PRIMARY KEY  (id),
		KEY `wf_field_name_index` (`name`)
	) $charset_collate;";
    maybe_create_table($table_name_attribute, $sql);

    $table_name_listing_attribute_relationships = $wpdb->prefix . 'wf_filter_field_relationships';
    $sql = "CREATE TABLE $table_name_listing_attribute_relationships (
		id bigint(20) NOT NULL AUTO_INCREMENT,      
		filter_id bigint(20) unsigned NOT NULL,
		field_id int(11) NOT NULL,
		PRIMARY KEY  (id),         
		KEY `wf_filter_field_relationships_filter_id_index` (`filter_id`),
		KEY `wf_filter_field_relationships_field_id_index` (`field_id`),
		CONSTRAINT `" .$wpdb->prefix. "wf_filter_field_relationships_filter_id_foreign` FOREIGN KEY (`filter_id`) REFERENCES {$wpdb->prefix}posts (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
		CONSTRAINT `" .$wpdb->prefix. "wf_filter_field_relationships_field_id_foreign` FOREIGN KEY (`field_id`) REFERENCES {$table_name_attribute}  (`id`) ON DELETE CASCADE ON UPDATE CASCADE
	
	) $charset_collate;";
    maybe_create_table($table_name_listing_attribute_relationships, $sql);
}