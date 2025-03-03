<?php
/*
Template Name: OTP Verify
*/

if (!isset($_COOKIE['doctor_otp_pending'])) {
    wp_redirect(site_url('/login'));
    exit;
}

$doctor_id = intval($_COOKIE['doctor_otp_pending']);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['verify_otp'])) {
    $entered_otp = sanitize_text_field($_POST['otp']);
    $saved_otp = get_user_meta($doctor_id, 'doctor_otp', true);

    if ($entered_otp == $saved_otp) {
        delete_user_meta($doctor_id, 'doctor_otp'); 
        setcookie("doctor_otp_pending", "", time() - 3600, COOKIEPATH, COOKIE_DOMAIN);

        $user = get_user_by('ID', $doctor_id);

        if ($user) {
            // Login the doctor user as if they used wp-login.php
            $creds = array(
                'user_login'    => $user->user_login,
                'user_password' => null, // No password needed since OTP is verified
                'remember'      => true
            );

            wp_clear_auth_cookie();
            wp_set_current_user($doctor_id);
            wp_set_auth_cookie($doctor_id, true);

            // Redirect to the admin dashboard
            wp_safe_redirect(admin_url());
            exit;
        }
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
    <title>Login</title>
    <?php wp_head(); ?>
</head>
<body>
		<div class="custom-dash">
    <div id="container">
		<div class="login">
			<div class="content">
				<h1>Enter Your OTP</h1>
                <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
                <form class="myform" method="POST">
                    <input type="text" name="otp" required>
                    <button type="submit" name="verify_otp">Verify OTP</button>
                </form>		
			</div>
		</div>
		<div class="page front">
			<div class="content">
				 <svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
					<h1>Check your email</h1>
					<p>This code is valid for 5 minutes.</p>					
			</div>
		</div>
	
    </div>
	</div>
    <?php wp_footer(); ?>
</body>
</html>
