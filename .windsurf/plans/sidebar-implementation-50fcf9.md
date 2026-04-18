# Plan for Sidebar and Menu Implementation in Laravel 13

The goal is to implement a structured sidebar with sub-menus using Flux UI components, adhering to the standard Laravel 13 starter kit patterns.

## Recommended Actions

- **Analyze Sidebar Component:** Review `@/resources/views/layouts/app/sidebar.blade.php` to understand how `flux:sidebar` and `flux:sidebar.nav` are currently used.
- **Implement Sub-menus:** Utilize `flux:navlist.group` with the `expandable` prop (as seen in `@/resources/views/flux/navlist/group.blade.php`) to create collapsible menu sections for "Bookings" and "Content".
- **Organize Navigation Groups:** Group related items together:
    - **Bookings Group:** Create Booking, Manage Bookings, Inspections.
    - **Content Group:** Pages, Content Blocks.
- **Dynamic Active States:** Use `:current="request()->routeIs(...)"` to highlight the active menu item based on the current route.
- **Consistency with Starter Kit:** Maintain the standard Flux UI layout patterns (collapsible sidebar, mobile header) already present in the codebase.

## Proposed Structure

- **Dashboard** (Item)
- **Bookings** (Expandable Group)
    - New Booking
    - Manage Bookings
    - Inspections
    - Staff Absences
- **Content** (Expandable Group)
    - Manage Pages
    - Content Blocks
- **Settings** (Item - linking to existing settings layout)
