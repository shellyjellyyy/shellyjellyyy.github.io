<?php
/**
 * GameAssetLab — single.php
 * Fallback for any singular post type not handled by single-tutorial.php
 * @package GameAssetLab
 */
get_header();
while ( have_posts() ) :
	the_post();
?>
<main id="main" tabindex="-1">
	<div class="cat-hero" style="padding-block:var(--space-12);">
		<div class="cat-hero__inner">
			<?php gal_breadcrumbs(); ?>
			<h1 class="cat-hero__title" style="margin-top:var(--space-4);"><?php the_title(); ?></h1>
		</div>
	</div>
	<div class="section">
		<div class="container--sm">
			<div class="article-meta">
				<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
					<?php echo esc_html( get_the_date( 'j F Y' ) ); ?>
				</time>
			</div>
			<div class="article-body">
				<?php the_content(); ?>
			</div>
		</div>
	</div>
</main>
<?php
endwhile;
get_footer();
?>
