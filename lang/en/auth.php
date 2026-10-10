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
        'email_label' => 'Email',
        'password_label' => 'Password',
        'password_confirmation_label' => 'Confirm Password',
        'register_button' => 'Register',
        'creating_account' => 'Creating your account...',
        'already_have_account' => 'Already have an account?',
        'sign_in_link' => 'Sign in',
    ],

    // Forgot password
    'forgot_password' => [
        'title' => 'Forgot Password',
        'heading' => 'Reset Password',
        'subheading' => 'Enter your email address and we\'ll send you a link to reset your password.',
        'email_label' => 'Email',
        'send_button' => 'Send Password Reset Link',
        'sending' => 'Sending reset link...',
        'back_to_login' => 'Back to login',
    ],

    // Reset password
    'reset_password' => [
        'title' => 'Reset Password',
        'heading' => 'Reset Password',
        'subheading' => 'Enter your new password below.',
        'email_label' => 'Email',
        'password_label' => 'Password',
        'password_confirmation_label' => 'Confirm Password',
        'reset_button' => 'Reset Password',
        'updating' => 'Updating password...',
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
    ],

    // Confirm password
    'confirm_password' => [
        'title' => 'Confirm Password',
        'heading' => 'Confirm Password',
        'subheading' => 'Please confirm your password to continue.',
        'password_label' => 'Password',
        'confirm_button' => 'Confirm',
        'confirming' => 'Confirming...',
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
    ],
];
