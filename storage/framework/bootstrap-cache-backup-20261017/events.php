<?php return array (
  'App\\Providers\\EventServiceProvider' => 
  array (
    'App\\Events\\ContactMessageReceived' => 
    array (
      0 => 'App\\Listeners\\SendContactMessageEmail',
    ),
    'Illuminate\\Auth\\Events\\Login' => 
    array (
      0 => 'App\\Listeners\\RecordSessionLogin',
    ),
    'Illuminate\\Auth\\Events\\Logout' => 
    array (
      0 => 'App\\Listeners\\EndSessionOnLogout',
    ),
    'Illuminate\\Auth\\Events\\PasswordReset' => 
    array (
      0 => 'App\\Listeners\\RevokeSessionsOnPasswordReset',
    ),
  ),
  'Illuminate\\Foundation\\Support\\Providers\\EventServiceProvider' => 
  array (
    'Illuminate\\Auth\\Events\\Logout' => 
    array (
      0 => 'App\\Listeners\\EndSessionOnLogout@handle',
    ),
    'Illuminate\\Auth\\Events\\Login' => 
    array (
      0 => 'App\\Listeners\\RecordSessionLogin@handle',
    ),
    'Illuminate\\Auth\\Events\\PasswordReset' => 
    array (
      0 => 'App\\Listeners\\RevokeSessionsOnPasswordReset@handle',
    ),
    'App\\Events\\ContactMessageReceived' => 
    array (
      0 => 'App\\Listeners\\SendContactMessageEmail@handle',
    ),
  ),
);