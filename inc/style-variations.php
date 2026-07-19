<?php
/**
 * Style-variation metadata + apply mechanism.
 *
 * Three responsibilities:
 *
 *   1. variation_metadata() — the 4 named light/dark pairings with their
 *      labels, descriptions, and swatch colors. Used by both the welcome
 *      wizard and the AWT Settings → Appearance tab pickers.
 *
 *   2. apply_style_variation( $slug ) — writes the chosen variation's
 *      content into the active theme's user-level `wp_global_styles`
 *      post. This is what WordPress's Site Editor → Styles does when you
 *      click a variation: the variation file is the "template", and
 *      "applying" it means copying its settings + styles into the
 *      currently-active user globals.
 *
 *   3. picker_ui() — emits the 4-card swatch grid markup the wizard step 1
 *      and the Appearance tab both render. Single source of truth.
 *
 * Per §1 "Visitor color-scheme behavior" and §5 "Site Editor surfaces →
 * Color palette": style variations bundle complete light/dark pairings;
 * applying one swaps both theme scopes at once.
 *
 * @package AWT\Theme
 */

declare( strict_types = 1 );

namespace AWT\Theme\StyleVariations;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 4 named light/dark pairings, in the same order the wizard surfaces them.
 *
 * `light_color` and `dark_color` are display-only — used by the picker UI
 * to render the two-square swatch under each variation's label. The actual
 * theme-scope swap happens via the JSON files under `styles/`.
 *
 * §A: now sourced from the design system layer — Carbon supplies the four
 * pairings above. This function stays as the public entry point so the
 * wizard + Appearance-tab pickers don't change.
 */
function variation_metadata(): array {
	return \AWT\Theme\DesignSystem\Registry::get_active()->get_style_variations();
}

/**
 * Write the chosen variation's settings + styles into the active theme's
 * user-level `wp_global_styles` post. This is the same post WordPress's
 * Site Editor → Styles UI writes to when a variation is selected; we just
 * do it programmatically instead of through a click + REST round-trip.
 *
 * Naming convention (set by WP core in `WP_Theme_JSON_Resolver::get_user_global_styles_post()`):
 *
 *   post_type   = 'wp_global_styles'
 *   post_status = 'publish'
 *   post_name   = 'wp-global-styles-' . urlencode( $theme_slug )
 *   wp_theme    = (taxonomy term) the theme slug
 *
 * One post per (user × theme); reused across activations of the same theme.
 *
 * IMPORTANT — this OVERWRITES any prior Site-Editor customizations to the
 * user globals. That's the same behavior the Site Editor's "Select
 * variation" action has. The UI surface should warn the user. We don't
 * back up the prior state — restoring requires re-applying a different
 * variation or editing in Site Editor.
 *
 * Returns true on success, false if the variation file is missing or the
 * write fails.
 *
 * @param string $slug Variation slug matching a JSON file under `styles/`.
 * @return bool True on success, false if the file is missing or the write fails.
 */
function apply_style_variation( string $slug ): bool {
	$variation_file = get_stylesheet_directory() . '/styles/' . $slug . '.json';
	if ( ! file_exists( $variation_file ) ) {
		return false;
	}
	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- local theme-bundled style variation, not a remote URL.
	$raw = file_get_contents( $variation_file );
	if ( $raw === false ) {
		return false;
	}
	$variation_data = json_decode( $raw, true );
	if ( ! is_array( $variation_data ) ) {
		return false;
	}

	// The user globals post stores only `version`, `settings`, `styles`
	// — drop the variation file's `$schema` and `title` keys.
	$user_global = array(
		'version'  => $variation_data['version'] ?? 3,
		'settings' => is_array( $variation_data['settings'] ?? null ) ? $variation_data['settings'] : new \stdClass(),
		'styles'   => is_array( $variation_data['styles'] ?? null ) ? $variation_data['styles'] : new \stdClass(),
	);

	$theme_slug = get_stylesheet();
	$post_name  = 'wp-global-styles-' . rawurlencode( $theme_slug );

	// Locate the existing post. Direct WP_Query — `get_page_by_path` and
	// friends don't handle the `wp_global_styles` CPT consistently across
	// WP versions.
	$existing = get_posts(
		array(
			'post_type'     => 'wp_global_styles',
			'post_status'   => 'publish',
			'name'          => $post_name,
			'numberposts'   => 1,
			'no_found_rows' => true,
		)
	);

	$post_data = array(
		'post_type'    => 'wp_global_styles',
		'post_status'  => 'publish',
		'post_name'    => $post_name,
		'post_title'   => __( 'Custom Styles', 'awt' ),
		'post_content' => wp_json_encode( $user_global, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ),
		'tax_input'    => array(
			'wp_theme' => $theme_slug,
		),
	);

	if ( ! empty( $existing ) ) {
		$post_data['ID'] = $existing[0]->ID;
		$result          = wp_update_post( $post_data, true );
	} else {
		$result = wp_insert_post( $post_data, true );
	}

	if ( is_wp_error( $result ) ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
			error_log( '[AWT] apply_style_variation failed: ' . $result->get_error_message() ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log -- debug-gated failure trace.
		}
		return false;
	}

	// Same `wp_set_object_terms` re-application as the header-preset path —
	// `tax_input` is unreliable when the term doesn't exist yet (insert
	// case). Force-assign after the post is in place.
	$post_id = is_array( $result ) ? ( $result['ID'] ?? 0 ) : (int) $result;
	if ( ! $post_id && ! empty( $existing ) ) {
		$post_id = (int) $existing[0]->ID;
	}
	if ( $post_id ) {
		wp_set_object_terms( $post_id, $theme_slug, 'wp_theme' );
	}

	// Bust theme-json resolver caches so the next request picks up the change.
	if ( class_exists( '\\WP_Theme_JSON_Resolver' ) && method_exists( '\\WP_Theme_JSON_Resolver', 'clean_cached_data' ) ) {
		\WP_Theme_JSON_Resolver::clean_cached_data();
	}

	return true;
}

/**
 * 4-card swatch grid for the variation picker. Used by the wizard's
 * step 1 and the AWT Settings → Appearance tab. Output is a fieldset
 * containing 4 radio inputs wrapped in clickable labels — caller wraps
 * in its own `<form>` and adds submit/back buttons.
 *
 * @param string $selected_slug Currently-selected variation slug, or '' for none.
 * @param string $name_attr     The name attribute for the radio group (default 'styleVariation').
 */
function picker_ui( string $selected_slug, string $name_attr = 'styleVariation' ): void {
	$variations = variation_metadata();
	?>
	<fieldset>
		<legend class="screen-reader-text"><?php esc_html_e( 'Style variation', 'awt' ); ?></legend>
		<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1em;">
			<?php
			foreach ( $variations as $slug => $variation ) :
				$is_selected = $selected_slug === $slug;
				?>
				<label style="display: block; padding: 1em; border: 2px solid <?php echo $is_selected ? '#0073aa' : '#c3c4c7'; ?>; border-radius: 4px; cursor: pointer; background: <?php echo $is_selected ? '#f0f6fc' : '#ffffff'; ?>;">
					<input type="radio" name="<?php echo esc_attr( $name_attr ); ?>" value="<?php echo esc_attr( $slug ); ?>" <?php checked( $is_selected ); ?> style="margin-inline-end: 0.5em;" />
					<strong><?php echo esc_html( $variation['label'] ); ?></strong>
					<div style="display: flex; gap: 4px; margin-block: 0.5em;">
						<div title="<?php esc_attr_e( 'Light scope', 'awt' ); ?>" style="background: <?php echo esc_attr( $variation['light_color'] ); ?>; inline-size: 80px; block-size: 32px; border: 1px solid #c3c4c7; border-radius: 2px;"></div>
						<div title="<?php esc_attr_e( 'Dark scope', 'awt' ); ?>" style="background: <?php echo esc_attr( $variation['dark_color'] ); ?>; inline-size: 80px; block-size: 32px; border: 1px solid #c3c4c7; border-radius: 2px;"></div>
					</div>
					<small style="color: #646970;"><?php echo esc_html( $variation['description'] ); ?></small>
				</label>
			<?php endforeach; ?>
		</div>
	</fieldset>
	<?php
}
