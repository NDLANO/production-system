<?php

function create_acf_field_group()
{
    if (function_exists("register_field_group")) {
        register_field_group(array(
            'id' => 'acf_emnebeskrivelser',
            'title' => 'Emnebeskrivelser',
            'fields' => array(
                array(
                    'key' => 'field_5881d56eef8f0',
                    'label' => 'Metabeskrivelse',
                    'name' => 'metabeskrivelse',
                    'type' => 'textarea',
                    'instructions' => 'Maks 155 tegn.',
                    'default_value' => '',
                    'placeholder' => '',
                    'maxlength' => 155,
                    'rows' => '',
                    'formatting' => 'br',
                ),
                array(
                    'key' => 'field_5881c7f166735',
                    'label' => 'Metabilde',
                    'name' => 'metabilde',
                    'type' => 'ndla_image',
                    'required' => 1,
                    'preview_size' => 'thumbnail',
                ),
                array(
                    'key' => 'field_5881d70101385',
                    'label' => 'Ingress',
                    'name' => 'ingress',
                    'type' => 'textarea',
                    'instructions' => 'Maks 300 tegn.',
                    'default_value' => '',
                    'placeholder' => '',
                    'maxlength' => 300,
                    'rows' => '',
                    'formatting' => 'br',
                ),
                array(
                    'key' => 'field_5891b183f0e1c',
                    'label' => 'Visuelt element',
                    'name' => 'visuelt_element',
                    'type' => 'ndla_image',
                    'required' => 1,
                    'preview_size' => 'thumbnail',
                ),
                array(
                    'key' => 'field_5881d78801386',
                    'label' => 'Brødtekst',
                    'name' => 'brodtekst',
                    'type' => 'wysiwyg',
                    'default_value' => '',
                    'toolbar' => 'full',
                    'media_upload' => 'no',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'emnebeskrivelse',
                        'order_no' => 0,
                        'group_no' => 0,
                    ),
                ),
            ),
            'options' => array(
                'position' => 'normal',
                'layout' => 'no_box',
                'hide_on_screen' => array(),
            ),
            'menu_order' => 0,
        ));
    }
}

add_action('init', 'create_acf_field_group');