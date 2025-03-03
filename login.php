<?php
/*
Template Name: Login
*/

// Redirect logged-in users to the appropriate dashboard based on their role
if (is_user_logged_in()) {
    // Redirect to doctor dashboard if the user is a doctor
    if (current_user_can('doctor')) {
        wp_redirect(site_url('/wp-admin'));
    }     
    exit;
}

// Handle the login form submission
if (isset($_POST['login'])) {
    $username = sanitize_text_field($_POST['username']);
    $password = sanitize_text_field($_POST['password']);
    
    // Attempt to get user by email or username
    if (is_email($username)) {
        // If it's an email, get the user by email
        $user = get_user_by('email', $username);
    } else {
        // Otherwise, get the user by username
        $user = get_user_by('login', $username);
    }

    if ($user) {
        // Check password
        if (wp_check_password($password, $user->user_pass, $user->ID)) {
            // Password is correct, proceed to handle role-based redirection
            $roles = $user->roles;
            $creds = array(
                'user_login'    => $username,
                'user_password' => $password,
                'remember'      => true // Set to false if you do not want to remember the user
            );
        
            // Attempt to sign in the user
            $user = wp_signon($creds, false);
            if (in_array('doctor', $roles)) {
                // Handle doctor OTP
                $email = $user->user_email;
                $otp = rand(100000, 999999); 
                update_user_meta($user->ID, 'doctor_otp', $otp);

                // Send OTP email
                $subject = "Your OTP Code for Login";
                $message = "Your OTP code is: <strong>$otp</strong>. This code is valid for 5 minutes.";
                $headers = array('Content-Type: text/html; charset=UTF-8', 'From: OTP Service <jewelbdcalling@gmail.com>');

                $mail_sent = wp_mail($email, $subject, $message, $headers); 

                if ($mail_sent) {
                    // Set OTP pending cookie for 5 minutes
                    setcookie("doctor_otp_pending", $user->ID, time() + 300, COOKIEPATH, COOKIE_DOMAIN);
                    wp_redirect(site_url('/otp-verify')); 
                    exit;
                } else {
                    $error = "Failed to send OTP. Please check email settings.";
                    error_log('❌ Failed to send OTP email to ' . $email);
                }
            } elseif (in_array('patient', $roles)) {                
                wp_redirect(site_url('/patient-dashboard'));
                exit;
            } else {
                // Invalid role: user is neither doctor nor patient
                $error = "Your account does not have a valid role (doctor or patient).";
            }
        } else {
            // Invalid password
            $error = "Invalid password. Please try again.";
        }
    } else {
        // Invalid username or email
        $error = "Invalid username or email address.";
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
				<h1>Log In</h1>
                <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
                <form class="myform" method="POST">
                    <input type="text" name="username" placeholder="Username or Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <button type="submit" name="login">Log In</button>
                </form>		
			</div>
		</div>
		<div class="page front">
			<div class="content">
				 <svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
					<h1>Hello, friend!</h1>
					<p>Enter your personal details and start journey with us</p>
					<a class="btn" href="<?php echo esc_url(site_url('/registration/')); ?>" id="register">Register <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right-circle"><circle cx="12" cy="12" r="10"/><polyline points="12 16 16 12 12 8"/><line x1="8" y1="12" x2="16" y2="12"/></svg></a>
			</div>
		</div>
	
    </div>
	</div>
    <?php wp_footer(); ?>
</body>
</html>
