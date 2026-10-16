<?php
/**
 * GameAssetLab — Search Results (search.php)
 * @package GameAssetLab
 */

get_header();
$query = get_search_query();
?>

<main id="main" tabindex="-1">
	<div class="section">
		<div class="container">
			<div class="search-page">
				<div class="search-page__header">
					<?php gal_breadcrumbs(); ?>
					<?php if ( $query ) : ?>
						<h1 class="search-page__query" style="margin-top:var(--space-4);">
							Search results for <em style="font-style:italic; color:var(--color-ink-60);">"<?php echo esc_html( $query ); ?>"</em>
						</h1>
						<?php if ( have_posts() ) : ?>
							<p class="text-muted" style="margin-top:var(--space-2);">
								<?php printf( esc_html( _n( '%s result', '%s results', $wp_query->found_posts, 'gameassetlab' ) ), esc_html( number_format_i18n( $wp_query->found_posts ) ) ); ?>
							</p>
						<?php endif; ?>
					<?php else : ?>
						<h1 style="margin-top:var(--space-4);"><?php esc_html_e( 'Search', 'gameassetlab' ); ?></h1>
					<?php endif; ?>
				</div>

				<?php if ( have_posts() ) : ?>
					<div class="grid grid-3">
						<?php while ( have_posts() ) : the_post(); ?>
							<?php if ( get_post_type() === 'tutorial' ) : ?>
								<?php gal_tutorial_card( get_the_ID() ); ?>
							<?php else : ?>
								<a href="<?php the_permalink(); ?>" class="tutorial-card" style="text-decoration:none;">
									<div class="tutorial-card__body" style="padding:var(--space-6);">
										<span class="tag tag-outline" style="margin-bottom:var(--space-3); display:inline-block;"><?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?></span>
										<h2 class="tutorial-card__title"><?php the_title(); ?></h2>
										<p class="tutorial-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22, '…' ) ); ?></p>
									</div>
								</a>
							<?php endif; ?>
						<?php endwhile; ?>
					</div>
					<?php gal_pagination(); ?>
				<?php elseif ( $query ) : ?>
					<div class="empty-state">
						<div class="empty-state__icon" aria-hidden="true">◇</div>
						<h2 class="empty-state__title">No results for "<?php echo esc_html( $query ); ?>"</h2>
						<p class="empty-state__text">
							Try different search terms, or browse the <a href="<?php echo esc_url( home_url( '/tutorials/' ) ); ?>">tutorials index</a> to find what you need.
						</p>
					</div>
				<?php else : ?>
					<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" style="max-width:500px;">
						<label for="search-main" class="sr-only"><?php esc_html_e( 'Search', 'gameassetlab' ); ?></label>
						<div style="display:flex; gap:var(--space-3);">
							<input type="search" id="search-main" name="s"
								placeholder="<?php esc_attr_e( 'Search tutorials…', 'gameassetlab' ); ?>"
								style="flex:1; padding:var(--space-3) var(--space-4); border:1px solid var(--color-border-light); border-radius:var(--radius-sm); font-family:var(--font-body); font-size:var(--text-base);">
							<button type="submit" class="btn btn-primary"><?php esc_html_e( 'Search', 'gameassetlab' ); ?></button>
						</div>
					</form>
				<?php endif; ?>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>
