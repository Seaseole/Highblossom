<?php
    /** @var \App\Services\DataTransferObjects\SeoMetadata $seoMetadata */
?>


<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($seoMetadata->schemaJsonLd): ?>
    <script type="application/ld+json">
        <?php echo json_encode($seoMetadata->schemaJsonLd, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP); ?>

    </script>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
<?php /**PATH C:\laragon\www\Highblossom\resources\views/components/seo/schema.blade.php ENDPATH**/ ?>