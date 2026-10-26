{{-- The cross-module "new" control. It opens the create dialog (x-admin.create-dialog) rather
     than holding a list of its own, so there is one place that knows what can be created, and the
     account menu's phone row opens the very same panel. The wrapper hides it below `sm` because
     the phone bar is already full of 44px targets; the account menu carries the fallback there.
     `$el` is handed to the store so Escape can return focus to this button and not to the body. --}}
<div class="hidden shrink-0 sm:block">
    <button
        type="button"
        @click="$store.adminCreate.show($el)"
        :aria-expanded="$store.adminCreate.open.toString()"
        aria-haspopup="dialog"
        aria-controls="admin-create-dialog"
        aria-label="Create something new"
        title="Create"
        class="admin-bar-btn inline-flex"
    >
        <x-ui.icon name="plus" size="sm" />
    </button>
</div>
