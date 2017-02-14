<?php

add_action('admin_menu', 'ndla_plugin_menu');


function ndla_plugin_menu()
{
    //add_options_page( 'My Plugin Options', 'My Plugin', 'manage_options', 'my-unique-identifier', 'my_plugin_options' );

    add_menu_page('NDLA Settings', 'NDLA', 'manage_options', 'ndla-settings', 'ndla_settings_page');
    //add_submenu_page( 'ndla-settings', 'NDLA API', 'NDLA API', 'manage_options', 'ndla-api-settings', 'ndla_plugin_options');
}

function ndla_settings_page()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    // variables for the field and option names
    $opt_name = 'ndla_api_url';
    $hidden_field_name = 'ndla_submit_hidden';
    $data_field_name = 'ndla_api_url';

    // Read in existing option value from database
    $opt_val = get_option($opt_name);

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if (isset($_POST[$hidden_field_name]) && $_POST[$hidden_field_name] == 'Y') {
        // Read their posted value
        $opt_val = $_POST[$data_field_name];

        // Save the posted value in the database
        update_option($opt_name, $opt_val);

        // Put a "settings saved" message on the screen
        ?>
        <div class="updated"><p><strong><?php _e('Settings saved.', 'ndla'); ?></strong></p></div>
        <?php

    }

    // Now display the settings editing screen

    echo '<div class="wrap">';

    // header

    echo "<h2>" . __('NDLA Plugin Settings', 'ndla') . "</h2>";

    // settings form

    ?>

    <form name="form1" method="post" action="">
        <input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

        <p><?php _e("NDLA API base URL:", 'ndla'); ?>
            <input type="text" name="<?php echo $data_field_name; ?>" value="<?php echo $opt_val; ?>" size="30">
            <em><?php _e('Include trailing slash', 'ndla')?></em>
        </p>
        <hr/>

        <p class="submit">
            <input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>"/>
        </p>

    </form>
    </div>

    <?php

}

