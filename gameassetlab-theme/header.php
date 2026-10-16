<?php
/**
 * GameAssetLab — Header Template
 * @package GameAssetLab
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main"><?php esc_html_e( 'Skip to main content', 'gameassetlab' ); ?></a>
<div class="reading-progress" id="reading-progress" role="progressbar" aria-label="<?php esc_attr_e( 'Reading progress', 'gameassetlab' ); ?>"></div>

<header class="site-header" role="banner">
	<div class="site-header__inner">

		<!-- Logo -->
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo" rel="home" aria-label="<?php esc_attr_e( 'GameAssetLab — home', 'gameassetlab' ); ?>">
			<div class="site-logo__mark" aria-hidden="true">G</div>
			<span class="site-logo__text">Game<span>Asset</span>Lab</span>
		</a>

		<!-- Primary Navigation -->
		<nav class="site-nav" id="site-nav" role="navigation" aria-label="<?php esc_attr_e( 'Primary', 'gameassetlab' ); ?>">
			<?php
			wp_nav_menu( [
				'theme_location'  => 'primary',
				'container'       => false,
				'items_wrap'      => '%3$s',
				'fallback_cb'     => 'gal_default_nav',
				'link_before'     => '',
				'link_after'      => '',
				'item_spacing'    => 'discard',
			] );
			?>
		</nav>

		<div class="flex" style="gap:0.5rem; align-items:center;">
			<!-- Search toggle -->
			<button class="header-search-btn" id="search-toggle"
				aria-label="<?php esc_attr_e( 'Open search', 'gameassetlab' ); ?>"
				aria-expanded="false" aria-controls="header-search">
				<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true" focusable="false">
					<circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/>
				</svg>
			</button>

			<!-- Mobile toggle -->
			<button class="mobile-menu-toggle" id="mobile-menu-toggle"
				aria-label="<?php esc_attr_e( 'Toggle navigation menu', 'gameassetlab' ); ?>"
				aria-expanded="false" aria-controls="site-nav">
				<span></span><span></span><span></span>
			</button>
		</div>

	</div><!-- /.site-header__inner -->

	<!-- Search overlay -->
	<div class="header-search-overlay" id="header-search" role="search" aria-label="<?php esc_attr_e( 'Site search', 'gameassetlab' ); ?>">
		<form method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
			<label for="header-search-input" class="sr-only"><?php esc_html_e( 'Search tutorials', 'gameassetlab' ); ?></label>
			<input
				type="search"
				id="header-search-input"
				name="s"
				placeholder="<?php esc_attr_e( 'Search tutorials, topics, or steps…', 'gameassetlab' ); ?>"
				autocomplete="off"
				value="<?php echo esc_attr( get_search_query() ); ?>"
			>
		</form>
	</div>
</header>

<?php
function gal_default_nav() {
	$links = [
		home_url( '/tutorials/' )                                    => 'Tutorials',
		home_url( '/tutorials/low-poly-modeling/' )                  => 'Low-Poly Modeling',
		home_url( '/tutorials/uv-texturing/' )                       => 'UV & Texturing',
		home_url( '/tutorials/game-engine-export/' )                 => 'Engine Export',
		home_url( '/about/' )                                        => 'About',
	];
	$current = home_url( sanitize_url( wp_unslash( $_SERVER['REQUEST_URI'] ?? '/' ) ) );

	foreach ( $links as $url => $label ) {
		$active = ( rtrim( $current, '/' ) === rtrim( $url, '/' ) ) ? ' aria-current="page"' : '';
		$cta    = ( $label === 'Tutorials' ) ? ' site-nav__link--cta' : '';
		echo '<a href="' . esc_url( $url ) . '" class="site-nav__link' . esc_attr( $cta ) . '"' . $active . '>' . esc_html( $label ) . '</a>';
	}
}
