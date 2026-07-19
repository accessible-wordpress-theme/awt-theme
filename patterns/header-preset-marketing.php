<?php
/**
 * Title: AWT — Header preset: Marketing
 * Slug: awt/header-preset-marketing
 * Design system: carbon
 * Description: Brand + horizontal primary nav + minimal global actions. No side nav. Optimized for landing pages and conversion-focused sites.
 * Categories: awt-section, header
 * Keywords: header, preset, marketing, landing, brand
 * Block Types: core/template-part/header
 * Inserter: yes
 *
 * Composition mirrors the §1 "Header style presets → 1. Marketing"
 * pseudocode exactly. UI shell parameter overrides (header.position,
 * sideNav.mode, content.maxWidth) live in theme.json — switching to this
 * preset only replaces template-part content; layout overrides come from
 * the active style variation.
 */
?>
<!-- wp:awt/skip-link /-->
<!-- wp:awt/header-brand {"kind":"text-with-prefix"} /-->
<!-- wp:awt/header-nav -->
<!-- wp:awt/header-menu {"text":"Product"} -->
<!-- wp:awt/header-nav-item {"text":"Features","href":"#"} /-->
<!-- wp:awt/header-nav-item {"text":"Integrations","href":"#"} /-->
<!-- wp:awt/header-nav-item {"text":"Updates","href":"#"} /-->
<!-- /wp:awt/header-menu -->
<!-- wp:awt/header-nav-item {"text":"Pricing","href":"#"} /-->
<!-- wp:awt/header-nav-item {"text":"Customers","href":"#"} /-->
<!-- /wp:awt/header-nav -->
<!-- wp:awt/header-global -->
<!-- wp:awt/button {"text":"Get started","kind":"primary","size":"md","className":"awt-hide-on-mobile"} /-->
<!-- wp:awt/color-scheme-toggle {"kind":"icon-only"} /-->
<!-- /wp:awt/header-global -->
