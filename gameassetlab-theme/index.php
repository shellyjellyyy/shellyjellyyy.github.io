<?php
/**
 * GameAssetLab — index.php (fallback)
 * @package GameAssetLab
 */
get_header();
?>
<main id="main" tabindex="-1">
<?php if ( have_posts() ) : ?>
	<section class="section">
		<div class="container">
			<div class="grid grid-3">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php if ( get_post_type() === 'tutorial' ) : ?>
					<?php gal_tutorial_card( get_the_ID() ); ?>
				<?php else : ?>
					<article>
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<?php the_excerpt(); ?>
					</article>
				<?php endif; ?>
			<?php endwhile; ?>
			</div>
			<?php gal_pagination(); ?>
		</div>
	</section>
<?php else : ?>
	<div class="empty-state">
		<h2 class="empty-state__title">Nothing here yet</h2>
		<p class="empty-state__text"><a href="<?php echo esc_url( home_url( '/tutorials/' ) ); ?>">Browse all tutorials</a></p>
	</div>
<?php endif; ?>
</main>
<?php get_footer(); ?>
