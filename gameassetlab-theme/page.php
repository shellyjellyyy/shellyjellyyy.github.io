<?php
/**
 * GameAssetLab — page.php (generic page template)
 * Handles standard WordPress Pages that have no specific Page Template assigned.
 * Specialised pages (About, Tutorials Hub) use their own Template Name templates
 * in /page-templates/ and bypass this file entirely.
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
