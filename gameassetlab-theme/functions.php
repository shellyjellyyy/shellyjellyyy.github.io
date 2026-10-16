<?php
/**
 * GameAssetLab Theme — functions.php
 *
 * Core WordPress setup, asset loading, custom post types,
 * widget areas, menu registration, SEO/schema helpers.
 *
 * @package GameAssetLab
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GAL_VERSION', '1.0.0' );
define( 'GAL_DIR', get_template_directory() );
define( 'GAL_URI', get_template_directory_uri() );

/* ==========================================================================
   THEME SETUP
   ========================================================================== */

function gal_setup() {
	load_theme_textdomain( 'gameassetlab', GAL_DIR . '/languages' );

	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style' ] );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor-style.css' );
	add_theme_support( 'align-wide' );

	// Thumbnail sizes.
	add_image_size( 'gal-card',     800, 450, true );
	add_image_size( 'gal-featured', 1200, 675, true );
	add_image_size( 'gal-thumb',    400, 225, true );

	// Nav menus.
	register_nav_menus( [
		'primary'  => __( 'Primary Navigation', 'gameassetlab' ),
		'footer-1' => __( 'Footer Column 1 (Tutorials)', 'gameassetlab' ),
		'footer-2' => __( 'Footer Column 2 (Resources)', 'gameassetlab' ),
	] );
}
add_action( 'after_setup_theme', 'gal_setup' );

/* ==========================================================================
   ENQUEUE ASSETS
   ========================================================================== */

function gal_enqueue_assets() {
	// Google Fonts — Fraunces (display), Inter (body), JetBrains Mono (code).
	wp_enqueue_style(
		'gal-fonts',
		'https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300..900;1,9..144,300..900&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@400;700&display=swap',
		[],
		null
	);

	// Main stylesheet.
	wp_enqueue_style(
		'gameassetlab-style',
		get_stylesheet_uri(),
		[ 'gal-fonts' ],
		GAL_VERSION
	);

	// Component stylesheet (pagination, accordion, dark mode, comparison).
	wp_enqueue_style(
		'gameassetlab-components',
		GAL_URI . '/assets/css/components.css',
		[ 'gameassetlab-style' ],
		GAL_VERSION
	);

	// Theme JS.
	wp_enqueue_script(
		'gameassetlab-main',
		GAL_URI . '/assets/js/main.js',
		[],
		GAL_VERSION,
		[ 'strategy' => 'defer', 'in_footer' => true ]
	);

	// Pass data to JS.
	wp_localize_script( 'gameassetlab-main', 'galData', [
		'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
		'nonce'      => wp_create_nonce( 'gal_nonce' ),
		'searchUrl'  => get_search_link(),
		'siteUrl'    => home_url(),
	] );

	// Comment reply.
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'gal_enqueue_assets' );

/* ==========================================================================
   CUSTOM POST TYPE — TUTORIAL
   ========================================================================== */

function gal_register_post_types() {
	$labels = [
		'name'               => __( 'Tutorials', 'gameassetlab' ),
		'singular_name'      => __( 'Tutorial', 'gameassetlab' ),
		'menu_name'          => __( 'Tutorials', 'gameassetlab' ),
		'add_new_item'       => __( 'Add New Tutorial', 'gameassetlab' ),
		'edit_item'          => __( 'Edit Tutorial', 'gameassetlab' ),
		'search_items'       => __( 'Search Tutorials', 'gameassetlab' ),
		'not_found'          => __( 'No tutorials found.', 'gameassetlab' ),
		'not_found_in_trash' => __( 'No tutorials in trash.', 'gameassetlab' ),
	];

	register_post_type( 'tutorial', [
		'labels'             => $labels,
		'public'             => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'menu_icon'          => 'dashicons-welcome-learn-more',
		'menu_position'      => 5,
		'supports'           => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions', 'author' ],
		'has_archive'        => false,
		'hierarchical'       => false,
		'rewrite'            => [ 'slug' => 'tutorials', 'with_front' => false ],
		'show_in_rest'       => true,
		'rest_base'          => 'tutorials',
	] );
}
add_action( 'init', 'gal_register_post_types' );

/* ==========================================================================
   CUSTOM TAXONOMY — TUTORIAL CATEGORY
   ========================================================================== */

function gal_register_taxonomies() {
	$labels = [
		'name'          => __( 'Tutorial Categories', 'gameassetlab' ),
		'singular_name' => __( 'Tutorial Category', 'gameassetlab' ),
		'menu_name'     => __( 'Categories', 'gameassetlab' ),
		'all_items'     => __( 'All Categories', 'gameassetlab' ),
		'edit_item'     => __( 'Edit Category', 'gameassetlab' ),
		'add_new_item'  => __( 'Add New Category', 'gameassetlab' ),
	];

	register_taxonomy( 'tutorial_cat', 'tutorial', [
		'labels'            => $labels,
		'public'            => true,
		'show_ui'           => true,
		'show_in_menu'      => true,
		'show_in_rest'      => true,
		'hierarchical'      => true,
		'rewrite'           => [ 'slug' => 'tutorials', 'with_front' => false, 'hierarchical' => true ],
		'show_admin_column' => true,
	] );

	// Difficulty level.
	register_taxonomy( 'difficulty', 'tutorial', [
		'labels'       => [
			'name'          => __( 'Difficulty', 'gameassetlab' ),
			'singular_name' => __( 'Difficulty', 'gameassetlab' ),
		],
		'public'       => true,
		'show_ui'      => true,
		'show_in_rest' => true,
		'hierarchical' => false,
		'rewrite'      => [ 'slug' => 'difficulty' ],
	] );
}
add_action( 'init', 'gal_register_taxonomies' );

/* ==========================================================================
   CUSTOM META BOXES — TUTORIAL FIELDS
   ========================================================================== */

function gal_register_meta_boxes() {
	add_meta_box(
		'gal_tutorial_meta',
		__( 'Tutorial Details', 'gameassetlab' ),
		'gal_tutorial_meta_cb',
		'tutorial',
		'side',
		'high'
	);
}
add_action( 'add_meta_boxes', 'gal_register_meta_boxes' );

function gal_tutorial_meta_cb( $post ) {
	wp_nonce_field( 'gal_tutorial_meta_nonce', 'gal_tutorial_meta_nonce' );
	$read_time   = get_post_meta( $post->ID, '_gal_read_time', true );
	$software    = get_post_meta( $post->ID, '_gal_software', true );
	$blender_ver = get_post_meta( $post->ID, '_gal_blender_version', true );
	$prereqs     = get_post_meta( $post->ID, '_gal_prerequisites', true );
	$next_tut    = get_post_meta( $post->ID, '_gal_next_tutorial', true );
	?>
	<p>
		<label for="gal_read_time"><strong><?php esc_html_e( 'Estimated Time (minutes)', 'gameassetlab' ); ?></strong></label><br>
		<input type="number" id="gal_read_time" name="gal_read_time" value="<?php echo esc_attr( $read_time ); ?>" style="width:100%" min="1">
	</p>
	<p>
		<label for="gal_software"><strong><?php esc_html_e( 'Software Required', 'gameassetlab' ); ?></strong></label><br>
		<input type="text" id="gal_software" name="gal_software" value="<?php echo esc_attr( $software ); ?>" style="width:100%" placeholder="e.g. Blender 4.x">
	</p>
	<p>
		<label for="gal_blender_version"><strong><?php esc_html_e( 'Blender Version Tested', 'gameassetlab' ); ?></strong></label><br>
		<input type="text" id="gal_blender_version" name="gal_blender_version" value="<?php echo esc_attr( $blender_ver ); ?>" style="width:100%" placeholder="e.g. 4.2 LTS">
	</p>
	<p>
		<label for="gal_prerequisites"><strong><?php esc_html_e( 'Prerequisites', 'gameassetlab' ); ?></strong></label><br>
		<textarea id="gal_prerequisites" name="gal_prerequisites" style="width:100%" rows="3"><?php echo esc_textarea( $prereqs ); ?></textarea>
	</p>
	<p>
		<label for="gal_next_tutorial"><strong><?php esc_html_e( 'Next Tutorial URL', 'gameassetlab' ); ?></strong></label><br>
		<input type="url" id="gal_next_tutorial" name="gal_next_tutorial" value="<?php echo esc_url( $next_tut ); ?>" style="width:100%">
	</p>
	<?php
}

function gal_save_tutorial_meta( $post_id ) {
	if ( ! isset( $_POST['gal_tutorial_meta_nonce'] ) ) return;
	if ( ! wp_verify_nonce( $_POST['gal_tutorial_meta_nonce'], 'gal_tutorial_meta_nonce' ) ) return;
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( ! current_user_can( 'edit_post', $post_id ) ) return;
	if ( get_post_type( $post_id ) !== 'tutorial' ) return;

	$fields = [
		'gal_read_time'      => '_gal_read_time',
		'gal_software'       => '_gal_software',
		'gal_blender_version'=> '_gal_blender_version',
		'gal_prerequisites'  => '_gal_prerequisites',
		'gal_next_tutorial'  => '_gal_next_tutorial',
	];

	foreach ( $fields as $key => $meta_key ) {
		if ( isset( $_POST[ $key ] ) ) {
			if ( $key === 'gal_next_tutorial' ) {
				update_post_meta( $post_id, $meta_key, esc_url_raw( $_POST[ $key ] ) );
			} elseif ( $key === 'gal_read_time' ) {
				update_post_meta( $post_id, $meta_key, absint( $_POST[ $key ] ) );
			} else {
				update_post_meta( $post_id, $meta_key, sanitize_textarea_field( $_POST[ $key ] ) );
			}
		}
	}
}
add_action( 'save_post', 'gal_save_tutorial_meta' );

/* ==========================================================================
   WIDGET AREAS
   ========================================================================== */

function gal_widgets_init() {
	register_sidebar( [
		'name'          => __( 'Tutorial Sidebar', 'gameassetlab' ),
		'id'            => 'tutorial-sidebar',
		'description'   => __( 'Widgets displayed in the tutorial article sidebar.', 'gameassetlab' ),
		'before_widget' => '<div class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget__title">',
		'after_title'   => '</h4>',
	] );
}
add_action( 'widgets_init', 'gal_widgets_init' );

/* ==========================================================================
   SEO & STRUCTURED DATA (JSON-LD)
   Used when Rank Math / Yoast is NOT installed.
   When either plugin is present, disable this to avoid conflicts.
   ========================================================================== */

function gal_has_seo_plugin() {
	return class_exists( 'RankMath' ) || class_exists( 'WPSEO_Options' );
}

function gal_output_schema() {
	if ( gal_has_seo_plugin() ) return;

	$site_name = get_bloginfo( 'name' );
	$site_url  = home_url();
	$logo_url  = GAL_URI . '/assets/images/logo.svg';

	// WebSite + SearchAction.
	$website = [
		'@context'        => 'https://schema.org',
		'@type'           => 'WebSite',
		'name'            => $site_name,
		'url'             => $site_url,
		'potentialAction' => [
			'@type'       => 'SearchAction',
			'target'      => [
				'@type'       => 'EntryPoint',
				'urlTemplate' => $site_url . '/?s={search_term_string}',
			],
			'query-input' => 'required name=search_term_string',
		],
	];
	echo '<script type="application/ld+json">' . wp_json_encode( $website, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";

	// Organization.
	$org = [
		'@context' => 'https://schema.org',
		'@type'    => 'Organization',
		'name'     => $site_name,
		'url'      => $site_url,
		'logo'     => [
			'@type' => 'ImageObject',
			'url'   => $logo_url,
		],
		'sameAs'   => [],
	];
	echo '<script type="application/ld+json">' . wp_json_encode( $org, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";

	// Article schema for tutorials.
	if ( is_singular( 'tutorial' ) ) {
		global $post;
		$thumb_id  = get_post_thumbnail_id( $post );
		$thumb_src = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'gal-featured' ) : $logo_url;
		$cats      = get_the_terms( $post, 'tutorial_cat' );
		$section   = ( $cats && ! is_wp_error( $cats ) ) ? $cats[0]->name : 'Blender Tutorials';

		$article = [
			'@context'         => 'https://schema.org',
			'@type'            => 'Article',
			'headline'         => get_the_title( $post ),
			'description'      => wp_strip_all_tags( get_the_excerpt( $post ) ),
			'url'              => get_permalink( $post ),
			'datePublished'    => get_the_date( 'c', $post ),
			'dateModified'     => get_the_modified_date( 'c', $post ),
			'image'            => [ '@type' => 'ImageObject', 'url' => $thumb_src ],
			'author'           => [ '@type' => 'Organization', 'name' => $site_name ],
			'publisher'        => [
				'@type' => 'Organization',
				'name'  => $site_name,
				'logo'  => [ '@type' => 'ImageObject', 'url' => $logo_url ],
			],
			'articleSection'   => $section,
			'inLanguage'       => 'en-US',
		];
		echo '<script type="application/ld+json">' . wp_json_encode( $article, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";

		// BreadcrumbList.
		$breadcrumb_list = gal_get_breadcrumb_schema( $post );
		if ( $breadcrumb_list ) {
			echo '<script type="application/ld+json">' . wp_json_encode( $breadcrumb_list, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
		}
	}

	// BreadcrumbList on other pages.
	if ( is_page() || is_tax( 'tutorial_cat' ) ) {
		$breadcrumb_list = gal_get_breadcrumb_schema( get_queried_object() );
		if ( $breadcrumb_list ) {
			echo '<script type="application/ld+json">' . wp_json_encode( $breadcrumb_list, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
		}
	}
}
add_action( 'wp_head', 'gal_output_schema', 5 );

function gal_get_breadcrumb_schema( $object ) {
	$items = [];
	$pos   = 1;

	$items[] = [
		'@type'    => 'ListItem',
		'position' => $pos++,
		'name'     => get_bloginfo( 'name' ),
		'item'     => home_url(),
	];

	if ( is_singular( 'tutorial' ) ) {
		$cats = get_the_terms( $object, 'tutorial_cat' );
		if ( $cats && ! is_wp_error( $cats ) ) {
			$cat = $cats[0];
			$items[] = [
				'@type'    => 'ListItem',
				'position' => $pos++,
				'name'     => $cat->name,
				'item'     => get_term_link( $cat ),
			];
		}
		$items[] = [
			'@type'    => 'ListItem',
			'position' => $pos++,
			'name'     => get_the_title( $object ),
			'item'     => get_permalink( $object ),
		];
	}

	if ( ! $items || count( $items ) < 2 ) return null;

	return [
		'@context'        => 'https://schema.org',
		'@type'           => 'BreadcrumbList',
		'itemListElement' => $items,
	];
}

/* ==========================================================================
   CUSTOM META TAGS (when no SEO plugin)
   ========================================================================== */

function gal_meta_tags() {
	if ( gal_has_seo_plugin() ) return;

	global $post;
	$site_name   = get_bloginfo( 'name' );
	$description = '';
	$og_image    = GAL_URI . '/assets/images/og-default.jpg';
	$og_type     = 'website';
	$title       = '';

	if ( is_singular( 'tutorial' ) ) {
		// Tutorial article — og:type article is correct here.
		$description = wp_strip_all_tags( get_the_excerpt( $post ) );
		$title       = get_the_title( $post ) . ' — ' . $site_name;
		$og_type     = 'article';
		if ( has_post_thumbnail( $post ) ) {
			$src = wp_get_attachment_image_src( get_post_thumbnail_id( $post ), 'gal-featured' );
			if ( $src ) $og_image = $src[0];
		}
	} elseif ( is_singular() ) {
		// Standard page — og:type website.
		$description = wp_strip_all_tags( get_the_excerpt( $post ) ) ?: get_bloginfo( 'description' );
		$title       = get_the_title( $post ) . ' — ' . $site_name;
		if ( has_post_thumbnail( $post ) ) {
			$src = wp_get_attachment_image_src( get_post_thumbnail_id( $post ), 'gal-featured' );
			if ( $src ) $og_image = $src[0];
		}
	} elseif ( is_home() || is_front_page() ) {
		$description = get_bloginfo( 'description' );
		$title       = $site_name . ' — Blender game asset tutorials for beginners';
	} elseif ( is_tax( 'tutorial_cat' ) ) {
		$term        = get_queried_object();
		$description = $term->description ?: get_bloginfo( 'description' );
		$title       = $term->name . ' Tutorials — ' . $site_name;
	} elseif ( is_search() ) {
		$query       = get_search_query();
		$title       = 'Search results for: ' . $query . ' — ' . $site_name;
		$description = 'GameAssetLab search results for "' . esc_html( $query ) . '".';
	} else {
		$description = get_bloginfo( 'description' );
		$title       = wp_get_document_title();
	}

	$description = wp_trim_words( $description, 30, '…' );
	$canon       = is_singular() ? get_permalink() : home_url( add_query_arg( [] ) );

	echo '<meta name="description" content="' . esc_attr( $description ) . '">' . "\n";
	echo '<link rel="canonical" href="' . esc_url( $canon ) . '">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
	echo '<meta property="og:type" content="' . esc_attr( $og_type ) . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $canon ) . '">' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr( $site_name ) . '">' . "\n";
	echo '<meta property="og:image" content="' . esc_url( $og_image ) . '">' . "\n";
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta name="twitter:description" content="' . esc_attr( $description ) . '">' . "\n";
	echo '<meta name="twitter:image" content="' . esc_url( $og_image ) . '">' . "\n";
}
add_action( 'wp_head', 'gal_meta_tags', 1 );

/* ==========================================================================
   BREADCRUMB TEMPLATE HELPER
   ========================================================================== */

function gal_breadcrumbs() {
	echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'gameassetlab' ) . '"><ol>';
	echo '<li><a href="' . esc_url( home_url() ) . '">' . esc_html__( 'Home', 'gameassetlab' ) . '</a></li>';

	if ( is_singular( 'tutorial' ) ) {
		$cats = get_the_terms( get_the_ID(), 'tutorial_cat' );
		if ( $cats && ! is_wp_error( $cats ) ) {
			echo '<li><a href="' . esc_url( get_term_link( $cats[0] ) ) . '">' . esc_html( $cats[0]->name ) . '</a></li>';
		} else {
			echo '<li><a href="' . esc_url( home_url( '/tutorials/' ) ) . '">' . esc_html__( 'Tutorials', 'gameassetlab' ) . '</a></li>';
		}
		echo '<li aria-current="page">' . esc_html( get_the_title() ) . '</li>';
	} elseif ( is_page() ) {
		$ancestors = array_reverse( get_post_ancestors( get_the_ID() ) );
		foreach ( $ancestors as $ancestor_id ) {
			echo '<li><a href="' . esc_url( get_permalink( $ancestor_id ) ) . '">' . esc_html( get_the_title( $ancestor_id ) ) . '</a></li>';
		}
		echo '<li aria-current="page">' . esc_html( get_the_title() ) . '</li>';
	} elseif ( is_tax( 'tutorial_cat' ) ) {
		echo '<li><a href="' . esc_url( home_url( '/tutorials/' ) ) . '">' . esc_html__( 'Tutorials', 'gameassetlab' ) . '</a></li>';
		echo '<li aria-current="page">' . esc_html( single_tag_title( '', false ) ) . '</li>';
	} elseif ( is_search() ) {
		echo '<li aria-current="page">' . sprintf( esc_html__( 'Search: %s', 'gameassetlab' ), esc_html( get_search_query() ) ) . '</li>';
	} elseif ( is_404() ) {
		echo '<li aria-current="page">' . esc_html__( '404 — Page not found', 'gameassetlab' ) . '</li>';
	}

	echo '</ol></nav>';
}

/* ==========================================================================
   TUTORIAL CARD HELPER
   ========================================================================== */

function gal_tutorial_card( $post_id, $featured = false ) {
	$post        = get_post( $post_id );
	$title       = get_the_title( $post );
	$permalink   = get_permalink( $post );
	$excerpt     = get_the_excerpt( $post );
	$thumb_id    = get_post_thumbnail_id( $post );
	$read_time   = get_post_meta( $post_id, '_gal_read_time', true );
	$software    = get_post_meta( $post_id, '_gal_software', true );
	$difficulties= get_the_terms( $post_id, 'difficulty' );
	$difficulty  = ( $difficulties && ! is_wp_error( $difficulties ) ) ? $difficulties[0]->name : '';
	$cats        = get_the_terms( $post_id, 'tutorial_cat' );
	$cat         = ( $cats && ! is_wp_error( $cats ) ) ? $cats[0] : null;

	$card_class = 'tutorial-card';
	if ( $featured ) $card_class .= ' tutorial-card--featured';

	$size = $featured ? 'gal-featured' : 'gal-card';
	?>
	<a href="<?php echo esc_url( $permalink ); ?>" class="<?php echo esc_attr( $card_class ); ?>">
		<div class="tutorial-card__image">
			<?php if ( $thumb_id ) : ?>
				<?php echo wp_get_attachment_image( $thumb_id, $size, false, [ 'loading' => 'lazy', 'alt' => esc_attr( $title ) ] ); ?>
			<?php else : ?>
				<div class="tutorial-card__image-placeholder">
					<?php echo gal_inline_svg_placeholder( $title ); ?>
				</div>
			<?php endif; ?>
		</div>
		<div class="tutorial-card__meta-bar">
			<?php if ( $cat ) : ?>
				<span class="tag tag-outline"><?php echo esc_html( $cat->name ); ?></span>
			<?php endif; ?>
			<?php if ( $difficulty ) : ?>
				<span class="tag tag-beginner"><?php echo esc_html( $difficulty ); ?></span>
			<?php endif; ?>
		</div>
		<div class="tutorial-card__body">
			<h3 class="tutorial-card__title"><?php echo esc_html( $title ); ?></h3>
			<?php if ( $excerpt ) : ?>
				<p class="tutorial-card__excerpt"><?php echo esc_html( wp_trim_words( $excerpt, 22, '…' ) ); ?></p>
			<?php endif; ?>
		</div>
		<div class="tutorial-card__footer">
			<?php if ( $software ) : ?>
				<span class="text-mono"><?php echo esc_html( $software ); ?></span>
			<?php endif; ?>
			<?php if ( $read_time ) : ?>
				<span><?php echo absint( $read_time ); ?> <?php esc_html_e( 'min', 'gameassetlab' ); ?></span>
			<?php endif; ?>
		</div>
	</a>
	<?php
}

/* ==========================================================================
   INLINE SVG PLACEHOLDER (for cards without thumbnails)
   ========================================================================== */

function gal_inline_svg_placeholder( $title = '' ) {
	// Renders a simple low-poly-inspired SVG placeholder.
	ob_start();
	?>
	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 450" width="800" height="450" aria-hidden="true" focusable="false">
		<rect width="800" height="450" fill="#242424"/>
		<polygon points="0,450 200,180 400,300" fill="#2a2a2a" opacity="0.8"/>
		<polygon points="200,180 500,0 600,250" fill="#303030" opacity="0.9"/>
		<polygon points="400,300 700,200 800,450" fill="#1e1e1e" opacity="0.9"/>
		<polygon points="500,0 800,0 700,200" fill="#2e2e2e" opacity="0.8"/>
		<polygon points="0,0 500,0 200,180" fill="#282828" opacity="0.7"/>
		<!-- Low-poly hexagon accent -->
		<polygon points="400,160 440,182 440,225 400,247 360,225 360,182" fill="none" stroke="#b5cc1e" stroke-width="1.5" opacity="0.5"/>
		<text x="400" y="260" text-anchor="middle" font-family="monospace" font-size="11" fill="#666" letter-spacing="2">GAME ASSET</text>
	</svg>
	<?php
	return ob_get_clean();
}

/* ==========================================================================
   TITLE TAG SUPPORT
   ========================================================================== */

function gal_wp_title( $title ) {
	if ( gal_has_seo_plugin() ) return $title;
	return $title;
}

/* ==========================================================================
   NOINDEX — search results and 404 pages
   ========================================================================== */

function gal_noindex_pages() {
	if ( gal_has_seo_plugin() ) return; // SEO plugin handles this itself.
	if ( is_search() || is_404() ) {
		echo '<meta name="robots" content="noindex, follow">' . "\n";
	}
}
add_action( 'wp_head', 'gal_noindex_pages', 1 );

/* ==========================================================================
   CLEAN UP WORDPRESS HEAD
   ========================================================================== */

remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wlwmanifest_link' );
remove_action( 'wp_head', 'rsd_link' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );

/* ==========================================================================
   EXCERPT LENGTH
   ========================================================================== */

function gal_excerpt_length() { return 28; }
add_filter( 'excerpt_length', 'gal_excerpt_length' );

function gal_excerpt_more( $more ) { return '…'; }
add_filter( 'excerpt_more', 'gal_excerpt_more' );

/* ==========================================================================
   PAGINATION HELPER
   ========================================================================== */

function gal_pagination() {
	$links = paginate_links( [
		'type'    => 'array',
		'prev_text' => '← Previous',
		'next_text' => 'Next →',
	] );

	if ( ! $links ) return;

	echo '<nav class="pagination" aria-label="' . esc_attr__( 'Page navigation', 'gameassetlab' ) . '"><ul class="pagination__list">';
	foreach ( $links as $link ) {
		echo '<li class="pagination__item">' . $link . '</li>';
	}
	echo '</ul></nav>';
}

/* ==========================================================================
   AJAX SEARCH (for live search overlay)
   ========================================================================== */

function gal_ajax_search() {
	check_ajax_referer( 'gal_nonce', 'nonce' );

	$query = sanitize_text_field( $_GET['q'] ?? '' );
	if ( ! $query ) {
		wp_send_json_success( [] );
	}

	$search = new WP_Query( [
		's'              => $query,
		'posts_per_page' => 6,
		'post_type'      => [ 'tutorial', 'page' ],
		'post_status'    => 'publish',
	] );

	$results = [];
	if ( $search->have_posts() ) {
		while ( $search->have_posts() ) {
			$search->the_post();
			$results[] = [
				'title'     => get_the_title(),
				'url'       => get_permalink(),
				'type'      => get_post_type(),
				'excerpt'   => wp_trim_words( get_the_excerpt(), 15, '…' ),
			];
		}
	}
	wp_reset_postdata();

	wp_send_json_success( $results );
}
add_action( 'wp_ajax_nopriv_gal_search', 'gal_ajax_search' );
add_action( 'wp_ajax_gal_search', 'gal_ajax_search' );

/* ==========================================================================
   INCLUDE THEME MODULES
   ========================================================================== */

require_once GAL_DIR . '/inc/setup-content.php';
