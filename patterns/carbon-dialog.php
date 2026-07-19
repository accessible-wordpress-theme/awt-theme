<?php
/**
 * Title: AWT — Dialog (Carbon pattern)
 * Slug: awt/carbon-dialog
 * Design system: carbon
 * Description: Modal dialog with header + body + footer action row. Triggered by a button.
 * Categories: awt-section
 * Keywords: dialog, modal, confirm, prompt
 * Block Types: core/post-content
 * Inserter: yes
 */
?>
<!-- wp:awt/modal-opener {"text":"Open dialog","kind":"primary","modalId":"awt-dialog-pattern"} /-->

<!-- wp:awt/modal {"id":"awt-dialog-pattern","label":"Confirm action","heading":"Are you sure?","passiveModal":false,"danger":false,"size":"sm","primaryButtonText":"Confirm","secondaryButtonText":"Cancel"} -->
<!-- wp:paragraph -->
<p>This action will permanently change your account configuration. You can review what changes before confirming.</p>
<!-- /wp:paragraph -->
<!-- /wp:awt/modal -->
