<script>
(function () {
    var endpoint = <?php echo json_encode(route('jobs-monitor.workers.summary'), 15, 512) ?>;
    var intervalMs = <?php echo json_encode($vm->silentAfterSeconds, 15, 512) ?> * 1000;
    var container = document.getElementById('workers-live');

    if (!container) return;

    function refresh() {
        if (document.hidden) return;

        fetch(endpoint, { headers: { 'Accept': 'text/html' } })
            .then(function (r) { return r.ok ? r.text() : Promise.reject(r); })
            .then(function (html) {
                container.innerHTML = html;

                if (typeof lucide !== 'undefined' && lucide.createIcons) {
                    lucide.createIcons();
                }
            })
            .catch(function () { /* keep previous content on error */ });
    }

    function start() {
        setInterval(refresh, intervalMs);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', start);
    } else {
        start();
    }
})();
</script>
<?php /**PATH C:\laragon\www\Highblossom\vendor\romalytar\yammi-jobs-monitoring-laravel\resources\views\partials\workers-auto-refresh.blade.php ENDPATH**/ ?>