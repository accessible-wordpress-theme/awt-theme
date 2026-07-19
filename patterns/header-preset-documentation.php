<?php
/**
 * Title: AWT — Header preset: Documentation
 * Slug: awt/header-preset-documentation
 * Design system: carbon
 * Description: Brand + horizontal section nav + search + persistent side nav. Designed for technical product docs, knowledge bases, and reference sites.
 * Categories: awt-section, header
 * Keywords: header, preset, docs, knowledge base, reference
 * Block Types: core/template-part/header
 * Inserter: yes
 *
 * Mirrors §1 "Header style presets → 2. Documentation". The side-nav is
 * configured in the sidebar template part (not here) — this preset is
 * header-only. Style variations carry `sideNav.mode: persistent`.
 */
?>
<!-- wp:awt/skip-link /-->
<!-- wp:awt/header-brand {"kind":"text-with-prefix"} /-->
<!-- wp:awt/header-nav -->
<!-- wp:awt/header-nav-item {"text":"Guides","href":"/guides"} /-->
<!-- wp:awt/header-menu {"text":"Reference"} -->
<!-- wp:awt/header-nav-item {"text":"API","href":"/reference/api"} /-->
<!-- wp:awt/header-nav-item {"text":"CLI","href":"/reference/cli"} /-->
<!-- wp:awt/header-nav-item {"text":"SDKs","href":"/reference/sdks"} /-->
<!-- /wp:awt/header-menu -->
<!-- wp:awt/header-nav-item {"text":"Changelog","href":"/changelog"} /-->
<!-- /wp:awt/header-nav -->
<!-- wp:awt/header-global -->
<!-- wp:awt/header-action {"iconName":"search","label":"Search docs","href":"/?s="} /-->
<!-- wp:awt/color-scheme-toggle {"kind":"icon-only"} /-->
<!-- wp:awt/header-action {"iconName":"logo--github","label":"View on GitHub","href":"#"} /-->
<!-- /wp:awt/header-global -->
