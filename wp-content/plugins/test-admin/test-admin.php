<?php
/*
Plugin Name: Test Admin
Description: A test plugin to demonstrate wordpress functionality
Author: Simon Lissack
Version: 0.1
*/
add_action('admin_menu', 'test_plugin_setup_menu');

function test_plugin_setup_menu(){
    add_menu_page( 'Test Plugin Page', 'Test Plugin', 'manage_options', 'test-plugin', 'test_init' );
}

function test_init(){
    test_handle_post();
    ?>
    <h1>Hello World!</h1>
    <h2>Upload a File</h2>
    <!-- Form to handle the upload - The enctype value here is very important -->
    <form  method="post" enctype="multipart/form-data">
        <input type='file' id='test_upload_pdf' name='test_upload_pdf'></input>
        <?php submit_button('Upload') ?>
    </form>
    <?php
}

function test_handle_post(){
    // First check if the file appears on the _FILES array
    if(isset($_FILES['test_upload_pdf'])){
        $pdf = $_FILES['test_upload_pdf'];

        // Use the wordpress function to upload
        // test_upload_pdf corresponds to the position in the $_FILES array
        // 0 means the content is not associated with any other posts
        $uploaded=media_handle_upload('test_upload_pdf', 0);
        // Error checking using WP functions
        if(is_wp_error($uploaded)){
            echo "Error uploading file: " . $uploaded->get_error_message();
        }else{
            echo "File upload successful!";
        }
    }
}

function test_convert($id){
    // Get the file details
    $file = get_post($id);
    // Account details
    $email="";
    $password="";

    // Declare a new SoapClient - This is a class from PHP
    $client = new SoapClient('http://cloud.idrsolutions.com:8080/HTML_Page_Extraction/IDRConversionService?wsdl');

    // Get the data of the file as bytes
    $contents=file_get_contents(wp_get_attachment_url($id));

    // plugin_dir_path(__FILE__) gets the location of the plugin directory
    // Using preg replace to replace the directory sepeerators with the correct type
    // This is where the output will be written to
    $outputdir = preg_replace("[\\/]", DIRECTORY_SEPARATOR, plugin_dir_path(__FILE__)) . "output".DIRECTORY_SEPARATOR. $file->post_title. DIRECTORY_SEPARATOR;
    echo $outputdir;
    if (!file_exists($outputdir)) {
        mkdir($outputdir, 0777, true);
    }

    // Declare stlye parameters here - left blank here
    $style_params = array();
    // Set up array for the conversion
    $conversion_params = array("email" => $email,
        "password" =>$password,
        "fileName"=>$file->post_title,
        "dataByteArray"=>$contents,
        "conversionType"=>"html5",
        "conversionParams"=>$style_params,
        "xmlParamsByteArray"=>null,
        "isDebugMode"=>false);

    try{
        $output = (array)($client->convert($conversion_params));
        // This method is very improtant as it allows us access to the file system
        WP_Filesystem();
        // Write output as zip
        file_put_contents($outputdir.$file->post_title.".zip", $output);
        // Unzip the file
        $result=unzip_file($outputdir.$file->post_title.".zip", $outputdir);
    } catch (Exception $e){
        echo $e->getMessage() . "<br/>";
        return;
    }
}