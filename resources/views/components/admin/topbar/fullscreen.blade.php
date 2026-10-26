{{-- Fullscreen is a browser state, not a page one, so nothing here is seeded from the server:
     the store reads document.fullscreenElement, and the fullscreenchange listener keeps both
     this button and the copy inside the account menu in step with a press of Esc, which leaves
     full screen without asking anyone's permission. --}}
<button
    type="button"
    @click="$store.fullscreen.toggle()"
    class="admin-bar-btn hidden sm:inline-flex"
    :aria-label="$store.fullscreen.on ? 'Exit full screen' : 'Enter full screen'"
    :aria-pressed="$store.fullscreen.on ? 'true' : 'false'"
    title="Full screen"
>
    <x-ui.icon name="arrows-pointing-out" size="sm" x-show="! $store.fullscreen.on" />
    <x-ui.icon name="arrows-pointing-in" size="sm" x-show="$store.fullscreen.on" />
</button>
