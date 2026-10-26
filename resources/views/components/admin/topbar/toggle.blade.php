{{-- One control for both breakpoints. The shell used to carry two: a hamburger in the fixed
     mobile bar and a floating pill on the rail, each answering to a different store, which is
     how a collapsed rail and an open drawer could be remembered as the same thing by a person
     who had only ever pressed one button. The id is the one $store.mobileMenu.close() returns
     focus to, so the drawer's focus handoff keeps working untouched. --}}
<button
    id="mobile-menu-btn"
    type="button"
    x-data="adminSidebarToggle"
    @click="toggle()"
    class="admin-bar-btn inline-flex"
    :aria-label="label()"
    :aria-expanded="open() ? 'true' : 'false'"
    :aria-controls="isDesktop() ? 'desktop-sidebar' : 'sidebar-panel'"
    title="Toggle navigation"
>
    {{-- The icon is the rail's own verb (collapse inward) on desktop and the drawer's (open
         outward) on mobile, so the glyph describes what the press does rather than what state
         the page is in. --}}
    <x-ui.icon name="bars-3" size="sm" class="lg:hidden" />
    <span class="hidden inline-flex lg:inline-flex" :class="collapsed() ? 'rotate-180' : ''">
        <x-ui.icon name="chevron-double-left" size="xs" />
    </span>
</button>
