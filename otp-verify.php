<?php
/*
Template Name: Doctor OTP Verification
*/

if (!isset($_COOKIE['doctor_otp_pending'])) {
    wp_redirect(site_url('/doctor-login'));
    exit;
}

$doctor_id = intval($_COOKIE['doctor_otp_pending']);

if (isset($_POST['verify_otp'])) {
    $entered_otp = sanitize_text_field($_POST['otp']);
    $saved_otp = get_user_meta($doctor_id, 'doctor_otp', true);

    if ($entered_otp == $saved_otp) {
        delete_user_meta($doctor_id, 'doctor_otp'); 
        setcookie("doctor_otp_pending", "", time() - 3600, COOKIEPATH, COOKIE_DOMAIN);
        wp_set_current_user($doctor_id);
        wp_set_auth_cookie($doctor_id);
        wp_redirect(site_url('/doctor-dashboard')); // Redirect to dashboard
        exit;
    } else {
        $error = "Invalid OTP. Try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor OTP Verification</title>
    <?php wp_head(); ?>
</head>
<body style="padding:20px">
    <h2>Enter Your OTP Code</h2>
    <form method="post">
        <label>OTP Code:</label>
        <input type="text" name="otp" required>
        <button style="margin-top:10px" type="submit" name="verify_otp">Verify OTP</button>
    </form>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <?php wp_footer(); ?>
</body>
</html>
