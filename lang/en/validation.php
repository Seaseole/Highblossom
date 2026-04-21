<?php

return [
    // General validation
    'required' => 'The :attribute field is required.',
    'email' => 'The :attribute must be a valid email address.',
    'unique' => 'The :attribute has already been taken.',
    'min' => 'The :attribute must be at least :min characters.',
    'max' => 'The :attribute may not be greater than :max characters.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'current_password' => 'The password is incorrect.',
    'password' => 'The password must be at least 8 characters.',

    // Custom attribute names
    'attributes' => [
        'name' => 'name',
        'email' => 'email address',
        'password' => 'password',
        'current_password' => 'current password',
        'password_confirmation' => 'password confirmation',
        'phone' => 'phone number',
        'address' => 'address',
        'title' => 'title',
        'description' => 'description',
        'content' => 'content',
        'status' => 'status',
        'role' => 'role',
        'image' => 'image',
        'icon' => 'icon',
    ],
];
