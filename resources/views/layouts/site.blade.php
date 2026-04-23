<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <x-seo.meta />
    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">
    <link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/48.0.1/ckeditor5.css" />
    <script src="https://cdn.ckeditor.com/ckeditor5/48.0.1/ckeditor5.umd.js"></script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600&family=Cabinet+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;900&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        .glass-card {
            background: #16161d;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
        }
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .primary-gradient {
            background: linear-gradient(135deg, #73081d 0%, #a93440 100%);
        }
        .swiper {
            width: 100%;
            padding-bottom: 40px;
        }
        .swiper-pagination-bullet-active {
            background-color: #73081d;
        }
        .text-wrap-balance {
            text-wrap: balance;
        }
        .text-wrap-pretty {
            text-wrap: pretty;
        }
    </style>
</head>
<body class="bg-[#0A0A0F] text-[#FAFAFA] font-body selection:bg-[#DC2626] selection:text-white antialiased">
    @include('partials.site-nav')

    <main>
        {{ $slot }}
    </main>

    @include('partials.site-footer')
    @include('partials.whatsapp-fab')

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    @stack('scripts')
</body>

    <script>
    const {
        ClassicEditor,
        Essentials,
        Bold,
        Italic,
        Font,
        Paragraph
    } = CKEDITOR;
    const { FormatPainter } = CKEDITOR_PREMIUM_FEATURES;

    ClassicEditor
        .create( {
            attachTo: document.querySelector( '#editor' ),
            licenseKey: '<YOUR_LICENSE_KEY>',
            plugins: [ Essentials, Bold, Italic, Font, Paragraph, FormatPainter ],
            toolbar: [
                'undo', 'redo', '|', 'bold', 'italic', '|',
                'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', '|',
                'formatPainter'
            ]
        } )
        .then( /* ... */ )
        .catch( /* ... */ );
</script>
</html>
