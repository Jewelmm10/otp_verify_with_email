<?php

function child_enqueue_files() {
    // Parent and child theme styles
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_uri(), array('parent-style','elementor-frontend'));

    wp_enqueue_style('form-css', get_stylesheet_directory_uri() . '/assets/css/form.css');

    wp_enqueue_script('script', get_stylesheet_directory_uri() . '/assets/js/scripts.js', array('jquery'), '', true);
}

add_action('wp_enqueue_scripts', 'child_enqueue_files', 20);

function custom_add_roles() {
    // Add Doctor Role
    add_role('doctor', 'Doctor', [
        'read' => true, 
        'edit_posts' => false, 
        'delete_posts' => false, 
    ]);

    // Add Patient Role
    add_role('patient', 'Patient', [
        'read' => true, 
        'edit_posts' => false, 
        'delete_posts' => false, 
    ]);
}
add_action('init', 'custom_add_roles');


//patients
function custom_add_patients_menu() {
    // Check if user is admin or doctor
    if (current_user_can('administrator') || current_user_can('doctor')) {
        add_menu_page(
            'Patients', 
            'Patients', 
            'read', 
            'patients-list', 
            'custom_patients_page_callback',
            'dashicons-groups', 
            25 
        );
    }
}
add_action('admin_menu', 'custom_add_patients_menu');

function custom_patients_page_callback() {
    if (!current_user_can('administrator') && !current_user_can('doctor')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }

    $action = isset($_GET['action']) && !empty($_GET['action']) ? sanitize_text_field($_GET['action']) : 'patients-list';

    $template = '';

    // Determine the template to load
    switch ($action) {
        case 'prescription':
            $template = __DIR__ . '/inc/prescription.php';
            break;
        case 'view':
            $template = __DIR__ . '/inc/view.php';
            break;
        default:
            $template = __DIR__ . '/inc/list.php'; 
            break;
    }

    // Ensure only one file is included
    if ($template && file_exists($template)) {
        require_once $template;
        exit; 
    } else {
        echo '<p>Template not found.</p>';
        exit;
    }
}

function save_prescription_data() {
    // Check if the form was submitted
    if (isset($_POST['submit_prescription'])) {
        
        // Verify Nonce for Security
        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'new-prescription')) {
            wp_die(__('Security check failed.', 'theme'));
        }

        // Sanitize and Validate Inputs
        $patient_id = isset($_POST['patientID']) ? intval($_POST['patientID']) : 0;
        $medicine   = isset($_POST['medicine']) ? sanitize_textarea_field($_POST['medicine']) : '';
        $apply_pres = isset($_POST['apply_pres']) ? sanitize_text_field($_POST['apply_pres']) : '';
        $prescription_date = current_time('mysql'); 

        // Ensure required fields are not empty
        if (!$patient_id || empty($medicine)) {
            wp_die(__('Missing required fields.', 'theme'));
        }

        // Save prescription data as user meta
        update_user_meta($patient_id, 'prescription_medicine', $medicine);
        update_user_meta($patient_id, 'apply_pres', $apply_pres);
        update_user_meta($patient_id, 'prescription_date', $prescription_date); 

     
        wp_redirect(admin_url('admin.php?page=patients-list&message=success'));
        exit;
    }
}
add_action('admin_init', 'save_prescription_data'); 

function custom_smtp_mail_config($phpmailer) {
    $phpmailer->isSMTP();
    $phpmailer->Host = 'smtp.gmail.com';
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = 587;
    $phpmailer->Username = 'jewelbdcalling@gmail.com'; 
    $phpmailer->Password = 'fgbwckbzosnumnrm';
    $phpmailer->SMTPSecure = 'tls'; 
    $phpmailer->SMTPAutoTLS = true;
    $phpmailer->isHTML(true);
    $phpmailer->setFrom('jewelbdcalling@gmail.com', 'OTP Service');
   // $phpmailer->addReplyTo('jewelbdcalling@gmail.com', 'OTP Service');
}
add_action('phpmailer_init', 'custom_smtp_mail_config');


// Hide admin bar for subscribers
add_action('after_setup_theme', function () {
    if (current_user_can('patient')) {
        show_admin_bar(false);
    }
    if (current_user_can('doctor')) {
        show_admin_bar(false);
    }
});
