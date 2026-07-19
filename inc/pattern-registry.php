<?php
/**
 * Design-system-aware pattern registration.
 *
 * §A "Pattern ownership". Every pattern file in /patterns/ declares a
 * `Design system:` header (alongside Title / Slug / etc.) naming the design
 * system(s) that own it — `carbon`, a comma-separated list, or `*` for
 * system-neutral compositions (zero AWT blocks).
 *
 * WordPress auto-registers theme patterns from /patterns/ on `init`. This runs
 * just after that, reads each file's `Design system:` header, and unregisters
 * any pattern not owned by the active design system. Carbon owns every
 * pattern shipped today (all tagged `carbon`), so nothing is unregistered —
 * pure no-op. The header keeps pattern ownership declared next to the
 * pattern itself instead of in a separate list.
 *
 * Untagged patterns are left alone (defensive — never hide a pattern because a
 * header is missing).
 *
 * @package AWT\Theme
 */

declare( strict_types = 1 );

namespace AWT\Theme\PatternRegistry;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Unregister patterns whose `Design system:` header doesn't include the active
 * system's slug (and isn't `*`).
 */
function filter_by_design_system(): void {
	if ( ! class_exists( '\\WP_Block_Patterns_Registry' ) || ! class_exists( '\\AWT\\Theme\\DesignSystem\\Registry' ) ) {
		return;
	}

	$active = \AWT\Theme\DesignSystem\Registry::get_active()->slug();
	$dir    = get_stylesheet_directory() . '/patterns/';
	$files  = glob( $dir . '*.php' );
	if ( ! is_array( $files ) ) {
		return;
	}

	$registry = \WP_Block_Patterns_Registry::get_instance();

	foreach ( $files as $file ) {
		$headers = get_file_data(
			$file,
			array(
				'slug'         => 'Slug',
				'designSystem' => 'Design system',
			)
		);

		$slug = trim( (string) $headers['slug'] );
		$ds   = trim( (string) $headers['designSystem'] );

		if ( $slug === '' || $ds === '' ) {
			continue; // No slug or untagged → leave registered.
		}

		$systems = array_map( 'trim', explode( ',', $ds ) );
		if ( in_array( '*', $systems, true ) || in_array( $active, $systems, true ) ) {
			continue; // Owned by the active system (or neutral).
		}

		if ( $registry->is_registered( $slug ) ) {
			unregister_block_pattern( $slug );
		}
	}
}

// WordPress registers theme patterns on `init` (default priority). Run after
// that so the patterns exist to be unregistered.
add_action( 'init', __NAMESPACE__ . '\\filter_by_design_system', 20 );
