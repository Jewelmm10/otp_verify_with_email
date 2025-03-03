<?php
/*
Template Name: Patient Dashboard
*/

get_header(); // Load header template
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f7fc;
            color: #333;
            padding: 20px;
        }
        .dashboard-container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            padding: 20px;
            width: 900px;
            margin: 20px auto;
        }
        h3 {
            font-size: 24px;
            color: #333;
            text-align: center;
            margin-bottom: 10px;
        }
        p {
            font-size: 16px;
            line-height: 1.5;
            text-align: center;
            color: #666;
        }
        .welcome-message {
            font-size: 20px;
            color: #4CAF50;
            text-align: center;
        }
        .logout-btn {
            display: block;
            text-align: center;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #e74c3c;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .logout-btn:hover {
            background-color: #c0392b;
        }
        .dashboard-link {
            display: block;
            text-align: center;
            margin-top: 10px;
            padding: 8px 16px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .dashboard-link:hover {
            background-color: #2980b9;
        }
        table.widefat.medicine td, table.widefat.medicine th {
            color: #000;
            font-size: 20px;
        }
    </style>
</head>
<body>

<div class="dashboard-container">
    <?php 
        // Ensure the user is logged in
        if (is_user_logged_in()) {
            $current_user = wp_get_current_user();    
    ?>

    <h3>Welcome, <?php echo esc_html($current_user->display_name); ?>!</h3> 
    <div class="dashboard-links">
        <?php 
            $patient_id = get_current_user_id();

            // Fetch prescription details
            $prescription_medicine = get_user_meta($patient_id, 'prescription_medicine', true);
            $prescription_date = get_user_meta($patient_id, 'prescription_date', true);
        
            // Format date
            $formatted_date = $prescription_date ? date('F j, Y, g:i a', strtotime($prescription_date)) : 'N/A';
        
            ?>
            <div class="wrap">
                <h3><?php _e('My Prescription', 'theme'); ?></h3>
        
                <?php if (!$prescription_medicine): ?>
                    <p><?php _e('No prescription found.', 'theme'); ?></p>
                <?php else: ?>
                    <table class="widefat medicine">
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
       
    </div>

    <!-- Logout Link -->
    <a href="<?php echo wp_logout_url( home_url() ); ?>" class="logout-btn">Logout</a>
    
    <?php 
        } else {
            echo '<p>You are not logged in. Please log in to access your dashboard.</p>';
        }
    ?>
</div>

<?php wp_footer(); ?>
</body>
</html>
