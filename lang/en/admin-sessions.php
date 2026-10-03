<?php

return [
    'title' => 'Sessions',
    'nav' => 'Sessions',
    'heading' => 'Sessions & devices',

    // Table headers
    'user' => 'User',
    'device' => 'Device',
    'status' => 'Status',
    'signed_in' => 'Signed in',
    'last_seen' => 'Last seen',
    'ended' => 'Ended',
    'method' => 'Method',
    'address' => 'IP address',
    'actions' => 'Actions',

    // Filters
    'search_placeholder' => 'Search by name or email...',
    'all_statuses' => 'All statuses',
    'status_active' => 'Active',
    'status_ended' => 'Ended',
    'all_devices' => 'All devices',
    'from_label' => 'From',
    'to_label' => 'To',
    'filter' => 'Filter',
    'reset' => 'Reset',

    // Buttons
    'revoke' => 'Sign out',
    'revoke_others' => 'Sign out other devices',
    'revoke_all' => 'Sign out every session',

    // Messages
    'session_revoked' => 'That device has been signed out.',
    'sessions_revoked' => 'Signed out of :count other device(s).',
    'sessions_revoked_by_admin' => 'Signed out :count session(s) for this user.',
    'cannot_revoke_current' => 'This is the device you are using now. Use Sign out to end this session.',
    'no_sessions_found' => 'No sessions match these filters.',
    'no_user_sessions' => 'No sessions recorded for this user yet.',
];
