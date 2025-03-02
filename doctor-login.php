<?php
/*
Template Name: Doctor Login
*/

if (is_user_logged_in()) {
    wp_redirect(site_url('/doctor-dashboard')); // Redirect logged-in users
    exit;
}

if (isset($_POST['doctor_login'])) {
    $username = sanitize_text_field($_POST['username']);
    $user = get_user_by('login', $username);

    if ($user && in_array('doctor', $user->roles)) {
        $email = $user->user_email;

        if (!empty($email)) { 
            $otp = rand(100000, 999999); 
            update_user_meta($user->ID, 'doctor_otp', $otp);

            $subject = "Your OTP Code for Login";
            $message = "Your OTP code is: <strong>$otp</strong>. This code is valid for 5 minutes.";
            $headers = array('Content-Type: text/html; charset=UTF-8', 'From: OTP Service <jewelbdcalling@gmail.com>');

            $mail_sent = wp_mail($email, $subject, $message, $headers); 

            if ($mail_sent) {
                setcookie("doctor_otp_pending", $user->ID, time() + 300, COOKIEPATH, COOKIE_DOMAIN);
                wp_redirect(site_url('/doctor-otp-verify')); 
                exit;
            } else {
                $error = "Failed to send OTP. Please check email settings.";
                error_log('❌ Failed to send OTP email to ' . $email);
            }
        } else {
            $error = "Doctor account does not have a valid email.";
        }
    } else {
        $error = "Invalid username or not a doctor account.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Login</title>
    <?php wp_head(); ?>
</head>
<body style="padding:20px">
    <h2>Doctor Login</h2>
    <form method="post">
        <label>Username:</label>
        <input type="text" name="username" required>
        <button style="margin-top:10px" type="submit" name="doctor_login">Send OTP</button>
    </form>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <p>Not a member?<a href="http://localhost/otp/doctor-registration/"> Sign up</a></p>
    <?php wp_footer(); ?>
</body>
</html>
