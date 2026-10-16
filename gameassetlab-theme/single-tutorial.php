<?php
/**
 * GameAssetLab — Single Tutorial (single-tutorial.php)
 * @package GameAssetLab
 */

get_header();

while ( have_posts() ) :
	the_post();

	$read_time    = get_post_meta( get_the_ID(), '_gal_read_time', true );
	$software     = get_post_meta( get_the_ID(), '_gal_software', true );
	$blender_ver  = get_post_meta( get_the_ID(), '_gal_blender_version', true );
	$prereqs      = get_post_meta( get_the_ID(), '_gal_prerequisites', true );
	$next_tut_url = get_post_meta( get_the_ID(), '_gal_next_tutorial', true );
	$cats         = get_the_terms( get_the_ID(), 'tutorial_cat' );
	$difficulties = get_the_terms( get_the_ID(), 'difficulty' );
	$difficulty   = ( $difficulties && ! is_wp_error( $difficulties ) ) ? $difficulties[0]->name : 'Beginner';

	// Get next tutorial post for recommendation
	$next_post    = get_next_post();
?>

<main id="main" tabindex="-1">

	<!-- Reading progress bar is in header; updated by JS -->

	<!-- Article header -->
	<div class="cat-hero" style="padding-block: var(--space-12);">
		<div class="cat-hero__inner">
			<?php gal_breadcrumbs(); ?>
			<div style="display:flex; gap:var(--space-3); flex-wrap:wrap; margin-bottom:var(--space-5); margin-top:var(--space-3);">
				<?php if ( $cats && ! is_wp_error( $cats ) ) : ?>
					<span class="tag tag-dark"><?php echo esc_html( $cats[0]->name ); ?></span>
				<?php endif; ?>
				<span class="tag tag-beginner"><?php echo esc_html( $difficulty ); ?></span>
				<?php if ( $read_time ) : ?>
					<span class="tag tag-outline" style="border-color:rgba(255,255,255,0.15); color:rgba(244,241,236,0.5);">
						<?php echo absint( $read_time ); ?> min
					</span>
				<?php endif; ?>
			</div>
			<h1 style="font-family:var(--font-display); font-size:var(--text-3xl); color:var(--color-paper); letter-spacing:-0.02em; line-height:1.15; max-width:22ch; margin-bottom:var(--space-4);">
				<?php the_title(); ?>
			</h1>
			<?php if ( has_excerpt() ) : ?>
				<p style="font-size:var(--text-md); color:rgba(244,241,236,0.65); max-width:60ch; line-height:1.6;">
					<?php the_excerpt(); ?>
				</p>
			<?php endif; ?>
		</div>
	</div>

	<!-- Featured image -->
	<?php if ( has_post_thumbnail() ) : ?>
	<div style="background:var(--color-surface); max-height:480px; overflow:hidden;">
		<div class="container" style="padding-block:0;">
			<?php the_post_thumbnail( 'gal-featured', [ 'loading' => 'eager', 'alt' => esc_attr( get_the_title() ), 'style' => 'width:100%; border-radius:0 0 var(--radius-lg) var(--radius-lg);' ] ); ?>
		</div>
	</div>
	<?php endif; ?>

	<!-- Article body + sidebar -->
	<div class="article-page">

		<!-- Main content -->
		<article class="article-content">

			<!-- Prerequisites block -->
			<?php if ( $prereqs || $software ) : ?>
			<div class="callout callout--note" style="margin-top:0;">
				<div class="callout__icon" aria-hidden="true">
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
				</div>
				<div class="callout__body">
					<p class="callout__title">Before you start</p>
					<?php if ( $software ) : ?>
						<p style="margin-bottom:var(--space-2);"><strong>Software:</strong> <?php echo esc_html( $software ); ?><?php if ( $blender_ver ) echo ' (tested on ' . esc_html( $blender_ver ) . ')'; ?></p>
					<?php endif; ?>
					<?php if ( $prereqs ) : ?>
						<p style="margin-bottom:0;"><strong>Prerequisites:</strong> <?php echo wp_kses_post( nl2br( $prereqs ) ); ?></p>
					<?php endif; ?>
				</div>
			</div>
			<?php endif; ?>

			<!-- Article meta -->
			<div class="article-meta">
				<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
					Published <?php echo esc_html( get_the_date( 'j F Y' ) ); ?>
				</time>
				<?php if ( get_the_modified_date( 'c' ) !== get_the_date( 'c' ) ) : ?>
					<span>· Updated <?php echo esc_html( get_the_modified_date( 'j F Y' ) ); ?></span>
				<?php endif; ?>
				<span>·</span>
				<span>GameAssetLab</span>
			</div>

			<!-- Article body -->
			<div class="article-body">
				<?php the_content(); ?>
			</div>

			<!-- Tags -->
			<?php
			$tag_list = '';
			if ( $cats && ! is_wp_error( $cats ) ) {
				foreach ( $cats as $cat ) {
					$tag_list .= '<a href="' . esc_url( get_term_link( $cat ) ) . '" class="tag tag-outline">' . esc_html( $cat->name ) . '</a> ';
				}
			}
			if ( $tag_list ) : ?>
			<div style="display:flex; gap:var(--space-2); flex-wrap:wrap; margin-top:var(--space-8); padding-top:var(--space-6); border-top:1px solid var(--color-border-light);">
				<span class="text-muted text-sm">Filed under:</span>
				<?php echo $tag_list; // Already escaped above ?>
			</div>
			<?php endif; ?>

			<!-- Next tutorial recommendation -->
			<?php if ( $next_tut_url ) : ?>
				<?php
				// Try to get post from URL
				$next_id    = url_to_postid( $next_tut_url );
				$next_title = $next_id ? get_the_title( $next_id ) : 'Next tutorial';
				$next_desc  = $next_id ? wp_trim_words( get_the_excerpt( $next_id ), 18, '…' ) : '';
				?>
				<a href="<?php echo esc_url( $next_tut_url ); ?>" class="next-tutorial">
					<div>
						<p class="next-tutorial__label">Continue the workflow →</p>
						<h3 class="next-tutorial__title"><?php echo esc_html( $next_title ); ?></h3>
						<?php if ( $next_desc ) : ?>
							<p style="color:rgba(244,241,236,0.55); font-size:var(--text-sm);"><?php echo esc_html( $next_desc ); ?></p>
						<?php endif; ?>
					</div>
					<span class="next-tutorial__arrow" aria-hidden="true">→</span>
				</a>
			<?php elseif ( $next_post ) : ?>
				<a href="<?php echo esc_url( get_permalink( $next_post ) ); ?>" class="next-tutorial">
					<div>
						<p class="next-tutorial__label">Continue the workflow →</p>
						<h3 class="next-tutorial__title"><?php echo esc_html( get_the_title( $next_post ) ); ?></h3>
						<p style="color:rgba(244,241,236,0.55); font-size:var(--text-sm);"><?php echo esc_html( wp_trim_words( get_the_excerpt( $next_post ), 18, '…' ) ); ?></p>
					</div>
					<span class="next-tutorial__arrow" aria-hidden="true">→</span>
				</a>
			<?php endif; ?>

		</article>

		<!-- Sidebar -->
		<aside class="article-sidebar" role="complementary" aria-label="<?php esc_attr_e( 'Tutorial sidebar', 'gameassetlab' ); ?>">

			<!-- Table of Contents (JS-generated) -->
			<nav class="toc" id="toc" aria-label="<?php esc_attr_e( 'Table of contents', 'gameassetlab' ); ?>">
				<p class="toc__title"><?php esc_html_e( 'In this tutorial', 'gameassetlab' ); ?></p>
				<ol id="toc-list">
					<!-- Populated by JavaScript from h2/h3 headings -->
				</ol>
			</nav>

			<!-- Tutorial details -->
			<div style="margin-top:var(--space-6); background:var(--color-paper-dark); border:1px solid var(--color-border-light); border-radius:var(--radius-md); padding:var(--space-5);">
				<p style="font-family:var(--font-mono); font-size:var(--text-xs); color:var(--color-ink-40); text-transform:uppercase; letter-spacing:0.1em; margin-bottom:var(--space-4);"><?php esc_html_e( 'Tutorial info', 'gameassetlab' ); ?></p>
				<dl style="display:grid; gap:var(--space-3);">
					<?php if ( $difficulty ) : ?>
					<div>
						<dt style="font-size:var(--text-xs); color:var(--color-ink-40); text-transform:uppercase; letter-spacing:0.06em;"><?php esc_html_e( 'Level', 'gameassetlab' ); ?></dt>
						<dd style="font-weight:600; font-size:var(--text-sm);"><?php echo esc_html( $difficulty ); ?></dd>
					</div>
					<?php endif; ?>
					<?php if ( $read_time ) : ?>
					<div>
						<dt style="font-size:var(--text-xs); color:var(--color-ink-40); text-transform:uppercase; letter-spacing:0.06em;"><?php esc_html_e( 'Time needed', 'gameassetlab' ); ?></dt>
						<dd style="font-weight:600; font-size:var(--text-sm);"><?php echo absint( $read_time ); ?> <?php esc_html_e( 'minutes', 'gameassetlab' ); ?></dd>
					</div>
					<?php endif; ?>
					<?php if ( $software ) : ?>
					<div>
						<dt style="font-size:var(--text-xs); color:var(--color-ink-40); text-transform:uppercase; letter-spacing:0.06em;"><?php esc_html_e( 'Software', 'gameassetlab' ); ?></dt>
						<dd style="font-weight:600; font-size:var(--text-sm);"><?php echo esc_html( $software ); ?></dd>
					</div>
					<?php endif; ?>
				</dl>
			</div>

			<!-- Related tutorials widget area -->
			<?php if ( is_active_sidebar( 'tutorial-sidebar' ) ) : ?>
				<div style="margin-top:var(--space-6);">
					<?php dynamic_sidebar( 'tutorial-sidebar' ); ?>
				</div>
			<?php endif; ?>

		</aside>
	</div><!-- /.article-page -->

</main>

<?php
endwhile;
get_footer();
?>
