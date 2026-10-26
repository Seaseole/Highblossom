<span {{ $attributes->merge(['class' => 'admin-bar-kbd']) }}>
    {{-- Two spellings of the same key and CSS picks one, which is the only honest way to name a
         Mac without reading the user agent. html.os-mac is set by the pre-paint seed in the admin
         layout, so the right glyph is on screen before the first frame — no flash of "Ctrl". --}}
    <span class="hidden os-mac:inline">⌘</span><span class="os-mac:hidden">Ctrl</span> K
</span>
