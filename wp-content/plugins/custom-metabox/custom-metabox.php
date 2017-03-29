<?php
/*
Plugin Name: custom metabox
Description: Settings Management
Author: Nadeesha
Version: 0.1
*/

function wporg_add_custom_box()
{
    $screens = ['post', 'wporg_cpt', 'page'];
    foreach ($screens as $screen) {
        add_meta_box(
            'wporg_box_id',           // Unique ID
            'Custom Meta Box Title',  // Box title
            'wporg_custom_box_html',  // Content callback, must be of type callable
            $screen                   // Post type
        );
    }
}
add_action('add_meta_boxes', 'wporg_add_custom_box');


function wporg_custom_box_html($post)
{
    $value = get_post_meta($post->ID, '_wporg_meta_key', true);
    ?>
    <label for="wporg_field">Description for this field</label>
    <select name="wporg_field" id="wporg_field" class="postbox">
        <option value="">Select something...</option>
        <option value="something" <?php selected($value, 'something'); ?>>Something</option>
        <option value="else" <?php selected($value, 'else'); ?>>Else</option>
    </select>
    <?php
}

function wporg_save_postdata($post_id)
{
    if (array_key_exists('wporg_field', $_POST)) {

        update_post_meta(
            $post_id,
            '_wporg_meta_key',
            sanitize_text_field($_POST['wporg_field'])
        );
    }
}
add_action('save_post', 'wporg_save_postdata');