<?php

return [
    'label' => 'Book an Appointment',
    'title' => 'Book your glass fitting',
    'description' => 'Tell us the vehicle, pick a slot that works, and we will fit the glass at the workshop or come to you.',

    // Wizard steps
    'step_vehicle' => 'Vehicle & service',
    'step_date_time' => 'Date & time',
    'step_details' => 'Your details',
    'step_review' => 'Review & confirm',

    // Step 1 — what needs fitting
    'service_heading' => 'What needs attention?',
    'service_description' => 'This sets how long the job takes, so it decides which slots you are offered.',
    'service_windscreen' => 'Windscreen',
    'service_side_rear' => 'Side or rear glass',
    'service_heavy_machinery' => 'Heavy machinery',
    'service_fleet' => 'Fleet / multiple vehicles',

    'damage_heading' => 'What is the damage?',
    'damage_chip' => 'Chip or stone strike',
    'damage_crack' => 'Crack',
    'damage_shattered' => 'Shattered / needs replacement',
    'damage_seal_leak' => 'Leaking seal or wind noise',
    'damage_other' => 'Something else',

    'vehicle_heading' => 'Which vehicle?',
    'vehicle_make_label' => 'Make',
    'vehicle_make_placeholder' => 'Toyota',
    'vehicle_model_label' => 'Model',
    'vehicle_model_placeholder' => 'Hilux',
    'vehicle_year_label' => 'Year',
    'vehicle_year_placeholder' => '2020',

    'location_heading' => 'Where should we fit it?',
    'location_mobile' => 'Mobile fitting',
    'location_mobile_hint' => 'We come to your home, office or yard.',
    'location_workshop' => 'Workshop',
    'location_workshop_hint' => 'Gaborone West Industrial Site.',
    'address_label' => 'Where is the vehicle parked?',
    'address_placeholder' => 'Plot 123, Gaborone North',

    // Step 2 — the picker
    'select_date' => 'Pick a day',
    'select_time' => 'Pick a time',
    'quick_pick' => 'Jump to',
    'quick_today' => 'Today',
    'quick_tomorrow' => 'Tomorrow',
    'quick_this_week' => 'This week',
    'quick_earliest' => 'Earliest available',
    'no_upcoming_days' => 'Nothing is bookable right now. Please call us and we will sort something out.',

    'days_available_one' => ':count day available this month',
    'days_available_other' => ':count days available this month',
    'month_fully_booked' => 'No days left this month',
    'slot_count_one' => ':count time available',
    'slot_count_other' => ':count times available',

    'day_reason_past' => 'Already passed',
    'day_reason_closed' => 'We are closed',
    'day_reason_full' => 'Fully booked',
    'day_reason_loading' => 'Checking availability',
    'previous_month' => 'Previous month',
    'next_month' => 'Next month',

    'weekday_mon' => 'Mo',
    'weekday_tue' => 'Tu',
    'weekday_wed' => 'We',
    'weekday_thu' => 'Th',
    'weekday_fri' => 'Fr',
    'weekday_sat' => 'Sa',
    'weekday_sun' => 'Su',

    'period_morning' => 'Morning',
    'period_afternoon' => 'Afternoon',
    'selected' => 'Selected',
    'day_closed' => 'We are closed on this day. Please choose another.',
    'no_slots_available' => 'Every slot on this day is taken. Try the next one.',
    'earliest_available' => 'Earliest you can book:',
    'slots_load_failed' => 'We could not load the available times for this date.',
    'retry' => 'Try again',
    'loading_times' => 'Loading times…',
    'duration_note' => 'The job runs for about :minutes minutes. The end time is worked out from that, you do not pick it.',
    'ends_at' => 'ends :time',

    'slot_reason_too_late' => 'Too late to book',
    'slot_reason_taken' => 'Already booked',
    'slot_reason_closed' => 'Closed',

    'slot_closed_day' => 'We are closed on that day. Please choose another date.',
    'slot_too_late' => 'That time is too soon to book. Please choose a later time or another day.',
    'slot_already_taken' => 'Someone has just taken that slot. Please pick another time — everything else you entered is still here.',

    // Step 3 — who to contact
    'your_information' => 'How do we reach you?',
    'your_info_description' => 'We will confirm the fitting and send a reminder the day before.',
    'full_name_label' => 'Full name',
    'full_name_placeholder' => 'Lesego Kgositsile',
    'phone_number_label' => 'Phone number',
    'phone_placeholder' => '267 7X XXX XXX',
    'email_address_label' => 'Email address',
    'email_placeholder' => 'you@example.com',
    'prefers_whatsapp' => 'Message me on WhatsApp instead of calling',
    'prefers_whatsapp_hint' => 'We will send the confirmation and the reminder there.',
    'notes_label' => 'Anything else we should know?',
    'notes_placeholder' => 'Registration number, insurer, ADAS camera, parking access…',
    'photo_label' => 'Photo of the damage',
    'photo_hint' => 'Optional. JPG, PNG or WebP up to 5MB. It helps us bring the right glass.',
    'photo_choose' => 'Choose a photo',
    'photo_change' => 'Choose another',
    'photo_none' => 'No file chosen',

    // Step 4 — review
    'review_heading' => 'Check it over',
    'review_description' => 'Nothing is booked until you confirm. Use edit to change any part.',
    'review_vehicle' => 'Vehicle & service',
    'review_when' => 'When',
    'review_where' => 'Where',
    'review_contact' => 'Contact',
    'review_notes' => 'Notes',
    'review_photo' => 'Damage photo',
    'review_no_notes' => 'None',
    'edit' => 'Edit',
    'edit_aria' => 'Edit :section',

    // Navigation
    'submit_booking' => 'Confirm booking',
    'submit_disclaimer' => 'We will confirm the slot before we do any work.',
    'next' => 'Continue',
    'back' => 'Back',
    'book_another' => 'Book another',
    'submitting' => 'Booking…',
    'step_of' => 'Step :step of :total',

    // Summary rail
    'summary_title' => 'Your booking',
    'summary_empty' => 'Nothing chosen yet.',
    'summary_not_set' => 'Not chosen yet',
    'summary_show' => 'Show summary',
    'summary_hide' => 'Hide summary',

    // Confirmation extras
    'add_to_calendar' => 'Add to calendar',
    'confirm_on_whatsapp' => 'Confirm on WhatsApp',
    'whatsapp_message' => 'Hello Highblossom, I have just booked appointment :reference for :service on :when. Please confirm.',
    'reference_number' => 'Reference',
];
