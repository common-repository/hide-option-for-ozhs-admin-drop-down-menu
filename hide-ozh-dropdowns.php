<?php
/*
Plugin Name: Hide Option for Ozh's Admin Drop Down Menu
Plugin URI: http://www.beforesite.com
Description: Gives your users the ability to turn off Ozh' Admin Drop Down Menu
Version: 0.3.1
Author: Greenweb
Author URI: http://www.beforesite.com 
*/
/**
 * Copyright (c) 2012 Greenville Web. All rights reserved.
 *
 * Released under the GPL license
 * http://www.opensource.org/licenses/gpl-license.php
 *
 * This is an add-on for WordPress
 * http://wordpress.org/
 *
 * **********************************************************************
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * **********************************************************************
 */
add_action( 'admin_init', 'ndp_hide_ozh_adminmenu_init');

function ndp_hide_ozh_adminmenu_init(){
	if (!is_plugin_active("ozh-admin-drop-down-menu/wp_ozh_adminmenu.php")) {
		return;
	}
	$remove_ozh = get_the_author_meta( "ndp_hide_ozh", get_current_user_id() );
	if ( is_admin() AND $remove_ozh == 'true' ){
		if(has_action( 'admin_init', 'wp_ozh_adminmenu_init'))	remove_action('admin_init', 'wp_ozh_adminmenu_init', -1000);	
		if(has_action( 'admin_menu', 'wp_ozh_adminmenu_add_page' ))	remove_action('admin_menu', 'wp_ozh_adminmenu_add_page', -999); 
		if(has_action( 'admin_head', 'wp_ozh_adminmenu_head' ))	remove_action('admin_head', 'wp_ozh_adminmenu_head', 999); 
		if(has_action('in_admin_footer', 'wp_ozh_adminmenu_footer'))	remove_action('in_admin_footer', 'wp_ozh_adminmenu_footer'); 
		if(has_filter( 'plugin_action_links_'.'ozh-admin-drop-down-menu/wp_ozh_adminmenu.php', 'wp_ozh_adminmenu_plugin_actions', -10 ))
			remove_filter('plugin_action_links_'.'ozh-admin-drop-down-menu/wp_ozh_adminmenu.php', 'wp_ozh_adminmenu_plugin_actions', -10); 
		if(has_filter( 'ozh_adminmenu_icon_ozh_admin_menu', 'wp_ozh_adminmenu_customicon' ))
			remove_filter('ozh_adminmenu_icon_ozh_admin_menu', 'wp_ozh_adminmenu_customicon'); 
			remove_filter('in_admin_header', 'wp_ozh_adminmenu', -9999); 
	}
	add_action( 'show_user_profile', 'ndp_hide_ozh_profile_fields' );
	add_action( 'edit_user_profile', 'ndp_hide_ozh_profile_fields' );
	add_action( 'personal_options_update', 	'ndp_save_ozh_profile_fields' );
	add_action( 'edit_user_profile_update', 'ndp_save_ozh_profile_fields' );
}
//Hide Option for Ozh' Admin Drop Down Menu
function ndp_hide_ozh_profile_fields( $user ) { 
	?>
	<h3><?php _e("Turn off Ozh's Admin Drop Down Menu"); ?></h3>
	<table class="form-table">
		<tr>
			<th>
				<label for="ndp_hide_ozh "><?php _e("Restore the WordPress menus:"); ?></label>
			</th>
			<td>
				<input type="checkbox" name="ndp_hide_ozh" id=" ndp_hide_ozh " value="true" <?php if (esc_attr( get_the_author_meta( "ndp_hide_ozh", $user->ID )) == "true") echo "checked"; ?> >
			</td>
		</tr>
	</table>
<?php 
}

function ndp_save_ozh_profile_fields( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) )		return false;
	if (isset($_POST['ndp_hide_ozh'])) {
	  update_user_meta( $user_id, 'ndp_hide_ozh', $_POST['ndp_hide_ozh']);
	}else{
	  update_user_meta( $user_id, 'ndp_hide_ozh', '');
	}
	
}