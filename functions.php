<?php

function child_enqueue_files() {
    // Parent and child theme styles
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('child-style', get_stylesheet_uri(), array('parent-style','elementor-frontend'));

    wp_enqueue_script('script', get_stylesheet_directory_uri() . '/assets/js/scripts.js', array('jquery'), '', true);
}

add_action('wp_enqueue_scripts', 'child_enqueue_files', 20);

//modify register form 
function custom_registration_password_fields() {
    ?>
    <p>
        <label for="password"><?php _e('Password', 'textdomain'); ?><br />
            <input type="password" name="password" id="password" class="input" size="25" />
        </label>
    </p>
    <p>
        <label for="password_confirm"><?php _e('Confirm Password', 'textdomain'); ?><br />
            <input type="password" name="password_confirm" id="password_confirm" class="input" size="25" />
        </label>
    </p>
    <?php
}
add_action('register_form', 'custom_registration_password_fields');

function custom_registration_password_validation($errors, $sanitized_user_login, $user_email) {
    if (empty($_POST['password']) || empty($_POST['password_confirm'])) {
        $errors->add('password_error', __('ERROR: Please enter a password.', 'textdomain'));
    } elseif ($_POST['password'] !== $_POST['password_confirm']) {
        $errors->add('password_mismatch', __('ERROR: Passwords do not match.', 'textdomain'));
    }
    return $errors;
}
add_filter('registration_errors', 'custom_registration_password_validation', 10, 3);

function custom_registration_save_password($user_id) {
    if (!empty($_POST['password'])) {
        wp_set_password($_POST['password'], $user_id);
    }
}
add_action('user_register', 'custom_registration_save_password');





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
        $prescription_date = current_time('mysql'); // Get the current WordPress time

        // Ensure required fields are not empty
        if (!$patient_id || empty($medicine)) {
            wp_die(__('Missing required fields.', 'theme'));
        }

        // Save prescription data as user meta
        update_user_meta($patient_id, 'prescription_medicine', $medicine);
        update_user_meta($patient_id, 'apply_pres', $apply_pres);
        update_user_meta($patient_id, 'prescription_date', $prescription_date); // Store date

        // Redirect back to the patient list with a success message
        wp_redirect(admin_url('admin.php?page=patients-list&message=success'));
        exit;
    }
}
add_action('admin_init', 'save_prescription_data'); // Hook to process form submission


function custom_add_patient_dashboard() {
    
    if (current_user_can('patient')) {
        add_menu_page(
            'My Prescription', 
            'My Prescription', 
            'read', 
            'patient-prescription', 
            'custom_patient_prescription_page', 
            'dashicons-heart', 
            5
        );
    }
}
add_action('admin_menu', 'custom_add_patient_dashboard');

function custom_patient_prescription_page() {
   
    $patient_id = get_current_user_id();

    // Fetch prescription details
    $prescription_medicine = get_user_meta($patient_id, 'prescription_medicine', true);
    $prescription_date = get_user_meta($patient_id, 'prescription_date', true);

    // Format date
    $formatted_date = $prescription_date ? date('F j, Y, g:i a', strtotime($prescription_date)) : 'N/A';

    ?>
    <div class="wrap">
        <h1><?php _e('My Prescription', 'theme'); ?></h1>

        <?php if (!$prescription_medicine): ?>
            <p><?php _e('No prescription found.', 'theme'); ?></p>
        <?php else: ?>
            <table class="wp-list-table widefat fixed striped">
                <tbody>
                    <tr>
                        <th width="30%"><?php _e('Medicine:', 'theme'); ?></th>
                        <td>
                            <ul>
                                <?php
                                
                                $medicines = explode("\n", $prescription_medicine);
                                foreach ($medicines as $medicine) {
                                    echo '<li>' . esc_html(trim($medicine)) . '</li>';
                                }
                                ?>
                            </ul>
                        </td>
                    </tr>
                    <tr>
                        <th><?php _e('Prescription Apply:', 'theme'); ?></th>
                        <td><?php echo esc_html($formatted_date); ?></td>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <?php
}


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

