<?php
/*
Plugin Name: Settings Readings
Description: Settings Management
Author: Nadeesha
Version: 0.1
*/

function wp_settings_init(){
    // register a new setting for "reading" page
    register_setting('reading', 'wporg_setting_name');
    register_setting('discussion', 'wporg_setting_name_discussion');

    // register a new section in the "reading" page
    add_settings_section(
        'wporg_settings_section',
        'WPOrg Settings Section',
        'wporg_settings_section_cb',
        'reading'
    );

    add_settings_section(
        'wporg_settings_section_discussion',
        'WPOrg Settings Section Discussion',
        'wporg_settings_section_discussion_cb',
        'discussion'
    );

    // register a new field in the "wporg_settings_section" section, inside the "reading" page
    add_settings_field(
        'wporg_settings_field',
        'WPOrg Setting',
        'wporg_settings_field_cb',
        'reading',
        'wporg_settings_section'
    );

    add_settings_field(
        'wporg_settings_discussion_field',
        'WPOrg Setting Discussion',
        'wporg_settings_field_discussion_cb',
        'discussion',
        'wporg_settings_section_discussion'
    );


}

add_action('admin_init', 'wp_settings_init');

/**
 * callback functions
 */

// section content cb
function wporg_settings_section_cb()
{
    echo '<p>WPOrg Section Introduction.</p>';
}

function wporg_settings_section_discussion_cb(){
    echo '<p>Discussion Settings</p>';
}

// field content cb
function wporg_settings_field_cb()
{
    // get the value of the setting we've registered with register_setting()
    $setting = get_option('wporg_setting_name');
    // output the field
    ?>
    <input type="text" name="wporg_setting_name" value="<?= isset($setting) ? esc_attr($setting) : ''; ?>">
    <?php
}

function wporg_settings_field_discussion_cb(){
    
}