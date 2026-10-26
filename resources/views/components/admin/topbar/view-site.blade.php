{{-- rel="noopener" is not decoration: a tab that can reach window.opener can rewrite the admin
     page you left behind. The sidebar logo still links home in the same tab; this is the one
     that says "look at what the public sees" without losing where you were. --}}
<a
    href="/"
    target="_blank"
    rel="noopener noreferrer"
    class="admin-bar-btn inline-flex"
    aria-label="View the public site in a new tab"
    title="View site"
>
    <x-ui.icon name="arrow-top-right-on-square" size="sm" />
</a>
