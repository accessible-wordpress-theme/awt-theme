# Accessibility statement

<!-- Canonical source. The awt-blocks release script injects this file into
     the WordPress.org readme.txt between the ACCESSIBILITY_START/END
     markers. Review on every YYYY.MM release (see the Stage 1 spec,
     "Accessibility statement → Maintenance cadence"). -->

## Our commitment

AWT is a WordPress theme and blocks plugin committed to **WCAG 2.2 AA**
conformance for the components, patterns, and templates it ships.
Accessibility is the product's reason to exist, not a feature of it: every
block is built on the Carbon Design System's accessibility groundwork,
reviewed against WCAG 2.2 AA, and shipped with an in-editor accessibility
linter that helps authors keep their own content accessible.

## Scope

This statement covers what AWT ships, at its default state:

- The AWT theme: all bundled page templates, template parts, style
  variations, and block patterns.
- The AWT blocks plugin: every block, in the editor and on the published
  page, and the AWT Settings screens.

It does not cover:

- Content written by site owners and authors (the in-editor accessibility
  linter helps here, but authors stay responsible for their content).
- Third-party plugins installed alongside AWT.
- Custom code or custom CSS added through the AWT Settings → Custom code
  fields.

## Standard

**WCAG 2.2 Level AA.** The in-editor accessibility linter uses the same
2.2 AA thresholds (for example, contrast checks), so what the editor
enforces and what this statement promises stay aligned.

## Known limitations

We list what we know does not yet meet the standard, honestly:

- **No independent audit has been performed yet.** Conformance so far rests
  on the Carbon Design System's accessibility work, our own component
  reviews, and automated checks — not on third-party verification. See
  "Audit status" below.
- Components inherit the current behavior of the Carbon Design System
  (v11). Where Carbon publishes known accessibility issues for a component,
  those apply to the matching AWT block until fixed upstream or worked
  around.

If you find a barrier we have not listed, please tell us — see "Feedback"
below.

## Audit status

**Pending.** A third-party accessibility audit is scheduled for Stage 4
(before commercial launch). The audit report will be published here when
complete.

## Feedback

Found an accessibility problem in AWT? Email
**[hello@accessiblewordpresstheme.com](mailto:hello@accessiblewordpresstheme.com)**.
Reports about real barriers are treated as bugs, not feature requests.

## Dates

- Statement prepared: 2026-07-17
- Last reviewed: 2026-07-17
