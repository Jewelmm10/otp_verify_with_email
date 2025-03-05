# Astra Child Theme - Custom Login, Registration, and OTP Verification

## Overview

This Astra Child Theme adds custom functionality to your WordPress website for managing doctor and patient registrations. It also integrates OTP (One-Time Password) verification for doctor logins and includes a doctor’s dashboard to manage patients' prescriptions.

### Features

- **Custom Registration and Login**:
  - Separate registration and login for doctors and patients.
  - Doctors must complete an OTP verification upon login.
  
- **OTP Verification**:
  - OTP sent to the doctor's email for login verification.
  
- **Doctor Dashboard**:
  - Doctors can view a list of all patients.
  - Doctors can submit a prescription using a custom form.

- **Patient Dashboard**:
  - Patients can only view updated prescriptions from their doctor.

---

## Requirements

- WordPress version 5.0 or higher.
- Astra Theme.
- Basic knowledge of WordPress theme customization.
- An SMTP plugin for email verification (for OTP feature).

---

## Installation

1. **Install Astra Theme**:
   - Install the Astra theme from the WordPress Theme Directory.

2. **Install Astra Child Theme**:
   - Upload this child theme folder (`astra-child`) to your WordPress site’s `wp-content/themes/` directory.
   - Activate the Astra Child Theme via the WordPress admin panel.

---

## Features Configuration

### 1. **Doctor and Patient Registration**

- **Doctor Registration**:
  - Navigate to the registration page `/doctor-register/` to register as a doctor.
  - The registration form will include typical details like username, email, password.
  
- **Patient Registration**:
  - Navigate to the registration page `/patient-register/` to register as a patient.
  - The patient registration form will be simpler, requiring the patient’s username, email, and password.

### 2. **OTP Verification for Doctor Login**

- When a doctor logs in, the system sends an OTP to their registered email.
- The doctor must enter the OTP to successfully log in.

### 3. **Doctor Dashboard and Patient Management**

- After login, the doctor is directed to their dashboard.
- **Patient List**: The doctor can view a list of all registered patients.
- **Submit Prescription**: The doctor can submit prescriptions via a custom form, which includes fields like medicine name, dosage, and instructions.

### 4. **Patient Dashboard**

- After login, patients can view their prescriptions from the doctor on their personal dashboard.
- Only the doctor’s latest prescriptions are visible.

---

## Code Structure

- `functions.php`: Contains custom functions for registration, login, and OTP verification.
- `assets/css/`: Custom CSS styles for the theme's registration and login pages.
- `assets/js/`: Custom JavaScript for OTP verification and form submissions.

---

## Customization

You can customize the registration and login pages by editing the corresponding template files.

- **registration.php**: Customize the doctor & patients registration page.
- **login.php**: Customize the doctor & patient login page with OTP integration only for doctor.
- **otp-verify.php**: Customize the doctor OTP integration page.
- **patient-dashboard.php**: Customize the patient dashboard to display prescriptions.


### Example of a Simple Page Creation in WordPress:

1. **Page Creation Steps**:
   - Go to **Pages > Add New** in the WordPress dashboard.
   - Set a title for the page (e.g., **Registration**).
   - Under **Page Attributes**, select the appropriate template from the dropdown (e.g., **Registration**).
   - Publish the page.

2. **Page Linking**:
   After the page is published, you can link to it in menus or use custom redirects. For instance, after a doctor successfully registers, you can redirect them to the **Doctor Login** page.


---

## License

This theme is licensed under the [MIT License](LICENSE).

