<?php
/**
 * Title: AWT — Header preset: Public sector
 * Slug: awt/header-preset-public-sector
 * Design system: carbon
 * Description: Prominent agency identifier + accessible nav + language switcher + minimal actions. For government sites, public-service portals, EAA-regulated orgs.
 * Categories: awt-section, header
 * Keywords: header, preset, public sector, government, agency, accessibility
 * Block Types: core/template-part/header
 * Inserter: yes
 *
 * Mirrors §1 "Header style presets → 4. Public sector". The language
 * action's panelId="language-panel" needs an author-provided panel —
 * AWT does not ship a language-switching pattern at Stage 1 (typically
 * paired with WPML or Polylang). Style variations carry `header.position:
 * static` + `header.height: tall` for this preset.
 */
?>
<!-- wp:awt/skip-link /-->
<!-- wp:awt/header-brand {"kind":"logo-with-text-and-prefix","prefix":"Ministry of …"} /-->
<!-- wp:awt/header-nav -->
<!-- wp:awt/header-menu {"text":"Services"} -->
<!-- wp:awt/header-nav-item {"text":"Apply online","href":"#"} /-->
<!-- wp:awt/header-nav-item {"text":"Make a payment","href":"#"} /-->
<!-- wp:awt/header-nav-item {"text":"Report an issue","href":"#"} /-->
<!-- /wp:awt/header-menu -->
<!-- wp:awt/header-nav-item {"text":"About","href":"#"} /-->
<!-- wp:awt/header-nav-item {"text":"News","href":"#"} /-->
<!-- wp:awt/header-nav-item {"text":"Contact","href":"#"} /-->
<!-- /wp:awt/header-nav -->
<!-- wp:awt/header-global -->
<!-- wp:awt/header-action {"iconName":"search","label":"Search","href":"/?s="} /-->
<!-- wp:awt/header-action {"iconName":"language","label":"Change language","panelId":"language-panel"} /-->
<!-- wp:awt/color-scheme-toggle {"kind":"icon-only"} /-->
<!-- /wp:awt/header-global -->
