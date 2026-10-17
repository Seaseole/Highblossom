<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Session Ledger
    |--------------------------------------------------------------------------
    |
    | Controls the `user_sessions` ledger that powers the device lists and the
    | admin session overview. Kept in a config file rather than company settings
    | because these are operational knobs, not user-editable content.
    |
    */

    // How long closed sessions stay in the ledger before the prune command removes them.
    'history_retention_days' => (int) env('USER_SESSIONS_RETENTION_DAYS', 90),

    // Minimum seconds between `last_seen_at` writes for one session, so a busy
    // admin does not issue an UPDATE on every single request.
    'touch_throttle_seconds' => (int) env('USER_SESSIONS_TOUCH_THROTTLE_SECONDS', 60),

    // A ledger row whose framework row is missing is only called orphaned once it
    // has been that way for this long. Without the grace window a prune running
    // while a request is still being served would close a session that is about
    // to have its framework row written at the end of that same request.
    'orphan_grace_minutes' => (int) env('USER_SESSIONS_ORPHAN_GRACE_MINUTES', 10),

    // Revoke every other session for a user whenever their password changes.
    'revoke_on_password_change' => (bool) env('USER_SESSIONS_REVOKE_ON_PASSWORD_CHANGE', true),

    // Rows per page on the admin session lists.
    'per_page' => (int) env('USER_SESSIONS_PER_PAGE', 25),

    // Session key used to hand login metadata from the Login event to the
    // tracking middleware, which runs after the framework regenerates the
    // session id. Survives regeneration because regeneration preserves payload.
    'handoff_key' => 'user_session_ledger',

];
