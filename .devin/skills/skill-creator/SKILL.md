---
name: skill-creator
description: A brief description, shown to the model to help it understand when to use this skill
---

## Context: Laravel ContentBlocks package
I have a Laravel package called ContentBlocks with the following architecture:

BlockRegistry (singleton) — registers block type → service class mappings
BlockRenderer (singleton) — dispatches render calls
AbstractBlock — normalizes attributes, validates via getValidationRules(), resolves view name from class name (e.g. ParagraphBlock → paragraph), renders from content-blocks:: namespace
BlockBuilder.php (Livewire) — manages $blocks[] array, handles add/remove/reorder/update, image/video uploads
ContentBlocksServiceProvider — registers singletons, auto-registers default blocks, generates @cbParagraph(...) style Blade directives
Post model casts content as array (stored as JSON)
show.blade.php loops $post->content and dispatches per-type directives manually

Current block types: paragraph, image, heading, quote, code, list, cta, video

## Task 1 — Cleanup & hardening

Refactor show.blade.php to use the generic @block($block['type'], $block['attributes']) directive instead of hardcoded per-type directives, so new block types are picked up automatically
Add a StoreBlockContentRequest form request (or equivalent) that validates the incoming content JSON on save — ensure each block has a valid type that exists in the registry, and that attributes is an array
Add a submit event guard on the hidden input sync: read current Livewire block state synchronously on form submit so the hidden content field is never stale
Add orphaned upload cleanup: move uploaded images/videos to a temp/ disk on upload, and on successful form save, relocate them to permanent storage — include a scheduled artisan command to purge temp files older than 24h
Audit AbstractBlock validation — ensure failed validation logs a warning and returns a safe empty render rather than throwing or silently outputting broken HTML


## Task 2 — New production-ready block types
For each new block type below, generate:

The block service class extending AbstractBlock (with getDefaultAttributes() and getValidationRules())
The Blade view (*.blade.php) with semantic, accessible HTML and no inline styles (use CSS classes only)
The Livewire BlockBuilder form fields for that block type (inside the existing @switch($block['type']) or equivalent)
Any migration or config changes if needed

New block types to add:
TypeDescriptioncolumnsTwo or three column layout wrapping other blocks (nested blocks array per column)dividerVisual section break — supports style variants: line, dots, spacegalleryMulti-image grid with caption per image, lightbox-ready data- attributesembedoEmbed-compatible URL field (YouTube, Twitter/X, Spotify) — resolves and renders iframe safelyaccordionCollapsible FAQ-style items — array of {question, answer} pairs, accessible with ARIAtableEditable rows/columns grid with optional header row, renders a proper <table>alertCallout box with type (info/success/warning/danger), icon slot, and dismissible flaghtmlRaw HTML passthrough for power users — sanitized via a configurable allowlist before save

## Constraints

All views must be accessible (ARIA where needed, semantic HTML)
No inline styles in views — CSS classes only
Validation must fail safe (log + empty render, never exception)
Nested blocks in columns must recurse through BlockRenderer, not duplicate render logic
The html block must sanitize on save, not on render — store the clean HTML in the DB
All new block types must be auto-registered in ContentBlocksServiceProvider and auto-generate their Blade directive
Follow existing naming conventions exactly