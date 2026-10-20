{{--
    Public site theme bootstrap. Runs before first paint so there is no flash of the
    wrong theme. Deliberately separate from the admin 'theme' localStorage key: the
    admin layout force-writes that key from the user's database preference on every
    admin page load, which would clobber an anonymous visitor's choice.
--}}
<script>
    (function () {
        var KEY = 'hb-theme';
        var root = document.documentElement;
        var media = window.matchMedia('(prefers-color-scheme: dark)');

        function read() {
            try {
                var stored = localStorage.getItem(KEY);
                return stored === 'light' || stored === 'dark' || stored === 'system' ? stored : 'system';
            } catch (e) {
                return 'system';
            }
        }

        function resolve(mode) {
            return mode === 'system' ? (media.matches ? 'dark' : 'light') : mode;
        }

        function apply() {
            var mode = read();
            var resolved = resolve(mode);
            root.classList.toggle('dark', resolved === 'dark');
            root.setAttribute('data-theme', mode);
            root.style.colorScheme = resolved;
            var meta = document.querySelector('meta[name="theme-color"]');
            if (meta) {
                meta.setAttribute('content', resolved === 'dark' ? '#0a0a0f' : '#f7f5f3');
            }
        }

        window.highblossomTheme = {
            get: read,
            resolved: function () {
                return resolve(read());
            },
            set: function (mode) {
                try {
                    localStorage.setItem(KEY, mode);
                } catch (e) {
                    /* private mode: still apply for this visit */
                }
                apply();
            },
            apply: apply,
        };

        media.addEventListener('change', function () {
            if (read() === 'system') apply();
        });

        // wire:navigate keeps <html>, so the class survives SPA navigation; this is a
        // cheap guard against anything that ever re-renders the root element.
        document.addEventListener('livewire:navigated', apply);

        apply();
    })();
</script>
