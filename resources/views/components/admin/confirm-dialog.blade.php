{{--
    One question, asked in this design's words.

    Every destructive control on the desk used to answer for itself: the sheet's two-factor block
    wrote `confirm()` inline in an `onsubmit`, its Alpine code called `confirm()` again for
    regenerating codes, and the passkeys island used `wire:confirm`, which the Livewire bundle
    resolves to `window.confirm` as well. Three idioms, one of which is a system sheet on a phone,
    none of which read as this product, and all of them blocking — the browser halts the whole
    page while it waits, so a person who was mid-way through a form loses nothing but gains no
    context either.

    This is the same ask in the desk's own panel. It is driven from an Alpine store rather than a
    local flag because the things that need to ask do not share a scope: the profile sheet's root,
    the passkeys island inside one of its panes, and plain POST forms that have no Alpine at all.
    `window.adminConfirm({...})` answers with a promise, and `form[data-confirm]` is caught by a
    delegated listener in admin.js, so a form can be made to ask by giving it an attribute.

    Focus is not handled here. `x-dialog` does it: Tab stays inside, Escape settles the question as
    a refusal through `data-dialog-close` rather than by dropping the flag — a caller waiting on
    the promise would otherwise never be answered — and focus goes back to the control that asked.
--}}

<div
    x-show="$store.adminConfirm.open"
    x-cloak
    class="hb-modal"
>
    <div
        x-dialog="$store.adminConfirm.open"
        data-dialog-close="$store.adminConfirm.settle(false)"
        x-show="$store.adminConfirm.open"
        role="alertdialog"
        aria-modal="true"
        aria-labelledby="admin-confirm-title"
        aria-describedby="admin-confirm-lede"
        class="hb-modal__panel"
        @click.outside="$store.adminConfirm.settle(false)"
    >
        <h3 id="admin-confirm-title" class="hb-modal__title" x-text="$store.adminConfirm.title"></h3>

        <p id="admin-confirm-lede" class="hb-modal__body" x-text="$store.adminConfirm.message"></p>

        <div class="hb-modal__foot">
            {{-- Initial focus is on the refusal, not the action. This panel is used for the things
                 that cannot be undone — signing every other device out, switching two-factor off,
                 deleting a passkey — and a person who answers it with a hand on the Enter key has
                 already decided before they read it. The confirm button is one Tab away. --}}
            <button
                type="button"
                data-dialog-initial
                @click="$store.adminConfirm.settle(false)"
                class="hb-btn"
                x-text="$store.adminConfirm.cancelLabel || 'Cancel'"
            ></button>

            <button
                type="button"
                @click="$store.adminConfirm.settle(true)"
                :class="$store.adminConfirm.danger ? 'hb-btn hb-btn--danger' : 'hb-btn hb-btn--primary'"
                x-text="$store.adminConfirm.confirmLabel || 'Continue'"
            ></button>
        </div>
    </div>
</div>
