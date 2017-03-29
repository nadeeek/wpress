<?php
/*
Plugin Name: Student
Description: Student Management
Author: Nadeesha
Version: 0.1
*/

add_action('admin_menu', 'admin_student_setup_menu');

function admin_student_setup_menu(){
    add_menu_page( 'Student Menu', 'Student', 'manage_options', 'student', 'student_init' );
}

function student_init(){
//    test_handle_post();
    global $wpdb;
    

    ?>
    <h1>Student Management</h1>

    <h2>Add Students</h2>

    <?php
    if ( ! empty( $_POST ) ) {
        // Sanitize the POST field
        $name = sanitize_text_field( $_POST['name'] );
        $email = sanitize_email( $_POST['email'] );

        $wpdb->insert('wp_students', array(
            'name' => $name,
            'email' => $email
        ));
    }
    ?>

    <form method="POST" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
        <ul>
            <li><label for="name">Name<span> *</span>: </label>
                <input type="text" id="name" maxlength="50" size="10" name="name" value="" /></li>

            <li><label for="email">Email<span> *</span>: </label>
                <input type="email" id="email" maxlength="20" size="10" name="email" value="" /></li>
            <?php submit_button('Save') ?>
        </ul>
    </form>

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