<?php
/*
Plugin Name: NDLA: Produksjonssystem
Plugin URI: https://github.com/NDLANO/production-system
Description: Base plugin for NDLA production system.
Version: 1.0.0
Author: NDLA/Bouvet
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Text Domain: ndla-prodsys
Domain Path: /lang
*/

defined('ABSPATH') or die('No script kiddies please!');


require_once __DIR__ . '/includes/post_type.php';
require_once __DIR__ . '/includes/acf_field_group.php';


function ndla_dependency_check()
{
    $function_dependencies = array(
        'register_field_group' // ACF v4
    );

    $depsOK = true;

    foreach ($function_dependencies as $function) {
        if (!function_exists($function)) {
            $depsOK = false;
        }
    }

    return $depsOK;
}


function ndla_plugins_loaded_check()
{
    if (!ndla_dependency_check()) {
        add_action('admin_notices', function () {
            $class = 'notice notice-error';
            $message = '<strong>'.__('NDLA Production system', 'ndla-prodsys').':</strong>&nbsp;' . __('A plugin dependency is missing. Some things may not work properly.', 'ndla-prodsys');

            printf('<div class="%1$s"><p>%2$s</p></div>', $class, $message);
        });
    }
}

add_action('plugins_loaded', 'ndla_plugins_loaded_check');


function ndla_prodsys_activate()
{
    if (!ndla_dependency_check()) {
        if (isset($_GET['action']) && $_GET['action'] == 'error_scrape') {
            echo '<h2>' . esc_html__('Unable to activate', 'ndla-prodsys') . '</h2>';
            echo '<p>' . esc_html__('Plugin dependencies missing', 'ndla-prodsys') . '</p>';

            exit;
        } else {
            //Adding @ before will prevent XDebug output
            @trigger_error(__('Unable to activate', 'ndla-prodsys'), E_USER_ERROR);
        }

    }
}

register_activation_hook(__FILE__, 'ndla_prodsys_activate');