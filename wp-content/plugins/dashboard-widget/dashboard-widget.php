<?php
/*
Plugin Name: Dashboard-Widget
Description: A test plugin to demonstrate wordpress functionality
Author: Nadeesha
Version: 0.1
*/

/**
 * Add a widget to the dashboard.
 *
 * This function is hooked into the 'wp_dashboard_setup' action below.
 */
function example_add_dashboard_widgets() {

//    wp_add_dashboard_widget(
//        'example_dashboard_widget',         // Widget slug.
//        'Example Dashboard Widget',         // Title.
//        'example_dashboard_widget_function' // Display function.
//    );

    add_meta_box( 'id', 'Dashboard Widget Title', 'dash_widget', 'dashboard', 'side', 'high' );
}
add_action( 'wp_dashboard_setup', 'example_add_dashboard_widgets' );

/**
 * Create the function to output the contents of our Dashboard Widget.
 */
function dash_widget() {

    // Display whatever it is you want to show.
    global $wpdb;
    ?>
    <table class="widefat">
        <thead>
        <tr>
            <th>RegId</th>
            <th>Name</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>RegId</th>
            <th>Name</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
        </tfoot>
        <tbody>
        <?php

        $students = $wpdb->get_results("SELECT * FROM wp_students");

        foreach ( $students as $student ) {
            ?>
            <tr>
                <td><?php echo $student->regid; ?></td>
                <td><?php echo $student->name; ?></td>
                <td><?php echo $student->email; ?></td>
                <td><input type="button" class="button button-primary" value="Edit"></td>
            </tr>
            <?php

        }

        ?>


        </tbody>
    </table>
<?php
}