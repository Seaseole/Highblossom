// Bootstrap 4 + jQuery + Popper.js initialization for admin panel
import $ from 'jquery';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import 'popper.js';

window.$ = window.jQuery = $;

document.addEventListener('DOMContentLoaded', () => {
    // Bootstrap 4 tooltips initialization
    $('[data-toggle="tooltip"]').tooltip();

    // Bootstrap 4 toasts
    $('.toast').toast();
});
