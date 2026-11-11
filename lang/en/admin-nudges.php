<?php

declare(strict_types=1);

return [
    // Rule messages. Phrased at scan time and stored on the row, so they avoid
    // figures that would go stale between scans (exact day counts, ages).
    'quotes_stale' => '{1} A quote request has waited more than two days for a reply.|[2,*] :count quote requests have waited more than two days for a reply.',
    'bookings_unconfirmed' => '{1} A booking in the next :days days is still unconfirmed.|[2,*] :count bookings in the next :days days are still unconfirmed.',
    'drafts_stale' => '{1} A draft has not been touched in over a week.|[2,*] :count drafts have not been touched in over a week.',
    'seo_missing' => '{1} One page is missing its SEO title or description.|[2,*] :count pages are missing their SEO title or description.',
    'services_no_image' => '{1} An active service has no image.|[2,*] :count active services have no image.',
    'gallery_missing_description' => '{1} A gallery image has no description.|[2,*] :count gallery images have no description.',
    'blog_quiet' => 'The blog has gone quiet — no new post in over a month.',

    // One-click action labels.
    'review_quotes' => 'Review quotes',
    'review_bookings' => 'Review bookings',
    'review_drafts' => 'Review drafts',
    'configure_seo' => 'Configure SEO',
    'review_services' => 'Review services',
    'review_gallery' => 'Review gallery',
    'write_post' => 'Write a post',

    // Surface chrome.
    'title' => 'Suggestions',
    'hint' => 'Small things worth a look, measured by the scanner. Dismiss or snooze anything that can wait.',
    'dismiss' => 'Dismiss',
    'snooze' => 'Snooze',
    'dismissed' => 'Suggestion dismissed.',
    'snoozed' => 'Suggestion snoozed for a few days.',
];
