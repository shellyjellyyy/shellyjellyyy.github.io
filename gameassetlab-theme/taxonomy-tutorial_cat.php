<?php
/**
 * GameAssetLab — Taxonomy: tutorial_cat (taxonomy-tutorial_cat.php)
 * Serves /tutorials/low-poly-modeling/, /tutorials/uv-texturing/, /tutorials/game-engine-export/
 * @package GameAssetLab
 */

get_header();

$term        = get_queried_object();
$term_slug   = $term->slug;
$term_name   = $term->name;
$term_desc   = term_description();

// Per-category meta
$cat_meta = [
	'low-poly-modeling' => [
		'keyword'  => 'low poly blender',
		'subtitle' => 'Build props with efficient geometry. Each tutorial explains not just how to model, but why keeping geometry clean matters when the asset runs in real-time.',
		'icon'     => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#b5cc1e" stroke-width="1.5"><path d="m12 2 9 4.5v9L12 20l-9-4.5v-9L12 2z"/><path d="m12 20v-9M3 6.5l9 4.5 9-4.5"/></svg>',
		'next_cat' => [ 'label' => 'Next: UV Mapping & Texturing', 'url' => home_url( '/tutorials/uv-texturing/' ) ],
	],
	'uv-texturing' => [
		'keyword'  => 'blender uv unwrapping tutorial',
		'subtitle' => 'Seams, UV islands, and layout — explained for beginners. Learn how to unwrap a prop so textures map correctly onto every face, without stretching or wasted texture space.',
		'icon'     => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#b5cc1e" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>',
		'next_cat' => [ 'label' => 'Next: Game Engine Export', 'url' => home_url( '/tutorials/game-engine-export/' ) ],
	],
	'game-engine-export' => [
		'keyword'  => 'blender game asset export',
		'subtitle' => 'Transfer finished Blender assets into Unity. Export settings, scale, orientation, materials, and what to check before your prop appears in the scene for the first time.',
		'icon'     => '<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#b5cc1e" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>',
		'next_cat' => null,
	],
];

$meta = $cat_meta[ $term_slug ] ?? [
	'keyword'  => $term_name,
	'subtitle' => $term_desc ? wp_strip_all_tags( $term_desc ) : '',
	'icon'     => '',
	'next_cat' => null,
];
?>

<main id="main" tabindex="-1">

	<!-- Category hero -->
	<div class="cat-hero">
		<div class="cat-hero__inner">
			<?php gal_breadcrumbs(); ?>
			<div style="margin-top:var(--space-4);">
				<?php if ( $meta['icon'] ) : ?>
					<div style="margin-bottom:var(--space-4);"><?php echo $meta['icon']; // SVG — no user input ?></div>
				<?php endif; ?>
			</div>
			<h1 class="cat-hero__title"><?php echo esc_html( $term_name ); ?></h1>
			<p class="cat-hero__desc"><?php echo esc_html( $meta['subtitle'] ); ?></p>
		</div>
	</div>

	<section class="section section--paper">
		<div class="container">

			<?php
			$tax_query = new WP_Query( [
				'post_type'      => 'tutorial',
				'posts_per_page' => 12,
				'post_status'    => 'publish',
				'tax_query'      => [
					[
						'taxonomy' => 'tutorial_cat',
						'field'    => 'slug',
						'terms'    => $term_slug,
					],
				],
			] );
			?>

			<?php if ( $tax_query->have_posts() ) : ?>
			<div class="grid grid-3">
				<?php
				while ( $tax_query->have_posts() ) :
					$tax_query->the_post();
					gal_tutorial_card( get_the_ID() );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
			<?php else : ?>
				<!-- Category placeholder cards when no tutorials published yet -->
				<?php
				$placeholder_cats = [
					'low-poly-modeling' => [
						[
							'title'   => 'Low-Poly Barrel in Blender',
							'url'     => home_url( '/tutorials/low-poly-modeling/low-poly-barrel-blender/' ),
							'excerpt' => 'Model a game-ready barrel using cylinders, loop cuts, and minimal geometry. Understand how each edge and face affects the final render in a game engine.',
							'time'    => '45',
						],
					],
					'uv-texturing' => [
						[
							'title'   => 'UV Unwrapping a Game Asset in Blender',
							'url'     => home_url( '/tutorials/uv-texturing/blender-uv-unwrapping-game-asset/' ),
							'excerpt' => 'Place seams on a low-poly prop, generate UV islands, and arrange the layout. Practical guide using the barrel from the modeling series.',
							'time'    => '40',
						],
					],
					'game-engine-export' => [
						[
							'title'   => 'Blender to Unity via FBX',
							'url'     => home_url( '/tutorials/game-engine-export/blender-to-unity-fbx/' ),
							'excerpt' => 'Export a textured Blender prop into Unity using FBX. Correct scale, axis orientation, material setup, and common failure cases explained.',
							'time'    => '35',
						],
					],
				];
				$tuts = $placeholder_cats[ $term_slug ] ?? [];
				if ( $tuts ) :
				?>
				<div class="grid grid-3">
					<?php foreach ( $tuts as $tut ) : ?>
					<a href="<?php echo esc_url( $tut['url'] ); ?>" class="tutorial-card">
						<div class="tutorial-card__image">
							<div class="tutorial-card__image-placeholder">
								<?php echo gal_inline_svg_placeholder( $tut['title'] ); ?>
							</div>
						</div>
						<div class="tutorial-card__meta-bar">
							<span class="tag tag-outline"><?php echo esc_html( $term_name ); ?></span>
							<span class="tag tag-beginner">Beginner</span>
						</div>
						<div class="tutorial-card__body">
							<h2 class="tutorial-card__title"><?php echo esc_html( $tut['title'] ); ?></h2>
							<p class="tutorial-card__excerpt"><?php echo esc_html( $tut['excerpt'] ); ?></p>
						</div>
						<div class="tutorial-card__footer">
							<span class="text-mono">Blender 4.x</span>
							<span><?php echo esc_html( $tut['time'] ); ?> min</span>
						</div>
					</a>
					<?php endforeach; ?>
				</div>
				<?php else : ?>
				<div class="empty-state">
					<div class="empty-state__icon" aria-hidden="true">◇</div>
					<h2 class="empty-state__title">Tutorials coming soon</h2>
					<p class="empty-state__text">
						This category is being prepared. <a href="<?php echo esc_url( home_url( '/tutorials/' ) ); ?>">See all tutorials</a> in the meantime.
					</p>
				</div>
				<?php endif; ?>
			<?php endif; ?>

			<?php gal_pagination(); ?>

			<!-- Workflow next step -->
			<?php if ( $meta['next_cat'] ) : ?>
			<div style="margin-top:var(--space-16); padding-top:var(--space-12); border-top:1px solid var(--color-border-light); display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:var(--space-6);">
				<div>
					<p class="text-muted text-sm" style="margin-bottom:var(--space-2);">Workflow — next stage</p>
					<p style="font-size:var(--text-lg); font-weight:600;"><?php echo esc_html( $meta['next_cat']['label'] ); ?></p>
				</div>
				<a href="<?php echo esc_url( $meta['next_cat']['url'] ); ?>" class="btn btn-primary">
					Go to next stage →
				</a>
			</div>
			<?php endif; ?>

		</div>
	</section>

</main>

<?php get_footer(); ?>
