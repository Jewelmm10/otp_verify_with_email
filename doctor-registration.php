<?php
/*
Template Name: Doctor Registration
*/

if (is_user_logged_in()) {
    wp_redirect(site_url('/doctor-dashboard')); // Redirect logged-in users
    exit;
}

if (isset($_POST['doctor_register'])) {
    $username = sanitize_user($_POST['username']);
    $email = sanitize_email($_POST['email']);
    $password = sanitize_text_field($_POST['password']);

    // Check if username or email already exists
    if (username_exists($username) || email_exists($email)) {
        $error = "Username or email already exists.";
    } else {
        $user_id = wp_create_user($username, $password, $email);
        if (!is_wp_error($user_id)) {
            wp_update_user([
                'ID' => $user_id,
                'role' => 'doctor' // Assign Doctor Role
            ]);
            update_user_meta($user_id, 'phone_number', sanitize_text_field($_POST['phone'])); // Save Phone Number
            $success = "Registration successful! You can now log in.";
        } else {
            $error = "Error creating account. Try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Registration</title>
    <?php wp_head(); ?>
</head>
<body style="padding:20px">
    <h2>Doctor Registration</h2>
    <form method="post">
        <label>Username:</label>
        <input type="text" name="username" required>
        <label>Email:</label>
        <input type="email" name="email" required>
        <label>Phone Number:</label>
        <input type="text" name="phone" required>
        <label>Password:</label>
        <input type="password" name="password" required>
        <button style="margin-top:10px" type="submit" name="doctor_register">Register</button>
    </form>
    <p>Already you have an account? <a href="http://localhost/otp/doctor-login/">Login</a></p>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>

    <?php wp_footer(); ?>
</body>
</html>
