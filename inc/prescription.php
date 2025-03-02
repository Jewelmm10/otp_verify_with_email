<?php
// Get patient details from URL parameters
$patient_id = isset($_GET['patientID']) ? intval($_GET['patientID']) : '';
$patient_name = isset($_GET['name']) ? sanitize_text_field($_GET['name']) : '';

// Fetch previously saved prescription data
$prescription_medicine = get_user_meta($patient_id, 'prescription_medicine', true);
$prescription_apply = get_user_meta($patient_id, 'apply_pres', true);
$prescription_date = get_user_meta($patient_id, 'prescription_date', true);
?>

<div class="wrap">
    <h1><?php _e( 'New Prescription', 'theme' ); ?></h1>

    <form action="" method="post">
        <table class="form-table">
            <tbody>
                <!-- Hidden Field for Patient ID -->
                <input type="hidden" name="patientID" value="<?php echo esc_attr($patient_id); ?>">

                <tr>
                    <th scope="row">
                        <label for="name"><?php _e( 'Name', 'theme' ); ?></label>
                    </th>
                    <td>
                        <input type="text" name="name" id="name" class="regular-text" value="<?php echo esc_attr($patient_name); ?>" readonly>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="medicine"><?php _e( 'Medicine', 'theme' ); ?></label>
                    </th>
                    <td>
                        <textarea class="regular-text" name="medicine" id="medicine" rows="4"><?php echo esc_textarea($prescription_medicine); ?></textarea>
                    </td>
                </tr>                
                <tr>
                    <th scope="row">
                        <label for="prescription_date"><?php _e( 'Prescription Apply', 'theme' ); ?></label>
                    </th>
                    <td>
                        <input type="text" name="prescription_date" id="prescription_date" class="regular-text" value="<?php echo esc_attr($prescription_date ? date('F j, Y, g:i a', strtotime($prescription_date)) : 'No prescription yet'); ?>" readonly>
                    </td>
                </tr>
            </tbody>
        </table>

        <?php wp_nonce_field('new-prescription'); ?>
        <?php submit_button(__('Save Prescription', 'theme'), 'primary', 'submit_prescription'); ?>
    </form>
</div>
