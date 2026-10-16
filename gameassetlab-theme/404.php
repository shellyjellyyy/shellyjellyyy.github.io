<?php
/**
 * GameAssetLab — 404 Page
 * @package GameAssetLab
 */

get_header();
?>

<main id="main" tabindex="-1">
	<div class="error-page">
		<div>
			<span class="error-page__code" aria-hidden="true">404</span>
			<h1 class="error-page__title">Page not found</h1>
			<p class="error-page__text">
				The page you were looking for has moved, or the URL contains a typo. Try one of the links below, or use the search to find what you need.
			</p>
			<div class="flex-center gap-4" style="flex-wrap:wrap; margin-bottom:var(--space-8);">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn btn-primary">Back to home</a>
				<a href="<?php echo esc_url( home_url( '/tutorials/' ) ); ?>" class="btn btn-outline">Browse tutorials</a>
			</div>
			<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="max-width:400px; margin-inline:auto; display:flex; gap:var(--space-3);">
				<label for="search-404" class="sr-only"><?php esc_html_e( 'Search', 'gameassetlab' ); ?></label>
				<input type="search" id="search-404" name="s"
					placeholder="<?php esc_attr_e( 'Search tutorials…', 'gameassetlab' ); ?>"
					style="flex:1; padding:var(--space-3) var(--space-4); border:1px solid var(--color-border-light); border-radius:var(--radius-sm); font-family:var(--font-body); font-size:var(--text-base);">
				<button type="submit" class="btn btn-primary"><?php esc_html_e( 'Search', 'gameassetlab' ); ?></button>
			</form>
		</div>
	</div>
</main>

<?php get_footer(); ?>
