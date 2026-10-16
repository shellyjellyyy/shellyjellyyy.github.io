<?php
/**
 * Setup content — registers default tutorial content on theme activation.
 * Run once; idempotent (checks before creating).
 *
 * @package GameAssetLab
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Called from functions.php include.
 * Nothing runs at include time — content creation happens via WP-CLI
 * or the admin dashboard after activation.
 *
 * HOW TO POPULATE TUTORIALS:
 * After activating the theme:
 *   1. Go to WordPress Admin → Tutorials → Tutorial Categories
 *   2. Create: Low-Poly Modeling (slug: low-poly-modeling)
 *              UV Mapping & Texturing (slug: uv-texturing)
 *              Game Engine Export (slug: game-engine-export)
 *   3. Go to Tutorials → Add New and create each tutorial using the
 *      content blocks documented in the /content-templates/ folder.
 *   4. Set each tutorial's permalink under Settings → Permalinks
 *      to "Custom Structure": /tutorials/%tutorial_cat%/%postname%/
 *
 * OR run the WP-CLI commands in /deployment/setup-commands.sh
 */

/* --------------------------------------------------------------------- */
/* ADMIN NOTICE — shown once after activation to guide content setup      */
/* --------------------------------------------------------------------- */

function gal_activation_notice() {
	if ( ! current_user_can( 'manage_options' ) ) return;
	if ( get_transient( 'gal_activated' ) ) {
		echo '<div class="notice notice-info is-dismissible"><p>';
		echo '<strong>GameAssetLab theme activated.</strong> ';
		echo 'Next step: go to <a href="' . esc_url( admin_url( 'edit-tags.php?taxonomy=tutorial_cat&post_type=tutorial' ) ) . '">Tutorial Categories</a> and create the three categories, then add tutorials under <a href="' . esc_url( admin_url( 'post-new.php?post_type=tutorial' ) ) . '">Tutorials → Add New</a>.';
		echo '</p></div>';
		delete_transient( 'gal_activated' );
	}
}
add_action( 'admin_notices', 'gal_activation_notice' );

function gal_theme_activated() {
	set_transient( 'gal_activated', true, 30 );
}
add_action( 'after_switch_theme', 'gal_theme_activated' );
