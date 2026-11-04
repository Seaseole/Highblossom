<?php

return [
    'title' => 'Dashboard',
    'welcome' => 'Today, measured against the hours the shop actually keeps.',

    // Masthead: the day as a measured block
    'day_in_progress' => 'In the day',
    'day_before_open' => 'Before opening',
    'day_after_close' => 'After closing',
    'day_closed' => 'Closed today',
    'next_open' => 'Next open day',
    'open_label' => 'Opens',
    'close_label' => 'Closes',
    'window_label' => 'Trading hours',
    'left_label' => 'Left in the day',
    'clock_label' => 'Local time',

    // The rail
    'fittings_today' => 'Fittings today',
    'rail_hint' => 'A span is exactly as long as the job takes. Nothing on this rail is placed at a time the booking form would have refused.',
    'lane_workshop' => 'Bay',
    'lane_workshop_hint' => 'Fitted at the Gaborone West workshop',
    'lane_mobile' => 'On site',
    'lane_mobile_hint' => 'Fitted at the customer by a mobile team',
    'capacity' => 'pools',
    'utilisation' => 'held',
    'busy' => 'on the glass',
    'queued' => 'queued',
    'late' => 'late',
    'rail_empty_bay' => 'No job is holding the bay today. The last slot still starts early enough to finish before closing.',
    'rail_empty_mobile' => 'No team is on the road today. Mobile slots are still open.',
    'rail_closed' => 'The shop is closed today, so there is no scale to lay jobs on. These are recorded against this date anyway.',
    'next_job' => 'Next on the rail',
    'nothing_next' => 'Nothing left to start',
    'cancelled_today' => 'cancelled today',
    'runs_past_close' => ':countm past closing',
    'starts_before_open' => 'Starts before opening',

    // Span states
    'state_legend' => 'What a span can be',
    'state' => [
        'in_progress' => 'Fitting now',
        'waiting' => 'Not confirmed',
        'confirmed' => 'Confirmed',
        'overdue' => 'Overdue',
        'completed' => 'Fitted',
        'cancelled' => 'Cancelled',
    ],

    // Attention queue
    'waiting_on_you' => 'Waiting on you',
    'waiting_hint' => 'Oldest first. A reply is what moves a row off this list.',
    'waiting_empty' => 'Nothing is waiting. Every quote has had a reply, no job is running past its slot, and the inbox is read.',
    'quotes_waiting' => 'Quotes awaiting a reply',
    'inspections_open' => 'Appointments started and never closed',
    'unread_messages' => 'Unread messages',
    'quote' => 'Quote',
    'inspection' => 'Appointment',
    'message' => 'Message',
    'still_to_start' => 'still to start',
    'today_flag' => 'Today',
    'since' => 'Since',
    'bookings_window' => 'bookings created in :days days',
    'mix_empty' => 'No bookings have been created in the last :days days.',
    'team_empty' => 'No appointments were scheduled in the last :days days.',
    'quote_mobile' => 'Wants mobile fitting',
    'quote_workshop' => 'Coming to the workshop',
    'aged' => 'Waiting',
    'open_row' => 'Open',

    // Quote pipeline
    'quote_pipeline' => 'Where the quotes stand',
    'all_quotes' => 'Requests received',
    'still_open' => 'Still open',
    'arrivals' => 'Arrivals, last :days days',
    'arrivals_hint' => ':total requests over :days days, :peak on the busiest day.',
    'average_per_day' => 'a day',
    'quiet_days' => 'days with nothing',
    'busiest_day' => 'Busiest',

    // Ledger figures
    'total_bookings' => 'Bookings',
    'active_users' => 'Accounts',
    'fitted' => 'Jobs fitted',
    'no_change' => 'no change',
    'per_week' => 'vs previous 7 days',
    'this_week' => 'last 7 days',

    // Seven days ahead
    'seven_days' => 'Seven days ahead',
    'week_hint' => 'Scheduled fittings, including today. A closed day is one the shop has marked in its own calendar.',
    'closed_day' => 'Closed',
    'jobs' => 'jobs',
    'job' => 'job',

    // What we fit
    'what_we_fit' => 'What we fit',
    'mix_hint' => 'Bookings created in the last :days days, by service. Each duration is the one the calendar gives that service today.',
    'service_unspecified' => 'No service chosen',
    'on_glass' => 'on the glass',

    // Team load
    'who_carried_the_week' => 'Who carried the week',
    'team_hint' => 'Appointments scheduled in the last :days days. Unassigned work is a job nobody owns yet.',
    'unassigned' => 'Unassigned',
    'share' => 'share',
    'finished' => 'closed',
    'open_jobs' => 'still open',

    // Milestones
    'milestones' => 'Milestones, last :hours hours',
    'milestones_hint' => 'Customer-visible changes to a booking, newest first. Internal remarks stay on the inspection.',
    'no_milestones' => 'No booking changed state in the last :hours hours.',
    'milestone' => [
        'received' => 'Received',
        'confirmed' => 'Confirmed',
        'rescheduled' => 'Rescheduled',
        'started' => 'Started',
        'completed' => 'Completed',
        'cancelled' => 'Cancelled',
    ],

    // Colophon
    'measured_at' => 'Read at',
    'not_cached' => 'Nothing on this page is cached. Every figure was counted for this visit.',
    'source_bookings' => 'bookings',
    'source_quotes' => 'quote requests',
    'source_milestones' => 'booking milestones',
    'sources' => 'Counted from :sources.',
    'system_overview' => 'System Overview',

    // Existing stat labels, kept so the old keys stay resolvable
    'bookings' => 'Bookings',
    'inspections' => 'Appointments',
    'pending_inspections' => 'Pending appointments',
    'users' => 'Users',
    'quotes' => 'Quotes',
    'pending' => 'Pending',
    'contacted' => 'Contacted',
    'completed' => 'Completed',
    'cancelled' => 'Cancelled',
    'recent_activity' => 'Recent Activity',
    'view_all' => 'View all',
    'no_recent_activity' => 'No recent activity',
    'activity_will_appear' => 'Activity will appear here as you use the system',
];
