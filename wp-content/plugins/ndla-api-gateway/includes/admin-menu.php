<?php
/** Step 2 (from text above). */
add_action( 'admin_menu', 'ndla_plugin_menu' );

/** Step 1. */
function ndla_plugin_menu() {
	//add_options_page( 'My Plugin Options', 'My Plugin', 'manage_options', 'my-unique-identifier', 'my_plugin_options' );

	add_menu_page( 'NDLA Settings', 'NDLA', 'manage_options', 'ndla-settings', 'ndla_plugin_options', 'dashicons-chart-pie', null );
}

/** Step 3. */
function ndla_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	echo '<div class="wrap">';
	echo '<p>Here is where the form would go if I actually had options.</p>';
	echo '</div>';
}
?>