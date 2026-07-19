<?php
/**
 * Block-inserter filter — hides blocks the active design system can't render.
 *
 * §A "Component registry + class layer". Every AWT block declares an
 * `awt_component` slug in its block.json. The active design system declares
 * which component slugs it `supported_components()`. This filter removes from
 * the inserter any gated block whose component the active system doesn't
 * support.
 *
 * Carbon supports all 34 gated components, so today this is a NO-OP — the
 * filter returns the allowed list unchanged, so `true` (allow-all) stays
 * `true` and no other plugin's blocks are affected. The filter exists so the
 * inserter always mirrors supported_components() with no second list to
 * keep in sync.
 *
 * Design-system-NEUTRAL native blocks (no `awt_component`) and non-AWT blocks
 * (core/*, third-party) are never filtered.
 *
 * Orphaned-block fallback (a block already in post content whose component the
 * active system doesn't support) needs no code here: each render.php asks
 * `classes_for()`, which returns '' for unsupported components, so the block
 * emits its DOM + ARIA with no design-system classes — semantically intact,
 * visually unstyled — until the user switches back or removes it.
 *
 * @package AWT\Theme
 */

declare( strict_types = 1 );

namespace AWT\Theme\InserterFilter;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Map of blockName => awt_component slug, captured as blocks register. Custom
 * block.json keys aren't exposed on WP_Block_Type, so we snapshot them from
 * the parsed metadata array via the `block_type_metadata` filter.
 *
 * @return array<string, string>
 */
function &component_map(): array {
	static $map = array();
	return $map;
}

/**
 * Capture `awt_component` during block registration. Returns the metadata
 * untouched — pure side-effect.
 *
 * @param array $metadata Parsed block.json metadata.
 * @return array
 */
function capture_metadata( array $metadata ): array {
	if ( isset( $metadata['name'], $metadata['awt_component'] ) && is_string( $metadata['awt_component'] ) ) {
		$map                      = &component_map();
		$map[ $metadata['name'] ] = $metadata['awt_component'];
	}
	return $metadata;
}

/**
 * Filter the inserter's allowed-block list to the active system's supported
 * components. No-op when nothing would be hidden (the Carbon / AWT-Free case)
 * so `true` is preserved and blocks registered after this filter still appear.
 *
 * @param bool|string[]            $allowed Current allowed list (true = all).
 * @param \WP_Block_Editor_Context $context Editor context (unused).
 * @return bool|string[]
 */
function filter_allowed( $allowed, $context = null ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed -- filter signature; context reserved for future per-editor scoping.
	if ( ! class_exists( '\\AWT\\Theme\\DesignSystem\\Registry' ) ) {
		return $allowed;
	}
	$map = component_map();
	if ( empty( $map ) ) {
		return $allowed;
	}

	$supported = \AWT\Theme\DesignSystem\Registry::get_active()->supported_components();

	// Which gated AWT blocks would be hidden under the active system?
	$blocked = array();
	foreach ( $map as $block_name => $component ) {
		if ( ! in_array( $component, $supported, true ) ) {
			$blocked[ $block_name ] = true;
		}
	}
	if ( empty( $blocked ) ) {
		return $allowed; // Carbon supports everything → leave the list untouched.
	}

	// Restriction needed: materialize the list and drop the blocked blocks.
	$list = is_array( $allowed )
		? $allowed
		: array_keys( \WP_Block_Type_Registry::get_instance()->get_all_registered() );

	return array_values( array_filter( $list, static fn( $name ) => empty( $blocked[ $name ] ) ) );
}

// Registered at file-load time (before `init`) so the metadata capture is in
// place before blocks register.
add_filter( 'block_type_metadata', __NAMESPACE__ . '\\capture_metadata' );
add_filter( 'allowed_block_types_all', __NAMESPACE__ . '\\filter_allowed', 10, 2 );
