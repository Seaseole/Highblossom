<?php

return [
    // User messages
    'user_created' => 'User created successfully.',
    'user_updated' => 'User updated successfully.',
    'user_deleted' => 'User deleted successfully.',

    // Testimonial messages
    'testimonial_created' => 'Testimonial created successfully.',
    'testimonial_updated' => 'Testimonial updated successfully.',
    'testimonial_deleted' => 'Testimonial deleted successfully.',

    // Service messages
    'service_created' => 'Service created successfully.',
    'service_updated' => 'Service updated successfully.',
    'service_deleted' => 'Service deleted successfully.',

    // Service Type messages
    'service_type_created' => 'Service type created successfully.',
    'service_type_updated' => 'Service type updated successfully.',
    'service_type_deleted' => 'Service type deleted successfully.',

    // SEO messages
    'seo_saved' => 'SEO settings saved successfully.',
    'seo_created' => 'SEO configuration created successfully.',

    // Cache management messages
    'cache_group_cleared' => ':group cache cleared.',
    'cache_group_optimized' => ':group cache optimized.',
    'cache_all_cleared' => 'All caches cleared.',
    'cache_all_optimized' => 'All caches optimized.',

    // System overview messages
    'system_overview_refreshed' => 'System overview re-measured.',
    'system_overview_probe_failed' => 'This section could not be measured. See the application log.',
    'system_overview_not_warmed' => 'No measurement taken yet.',
    'system_overview_measure_now' => 'Measure now',
    'system_overview_measuring' => 'Measuring…',
    'system_overview_warming' => 'Waiting for a worker to finish measuring.',
    'system_overview_warm_in_background' => 'Warm in background',
    'system_overview_prune_now' => 'Prune now',
    // Read inside a single-quoted confirm() in an HTML attribute, so it carries no apostrophe.
    'system_overview_prune_confirm' => 'Delete expired session rows and close the sign-ins that lapsed? Anyone holding an expired session will have to sign in again.',
    'system_overview_pruned' => 'Deleted :deleted expired session row(s) and closed :closed sign-in(s).',
    'system_overview_heavy_hint' => 'This section walks the file system, so it is measured outside the page request.',
    'system_overview_measured' => 'Measured in :ms ms · :when',
    'system_overview_never_measured' => 'Not measured yet.',
    'system_overview_all_ok' => 'Every measured section is healthy.',
    'system_overview_export' => 'Export JSON',
    'system_overview_attention' => ':count section needs attention|:count sections need attention',
    'system_overview_packages_title' => 'Package inventory',
    'system_overview_packages_hint' => 'Searched from the list cached when this section was measured.',
    'system_overview_packages_search_placeholder' => 'Search packages by name or description',
    'system_overview_packages_clear' => 'Clear the package search',
    'system_overview_packages_source_label' => 'Filter packages by lock file',
    'system_overview_packages_source_all' => 'All',
    'system_overview_packages_source_composer' => 'Composer',
    'system_overview_packages_source_npm' => 'npm',
    'system_overview_packages_empty' => 'No package matches that search.',
    'system_overview_packages_loading' => 'Reading the package list…',

    // Booking messages
    'booking_created' => 'Booking created successfully.',
    'booking_updated' => 'Booking updated successfully.',
    'booking_deleted' => 'Booking deleted successfully.',

    // Quote messages
    'quote_created' => 'Quote request submitted successfully.',
    'quote_updated' => 'Quote updated successfully.',
    'quote_deleted' => 'Quote deleted successfully.',

    // Gallery messages
    'gallery_created' => 'Gallery item created successfully.',
    'gallery_updated' => 'Gallery item updated successfully.',
    'gallery_deleted' => 'Gallery item deleted successfully.',

    // Gallery category messages
    'gallery_category_created' => 'Gallery category created successfully.',
    'gallery_category_updated' => 'Gallery category updated successfully.',
    'gallery_category_deleted' => 'Gallery category deleted successfully.',

    // Category messages
    'category_created' => 'Category created successfully.',
    'category_updated' => 'Category updated successfully.',
    'category_deleted' => 'Category deleted successfully.',

    // Glass sub-category messages
    'glass_sub_category_created' => 'Glass sub-category created successfully.',
    'glass_sub_category_updated' => 'Glass sub-category updated successfully.',
    'glass_sub_category_deleted' => 'Glass sub-category deleted successfully.',

    // Tag messages
    'tag_created' => 'Tag created successfully.',
    'tag_updated' => 'Tag updated successfully.',
    'tag_deleted' => 'Tag deleted successfully.',

    // Role messages
    'role_created' => 'Role created successfully.',
    'role_updated' => 'Role updated successfully.',
    'role_deleted' => 'Role deleted successfully.',

    // Glass Type messages
    'glass_type_created' => 'Glass type created successfully.',
    'glass_type_updated' => 'Glass type updated successfully.',
    'glass_type_deleted' => 'Glass type deleted successfully.',

    // Appointment messages
    'inspection_created' => 'Appointment created successfully.',
    'inspection_updated' => 'Appointment updated successfully.',
    'inspection_deleted' => 'Appointment deleted successfully.',

    // Absence messages
    'absence_created' => 'Absence recorded successfully.',
    'absence_updated' => 'Absence updated successfully.',
    'absence_deleted' => 'Absence deleted successfully.',

    // Contact Message messages
    'contact_message_created' => 'Contact message sent successfully.',
    'contact_message_updated' => 'Contact message updated successfully.',
    'contact_message_deleted' => 'Contact message deleted successfully.',
    'contact_message_marked_read' => 'Message marked as read.',

    // Profile messages
    'profile_updated' => 'Profile updated successfully.',

    // Settings messages
    'settings_saved' => 'Settings saved successfully.',

    // Media Library messages
    'media_uploaded' => 'Media uploaded successfully.',
    'media_deleted' => 'Media deleted successfully.',

    // General messages
    'success' => 'Operation completed successfully.',
    'error' => 'An error occurred. Please try again.',
    'not_found' => 'Resource not found.',
    'unauthorized' => 'You are not authorized to perform this action.',

    // Profile messages
    'profile_information_updated' => 'Profile information updated successfully.',
    'appearance_updated' => 'Appearance settings updated successfully.',
    'password_updated' => 'Password updated successfully.',
    'two_factor_enabled' => 'Two-factor authentication enabled successfully.',
    'two_factor_disabled' => 'Two-factor authentication disabled successfully.',
    'recovery_codes_regenerated' => 'Recovery codes regenerated successfully.',
    'account_deleted' => 'Account deleted successfully.',

    // Quote messages
    'quote_status_updated' => 'Quote status updated successfully.',

    // Post messages
    'post_created' => 'Post created successfully.',
    'post_updated' => 'Post updated successfully.',
    'post_deleted' => 'Post deleted successfully.',
    'post_published' => '":title" is now live.',
    'post_unpublished' => '":title" has been moved back to draft.',
    'post_archived' => '":title" has been archived and removed from the site.',
    'posts_publish_blocked' => 'Cannot publish — this post is still missing: :fields.',
    'posts_publish_blocked_bulk' => 'Cannot publish :count of the selected posts — open each one to add its excerpt, category and content.',
    'posts_status_updated' => ':count posts set to :status.',
];
