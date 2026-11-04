{{--
    The passkeys island, in the bench's voice.

    Two things changed here beyond the surface.

    The name of a key is no longer taken with `prompt()`. That was a browser box with no room to say
    anything useful, no length the person could see before being refused, and on a phone it sat
    under the keyboard it had just raised. It is a row of this block now.

    And the button that starts the ceremony is the one that reports it. `window.Passkeys.register()`
    is not a Livewire call, so the `wire:loading` pair that used to hang off a `wire:target` of
    "register" could never have matched a request: through a fifteen-second wait for a fingerprint
    the control kept looking idle, and a second press calls the package's own `cancel()` on the
    first ceremony.

    Every `wire:*` hook the component itself answers to is unchanged, along with the `data-passkey-*`
    pair the rename handler reads off the closest `li` and the toast events. No name is echoed into a
    JavaScript expression — it is read back off the row, so a passkey called O'Brien cannot break the
    attribute that holds it.
--}}
<div
    class="hb-block"
    x-data="{
        adding: false,
        newName: '',
        busy: false,
        renamingId: null,
        renameValue: '',
        supported: true,

        init() {
            this.supported = typeof window.Passkeys?.isSupported === 'function'
                ? window.Passkeys.isSupported()
                : false;
        },

        /* The package already says the useful part in most cases, and says it as a named error
           class rather than a bare string: NotSupportedError, UserCancelledError,
           PasskeyExistsError, InvalidDomainError. Two of them need a next step added, because
           'this device already has one' and 'this browser cannot hold one' are dead ends unless the
           person is told which door is left. A ceremony somebody cancelled is not a failure and
           should say nothing at all. */
        sayFor(error) {
            if (error.name === 'UserCancelledError') {
                return null;
            }

            if (error.name === 'PasskeyExistsError') {
                return 'This device already holds a passkey for this account. Register from another '
                    + 'device, or rename the one you have and keep using it.';
            }

            if (error.name === 'NotSupportedError') {
                return 'This browser cannot hold a passkey. Use a device with a fingerprint reader, '
                    + 'face unlock, Windows Hello, or a security key.';
            }

            return error.message || 'The passkey was not registered. Try again, or sign in with your password.';
        },

        notify(message) {
            if (message === null) {
                return;
            }

            window.dispatchEvent(new CustomEvent('toast', {
                detail: { type: 'error', message },
            }));
        },

        startAdd() {
            this.adding = true;

            /* x-show reveals on a tick of its own; asking for the caret in the same turn finds a box
               that is still hidden and takes it nowhere. */
            this.$nextTick(() => this.$refs.newNameInput?.focus());
        },

        cancelAdd() {
            this.adding = false;
            this.newName = '';
        },

        async register() {
            const name = this.newName.trim();

            if (! name || this.busy) {
                return;
            }

            this.busy = true;

            try {
                await window.Passkeys.register({ name });

                Livewire.dispatch('passkeyRegistered');

                this.adding = false;
                this.newName = '';

                window.dispatchEvent(new CustomEvent('toast', {
                    detail: { type: 'success', message: 'Passkey added. This device can sign in without the password now.' },
                }));
            } catch (e) {
                console.error('Passkey registration failed', e);
                this.notify(this.sayFor(e));
            } finally {
                this.busy = false;
            }
        },

        startRename(id, currentName) {
            /* Kept as the string the row's `dataset` carries, because every comparison in the
               markup quotes the id. A number here would never equal a string there, and the row
               would show a title and a name box at the same time. */
            this.renamingId = String(id);
            this.renameValue = currentName;

            /* No `x-ref`: this input is printed once per row, and Alpine turns a repeated ref into
               an array, so `focus()` would land on nothing. The id of the row being renamed is
               known, so the box is found by it. */
            this.$nextTick(() => {
                document.getElementById('passkey-rename-' + this.renamingId)?.focus();
            });
        },

        cancelRename() {
            this.renamingId = null;
            this.renameValue = '';
        },

        submitRename(id, currentName) {
            const name = this.renameValue.trim();

            if (! name || name === currentName) {
                this.cancelRename();

                return;
            }

            Livewire.dispatch('renamePasskeyRequest', { id: id, name: name });
            this.cancelRename();
        },

        async remove(id, name) {
            const proceed = await window.adminConfirm({
                title: 'Remove this passkey',
                message: name + ' stops signing in to this account the moment it is removed, and a new '
                    + 'one has to be registered from the device itself. There is no undo.',
                confirm: 'Remove passkey',
                danger: true,
            });

            if (proceed) {
                $wire.deletePasskey(id);
            }
        },
    }"
>
    <div class="hb-block__head">
        <h2 class="hb-block__title">Passkeys</h2>

        <p class="hb-block__meta">
            @if ($passkeys->isNotEmpty())
                <span class="hb-num">{{ $passkeys->count() }} {{ $passkeys->count() === 1 ? 'device' : 'devices' }}</span>
            @endif
        </p>

        <p class="hb-block__note">
            A passkey lives on the device that holds it: a fingerprint, a face, a PIN. Nothing is
            typed, and nothing carries over to another device.
        </p>
    </div>

    <div class="hb-block__body">
        {{-- The bar that opens the form gets out of the way while the form is open. It used to
             keep its own padding and border with nothing left inside it, which read as an empty
             rule across the block. --}}
        <div class="hb-repeat-bar hb-repeat-bar--top" x-show="! adding">
            <button
                type="button"
                @click="startAdd()"
                :disabled="! supported"
                class="hb-btn hb-btn--primary"
            >
                Register a device
            </button>
        </div>

        {{-- Asked before the name, not after it. The old flow was: click, type a name into a browser
             box, and only then be told this device cannot hold a passkey. --}}
        <p class="hb-repeat__empty" x-show="! supported" x-cloak>
            This browser cannot hold a passkey. Sign in with your password here, and register one
            from a device with a fingerprint reader, face unlock, Windows Hello, or a security key.
        </p>

        @if ($passkeys->isEmpty())
            <p class="hb-repeat__empty" x-show="supported">
                No passkeys registered yet. Adding one here lets this device sign in without the
                password.
            </p>
        @endif

        {{-- Deliberately not an `x-sheet.field`: the marks engine reads every `.hb-field` on the
             sheet, and a name typed into a row of this list is not a value waiting to be saved. --}}
        <div class="hb-add-row" x-show="adding" x-cloak>
            <label class="hb-field__label" for="passkey-new-name">Name this passkey</label>

            <div class="hb-cluster">
                <input
                    type="text"
                    id="passkey-new-name"
                    x-ref="newNameInput"
                    x-model="newName"
                    maxlength="255"
                    placeholder="Work laptop, gate tablet, security key"
                    autocomplete="off"
                    class="hb-input"
                    @keydown.enter.prevent="register()"
                    @keydown.escape.window="cancelAdd()"
                />

                <button
                    type="button"
                    @click="register()"
                    :disabled="busy || newName.trim() === ''"
                    :aria-busy="busy ? 'true' : 'false'"
                    class="hb-btn hb-btn--primary"
                >
                    <span x-show="! busy">Add passkey</span>
                    <span x-cloak x-show="busy">Waiting for this device...</span>
                </button>
            </div>

            {{-- The way out of the form lives in the form. It used to sit in the bar above, twelve
                 pixels from the button that opened this row and a hundred and fifty from the row
                 itself, so the control that undoes an action was further from it than the action. --}}
            <div class="hb-add-row__foot">
                <p x-show="! busy">
                    Your device will ask for a fingerprint, a face, or a PIN. The name is only so you know
                    which device it is later, and it can be changed.
                </p>

                <p x-show="busy" x-cloak>
                    Nothing is written until the device answers. Leave this open while it asks.
                </p>

                <button
                    type="button"
                    @click="cancelAdd()"
                    :disabled="busy"
                    class="hb-btn"
                >
                    Cancel
                </button>
            </div>
        </div>

        @if ($passkeys->isNotEmpty())
            <ul class="hb-list">
                @foreach ($passkeys as $passkey)
                    <li
                        class="hb-list__row"
                        data-passkey-id="{{ $passkey->id }}"
                        data-passkey-name="{{ $passkey->name }}"
                    >
                        <span class="hb-list__main">
                            <span class="hb-list__title" x-show="renamingId !== '{{ $passkey->id }}'">
                                {{ $passkey->name }}
                            </span>

                            <input
                                type="text"
                                x-show="renamingId === '{{ $passkey->id }}'"
                                x-cloak
                                id="passkey-rename-{{ $passkey->id }}"
                                x-model="renameValue"
                                maxlength="255"
                                aria-label="New name for {{ $passkey->name }}"
                                autocomplete="off"
                                class="hb-input hb-input--inline"
                                @keydown.enter.prevent="submitRename($el.closest('li').dataset.passkeyId, $el.closest('li').dataset.passkeyName)"
                                @keydown.escape="cancelRename()"
                            />

                            <span class="hb-list__when">
                                @if ($passkey->authenticator)
                                    {{ $passkey->authenticator }}
                                    <span class="hb-sep" aria-hidden="true">·</span>
                                @endif

                                {{-- The date it was added says when a key started working; the date it
                                     was last accepted says whether it still is. Only the first one was
                                     ever printed, so a key nobody had touched in a year looked exactly
                                     like one used this morning, and the only way to find out was to
                                     delete it and try to sign in. --}}
                                @if ($passkey->last_used_at)
                                    Last used
                                    <time class="hb-num" datetime="{{ $passkey->last_used_at->toIso8601String() }}">
                                        {{ $passkey->last_used_at->diffForHumans() }}
                                    </time>
                                @else
                                    Never used
                                @endif

                                <span class="hb-sep" aria-hidden="true">·</span>

                                Added
                                <time class="hb-num" datetime="{{ $passkey->created_at->toIso8601String() }}">
                                    {{ $passkey->created_at->format('M j, Y') }}
                                </time>
                            </span>
                        </span>

                        <span class="hb-list__end">
                            <button
                                type="button"
                                @click="startRename($el.closest('li').dataset.passkeyId, $el.closest('li').dataset.passkeyName)"
                                x-show="renamingId !== '{{ $passkey->id }}'"
                                class="hb-btn"
                            >
                                Rename
                            </button>

                            <button
                                type="button"
                                @click="submitRename($el.closest('li').dataset.passkeyId, $el.closest('li').dataset.passkeyName)"
                                x-show="renamingId === '{{ $passkey->id }}'"
                                x-cloak
                                class="hb-btn hb-btn--primary"
                            >
                                Save
                            </button>

                            <button
                                type="button"
                                @click="cancelRename()"
                                x-show="renamingId === '{{ $passkey->id }}'"
                                x-cloak
                                class="hb-btn"
                            >
                                Cancel
                            </button>

                            {{-- The ask lives in `remove()` now, on purpose: `wire:confirm` reads as a
                                 first-party dialog but is the Livewire bundle calling `window.confirm`,
                                 which is the browser box this tab has otherwise done without. --}}
                            <button
                                type="button"
                                @click="remove($el.closest('li').dataset.passkeyId, $el.closest('li').dataset.passkeyName)"
                                class="hb-btn hb-btn--danger"
                                wire:loading.attr="disabled"
                                wire:target="deletePasskey"
                            >
                                <span wire:loading.remove wire:target="deletePasskey">Remove</span>
                                <span x-cloak wire:loading wire:target="deletePasskey">Removing...</span>
                            </button>
                        </span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
