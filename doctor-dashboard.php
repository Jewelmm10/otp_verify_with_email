<?php
/*
Template Name: Doctor Dashboard
*/

if (!is_user_logged_in()) {
    wp_redirect(site_url('/doctor-login'));
    exit;
}

$current_user = wp_get_current_user();
if (!in_array('doctor', $current_user->roles)) {
    wp_redirect(home_url());
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Dashboard</title>
    <?php wp_head(); ?>
</head>
<body style="padding:20px">
    <h2>Welcome, Dr. <?php echo $current_user->display_name; ?>!</h2>
    <p>This is your doctor dashboard.</p>
    <a href="<?php echo wp_logout_url(site_url('/doctor-login')); ?>">Logout</a>
    <?php wp_footer(); ?>
</body>
</html>
