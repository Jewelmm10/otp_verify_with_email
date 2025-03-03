<?php
/*
Template Name: Registration
*/

if (is_user_logged_in()) {
    wp_redirect(site_url('/dashboard'));
    exit;
}

if (isset($_POST['register'])) {

    $username = sanitize_user($_POST['username']);
    $email = sanitize_email($_POST['email']);
    $password = sanitize_text_field($_POST['password']);
	$role     = sanitize_text_field($_POST['role']);

    // Check if username or email already exists
    if (username_exists($username) || email_exists($email)) {
        $error = "Username or email already exists.";
    } else {
        $user_id = wp_create_user($username, $password, $email, $role);
        if (!is_wp_error($user_id)) {
            wp_update_user([
                'ID' => $user_id,
                'role' => $role 
            ]);
            
            wp_redirect(site_url('/login?registration=success'));
            exit; 
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
    <title>Login</title>
    <?php wp_head(); ?>
</head>
<body>
	<div class="custom-dash">


    <div id="container" class="active">		
		<div class="page back">
				<div class="content">
					<svg xmlns="http://www.w3.org/2000/svg" width="96" height="96" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-in"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
					<h1>Welcome Back!</h1>
					<p>To keep connected with us please login with your personal info</p>
					<a class="btn" href="<?php echo esc_url(site_url('/login/')); ?>" id="login"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left-circle"><circle cx="12" cy="12" r="10"/><polyline points="12 8 8 12 12 16"/><line x1="16" y1="12" x2="8" y2="12"/></svg> Log In</a>
			</div>
		</div>
		<div class="register">
			<div class="content">
				<h1>Sign Up</h1>
				<?php 
                if (isset($error)) {
                    echo '<p style="color:red;">' . $error . '</p>';
                }
                ?>	
				<form class="myform" method="POST">
                    <input type="text" name="username" placeholder="Username" required>
                    <input type="email" name="email" placeholder="Email" required>
                    <input type="password" name="password" placeholder="Password" required>
                    <select name="role">
                        <option value="doctor">Doctor</option>
                        <option value="patient">Patient</option>
                    </select>
                    <button type="submit" name="register">Register</button>
                </form>
			</div>		
		</div>
    </div>
	</div>
	
    <?php wp_footer(); ?>
</body>
</html>
