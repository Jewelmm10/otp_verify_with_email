# WordPress Custom Login, Registration, and OTP Verification (Astra Child Theme)

This project is a custom **Astra Child Theme** for WordPress, which customizes the default login, registration, and OTP verification pages. It provides a fully dynamic and secure user experience with custom login forms, registration forms, and OTP verification functionality. The theme ensures that your site maintains compatibility with the Astra theme while extending its functionality.

## Features

- **Custom Login Page**: A custom-designed login page, replacing the default WordPress login screen.
- **Custom Registration Page**: A custom user registration page with editable fields and an easy-to-use interface.
- **OTP Verification**: Added security layer with OTP (One-Time Password) verification for both login and registration processes.
- **Responsive & Mobile-Friendly**: Fully responsive design that works seamlessly across devices.
- **Astra Child Theme Compatibility**: This theme is built on the Astra Child Theme, inheriting the Astra theme’s performance and flexibility.

## Installation

1. **Install Astra Theme**:
   - First, make sure you have the **Astra theme** installed and activated. You can download it from the WordPress theme repository or install it directly from the WordPress dashboard.

2. **Install Astra Child Theme**:
   - Download and install the custom Astra Child Theme from this project.
   - In WordPress, navigate to `Appearance > Themes > Add New > Upload Theme`, and select the **Astra Child Theme** folder from your local machine.
   - Activate the Astra Child Theme after installation.

3. **Configure Theme Settings**:
   - The child theme will automatically inherit the Astra theme settings. However, you can customize the design further using the WordPress Customizer (found under `Appearance > Customize`).
   - Ensure that the login, registration, and OTP pages are set up and styled according to your preferences.

## Key Pages

### Custom Login Page

- The login page will replace the default WordPress login screen.
- Navigate to `yourdomain.com/login` to view or customize the login form.
- **Features**:
  - Custom logo and design.
  - Custom background and styling.
  - Optional redirect after successful login.

### Custom Registration Page

- A user-friendly registration page, located at `yourdomain.com/register`.
- **Features**:
  - Customizable fields (username, email, password, etc.).
  - Option to add extra fields using hooks or custom functions.
  - User role selection can be added (e.g., Subscriber, Admin, etc.).

### OTP Verification

- OTP verification is integrated into both the login and registration pages.
- After entering the correct login credentials or completing the registration form, users will receive an OTP via **email** or **SMS** for verification.
- **OTP Setup**: 
  - Use an email service (like Gmail) to send OTPs.
  - Configure OTP functionality in the `functions.php` file.

#### Modify Login Page Template
- The custom login form is located at `wp-content/themes/astra-child/login.php`. 
- You can customize this file for your specific requirements.

#### Modify Registration Page Template
- The registration form template is located at `wp-content/themes/astra-child/register.php`.
- Modify the fields, validation logic, and form styling in this file.


### Modify OTP Functionality

- You can customize the OTP logic or integrate a different SMS or email service by editing the OTP code located in the `functions.php` file.
- To use email, ensure the `wp_mail()` function is properly configured.
### Custom Styling

- All styles related to the login and registration pages can be modified in the `style.css` file of the child theme.
- Use the WordPress Customizer to further tweak the theme settings, or directly edit CSS if you need advanced customizations.

## Requirements

- **WordPress Version**: 5.0 or higher
- **Astra Theme**: Latest version of the Astra theme (free or pro)
- **PHP Version**: 7.2 or higher
- **SMS/Email Service**: For OTP verification, integrate with services like **Twilio**, **Nexmo**, or **SendGrid**.

## Usage

1. **Login Page**: Access the login page at `yourdomain.com/login`.
   - Enter username and password to log in.
   - Upon successful login, you will be asked to enter an OTP sent via email/SMS.

2. **Registration Page**: Access the registration page at `yourdomain.com/register`.
   - Fill in the necessary details (username, email, password).
   - You will receive an OTP to verify your registration.
   - After OTP verification, you will be successfully registered and logged in.

3. **OTP Verification**: Both login and registration require OTP verification, which can be sent via email or SMS, depending on your configuration.

## Contributing

If you'd like to contribute or improve this theme, feel free to fork the repository and create a pull request. Contributions are welcome to enhance the functionality or add new features.

## License

This project is licensed under the MIT License.

## Contact

For questions or assistance, please contact [your-email@example.com].

---

Thank you for using the custom WordPress login, registration, and OTP verification system with the Astra Child Theme. We hope this improves your site's security and user experience!
