<?php

return [
    // Login page
    'login' => [
        'title' => 'Login',
        'heading' => 'Sign in',
        'subheading' => 'Enter your credentials to access your account',
        'welcome_back' => 'Welcome back. Sign in to access your account and continue where you left off.',
        'email_label' => 'Email',
        'email_placeholder' => 'you@example.com',
        'identifier_label' => 'Username or email',
        'identifier_placeholder' => 'Username, or you@example.com',
        'password_label' => 'Password',
        'password_placeholder' => '••••••••',
        'remember_me' => 'Remember me',
        'forgot_password' => 'Forgot password?',
        'sign_in_button' => 'Sign in',
        'signing_in' => 'Signing in...',
        'show_password' => 'Show password',
        'hide_password' => 'Hide password',

        'passkey' => [
            'button' => 'Sign in with Passkey',
            'waiting' => 'Waiting for passkey verification...',
            'unsupported' => 'Passkeys are not supported in this browser',
            'generic_error' => 'We could not verify that passkey. Sign in with your password instead.',
            'recovery' => [
                'unrecognized_passkey' => [
                    'title' => 'That passkey is no longer linked to your account',
                    'message' => 'Your device offered a passkey this site does not recognise. It may have been removed from your account, or it belongs to a different one.',
                    'try_another' => 'Try another passkey',
                ],
                'expired_passkey_session' => [
                    'title' => 'That sign-in request expired',
                    'message' => 'For security, a passkey request is only valid for a short time. Nothing is wrong with your passkey, try again.',
                ],
                'passkey_verification_failed' => [
                    'title' => 'That passkey could not be verified',
                    'message' => 'The passkey is known to us but this verification was rejected, so it may have come from a different account.',
                ],
                'too_many_attempts' => [
                    'title' => 'Too many attempts',
                    'message' => 'Wait a minute, then try again or sign in with your password.',
                ],
            ],
        ],
    ],

    // Passkey re-enrolment nudge shown after signing in with a password
    'passkey_reenroll' => [
        'title' => 'Your passkey needs to be set up again',
        'message' => 'A passkey on your device was rejected because it is no longer registered to this account. Create a new one and you can skip your password next time.',
        'action' => 'Set up a passkey',
        'dismiss' => 'Dismiss',
    ],

    // Register page
    'register' => [
        'title' => 'Register',
        'heading' => 'Create account',
        'subheading' => 'Enter your details to create a new account',
        'name_label' => 'Name',
        'username_label' => 'Username',
        'username_placeholder' => 'yourname',
        'email_label' => 'Email',
        'password_label' => 'Password',
        'password_confirmation_label' => 'Confirm Password',
        'register_button' => 'Register',
        'creating_account' => 'Creating your account...',
        'already_have_account' => 'Already have an account?',
        'sign_in_link' => 'Sign in',
        'brand_line' => 'Create an account to book a replacement or track your quote.',
    ],

    // Forgot password
    'forgot_password' => [
        'title' => 'Forgot Password',
        'heading' => 'Reset Password',
        'subheading' => 'Enter your username or email address and we\'ll send you a link to reset your password.',
        'email_label' => 'Email',
        'identifier_label' => 'Username or email',
        'send_button' => 'Send Password Reset Link',
        'sending' => 'Sending reset link...',
        'back_to_login' => 'Back to login',
        'brand_line' => 'Forgot your password? Enter your username or email and we\'ll send you a reset link.',
    ],

    // Reset password
    'reset_password' => [
        'title' => 'Reset Password',
        'heading' => 'Reset Password',
        'subheading' => 'Enter your new password below.',
        'email_label' => 'Email',
        'password_label' => 'Password',
        'new_password_label' => 'New Password',
        'password_confirmation_label' => 'Confirm Password',
        'reset_button' => 'Reset Password',
        'updating' => 'Updating password...',
        'brand_line' => 'Choose a new password to regain access to your account.',
    ],

    // Verify email
    'verify_email' => [
        'title' => 'Verify Email',
        'heading' => 'Verify Your Email',
        'subheading' => 'Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?',
        'resend_button' => 'Resend Verification Email',
        'resending' => 'Resending email...',
        'logout_button' => 'Log Out',
        'resent' => 'A fresh verification link has been sent to your email address.',
        'brand_line' => 'Verifying your address keeps the notifications we send you legitimate.',
    ],

    // Confirm password
    'confirm_password' => [
        'title' => 'Confirm Password',
        'heading' => 'Confirm Password',
        'subheading' => 'Please confirm your password to continue.',
        'password_label' => 'Password',
        'confirm_button' => 'Confirm',
        'confirming' => 'Confirming...',
        'brand_line' => 'Confirming your password keeps sensitive account changes to you.',
    ],

    // Post-login consent capture
    'consent' => [
        'saving' => 'Saving...',
    ],

    // Two factor challenge
    'two_factor' => [
        'title' => 'Two-Factor Authentication',
        'heading' => 'Two-Factor Authentication',
        'subheading' => 'Please enter the code from your authenticator app.',
        'code_label' => 'Code',
        'recover_button' => 'Recover Account',
        'brand_line' => 'Two-factor authentication adds an extra layer of security to your account.',
        'authentication_code' => 'Authentication code',
        'recovery_code' => 'Recovery code',
        'verify_recovery_code' => 'Verify recovery code',
        'verifying' => 'Verifying code…',
        'use_recovery' => 'Lost your device? Use a recovery code',
        'use_code' => 'Use an authentication code instead',
    ],

    // Strings shared by every auth screen. Kept here so the flow's copy is
    // reviewed in one place instead of spread across eight templates.
    'ui' => [
        'or' => 'or',
        'fix_highlighted_fields' => 'Please check the highlighted fields.',
        'back_to_site' => 'Back to site',
        'back_to_login' => 'Back to login',
        'privacy' => 'Privacy',
        'terms' => 'Terms',
        'terms_link' => 'terms',
        'privacy_link' => 'privacy policy',
        'generate' => 'Generate',
        'remember_password' => 'Remember your password?',
        'no_account' => "Don't have an account?",
        'register_link' => 'Register',
        'already_have_account' => 'Already have an account?',
        'confirm_password_subheading' => 'This is a secure area. Please confirm your password to continue.',
        'verify_email_subheading' => "We've sent a verification link to your email address. Click it to activate your account.",
        'consent_heading' => 'Terms & Privacy',
        'consent_subheading' => "We haven't recorded your consent yet. Review and accept to continue.",
        'consent_brand_line' => 'One quick step before you can access your account.',
        'consent_accept' => 'Accept & continue',
        'consent_decline' => "Don't want to accept?",
        'log_out' => 'Log out',
    ],
];
