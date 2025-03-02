<div class="wrap">
    <h1>Patients List</h1>
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th width="20%">Name</th>
                <th width="20%">Email</th>
                <th width="15%">Role</th>
                <th width="20%">Last Updated</th>
                <th width="10%">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $users = get_users(['role' => 'patient']); 
            if (!empty($users)) {
                foreach ($users as $user) {
                    // Get prescription details
                    $apply_pres = get_user_meta($user->ID, 'apply_pres', true);
                    $prescription_date = get_user_meta($user->ID, 'prescription_date', true);

                    // Format date if available
                    $formatted_date = $prescription_date ? date('F j, Y, g:i a', strtotime($prescription_date)) : '❌ No Prescription';

                    // Prescription link
                    $prescription_url = admin_url('admin.php?page=patients-list&action=prescription&patientID=' . $user->ID . '&name=' . urlencode($user->display_name));

                    echo "<tr>
                        <td>{$user->display_name}</td>
                        <td>{$user->user_email}</td>
                        <td>Patient</td>
                        <td style='text-align:center;'>{$formatted_date}</td>
                        <td><a href='{$prescription_url}' class='button button-primary'>Prescription</a></td>
                    </tr>";
                }
            } else {
                echo '<tr><td colspan="6">No patients found.</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>
