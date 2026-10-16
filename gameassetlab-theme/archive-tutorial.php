<?php
/**
 * GameAssetLab — Tutorial Archive (archive-tutorial.php)
 * URL: /tutorials/
 * Primary keyword: blender game assets tutorial
 * @package GameAssetLab
 */

get_header();
?>

<main id="main" tabindex="-1">

	<!-- Category hero -->
	<div class="cat-hero">
		<div class="cat-hero__inner">
			<?php gal_breadcrumbs(); ?>
			<h1 class="cat-hero__title">Blender Game Asset Tutorials</h1>
			<p class="cat-hero__desc">
				Step-by-step guides for modeling low-poly props, unwrapping UVs, texturing assets, and
				exporting finished props into a real-time game engine. Each tutorial covers one focused
				stage of the asset workflow.
			</p>
		</div>
	</div>

	<div class="section section--paper">
		<div class="container">

			<!-- Category navigation cards -->
			<div class="grid grid-3" style="margin-bottom: var(--space-16);">
				<a href="<?php echo esc_url( home_url( '/tutorials/low-poly-modeling/' ) ); ?>" class="category-card" style="background: var(--color-ink);">
					<div class="category-card__icon" aria-hidden="true">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#b5cc1e" stroke-width="1.5"><path d="m12 2 9 4.5v9L12 20l-9-4.5v-9L12 2z"/></svg>
					</div>
					<h2 class="category-card__title">Low-Poly Modeling</h2>
					<p class="category-card__desc">Build props with efficient geometry. Low-poly barrels, crates, rocks, and environment props — geometry decisions explained.</p>
					<span class="category-card__arrow" aria-hidden="true">→</span>
				</a>
				<a href="<?php echo esc_url( home_url( '/tutorials/uv-texturing/' ) ); ?>" class="category-card" style="background: var(--color-ink);">
					<div class="category-card__icon" aria-hidden="true">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#b5cc1e" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
					</div>
					<h2 class="category-card__title">UV Mapping & Texturing</h2>
					<p class="category-card__desc">Seams, islands, texel density, and layout. Practical UV unwrapping that actually makes sense once you understand why each step matters.</p>
					<span class="category-card__arrow" aria-hidden="true">→</span>
				</a>
				<a href="<?php echo esc_url( home_url( '/tutorials/game-engine-export/' ) ); ?>" class="category-card" style="background: var(--color-ink);">
					<div class="category-card__icon" aria-hidden="true">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#b5cc1e" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
					</div>
					<h2 class="category-card__title">Game Engine Export</h2>
					<p class="category-card__desc">Transfer Blender assets into Unity using FBX. Scale, axes, materials, textures — and the common errors to catch before importing.</p>
					<span class="category-card__arrow" aria-hidden="true">→</span>
				</a>
			</div>

			<!-- Filter bar -->
			<div class="filter-bar" role="group" aria-label="Filter tutorials by category">
				<button class="filter-btn is-active" data-filter="all">All tutorials</button>
				<?php
				$cats = get_terms( [ 'taxonomy' => 'tutorial_cat', 'hide_empty' => false ] );
				if ( $cats && ! is_wp_error( $cats ) ) :
					foreach ( $cats as $cat ) :
					?>
					<button class="filter-btn" data-filter="<?php echo esc_attr( $cat->slug ); ?>">
						<?php echo esc_html( $cat->name ); ?>
					</button>
					<?php endforeach; ?>
				<?php else : ?>
					<button class="filter-btn" data-filter="low-poly-modeling">Low-Poly Modeling</button>
					<button class="filter-btn" data-filter="uv-texturing">UV & Texturing</button>
					<button class="filter-btn" data-filter="game-engine-export">Engine Export</button>
				<?php endif; ?>
			</div>

			<!-- Tutorial grid -->
			<?php
			$tutorial_query = new WP_Query( [
				'post_type'      => 'tutorial',
				'posts_per_page' => 12,
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'order'          => 'DESC',
			] );

			if ( $tutorial_query->have_posts() ) : ?>
			<div class="grid grid-3" id="tutorial-grid">
				<?php
				while ( $tutorial_query->have_posts() ) :
					$tutorial_query->the_post();
					$post_cats = get_the_terms( get_the_ID(), 'tutorial_cat' );
					$data_cat  = ( $post_cats && ! is_wp_error( $post_cats ) ) ? $post_cats[0]->slug : '';
					echo '<div data-category="' . esc_attr( $data_cat ) . '">';
					gal_tutorial_card( get_the_ID() );
					echo '</div>';
				endwhile;
				wp_reset_postdata();
				?>
			</div>
			<?php else : ?>
				<!-- Static placeholder tutorials for empty install -->
				<div class="grid grid-3" id="tutorial-grid">
					<?php
					$placeholders = [
						[
							'title'    => 'Low-Poly Barrel in Blender: A Step-by-Step Tutorial',
							'url'      => home_url( '/tutorials/low-poly-modeling/low-poly-barrel-blender/' ),
							'excerpt'  => 'Model a game-ready barrel from scratch. Learn how to use cylinders, loop cuts, and basic Blender tools to produce clean, efficient geometry.',
							'category' => 'Low-Poly Modeling',
							'filter'   => 'low-poly-modeling',
							'time'     => '45',
							'level'    => 'Beginner',
						],
						[
							'title'    => 'UV Unwrapping a Game Asset in Blender',
							'url'      => home_url( '/tutorials/uv-texturing/blender-uv-unwrapping-game-asset/' ),
							'excerpt'  => 'Place seams, generate UV islands, and arrange your UV layout so textures map correctly. A practical guide using the barrel from the modeling tutorial.',
							'category' => 'UV & Texturing',
							'filter'   => 'uv-texturing',
							'time'     => '40',
							'level'    => 'Beginner',
						],
						[
							'title'    => 'Blender to Unity: FBX Export for Game Assets',
							'url'      => home_url( '/tutorials/game-engine-export/blender-to-unity-fbx/' ),
							'excerpt'  => 'The complete FBX export workflow: correct scale, axis orientation, material setup, and what to check when your asset lands in Unity.',
							'category' => 'Engine Export',
							'filter'   => 'game-engine-export',
							'time'     => '35',
							'level'    => 'Beginner',
						],
					];
					foreach ( $placeholders as $tut ) : ?>
					<div data-category="<?php echo esc_attr( $tut['filter'] ); ?>">
						<a href="<?php echo esc_url( $tut['url'] ); ?>" class="tutorial-card">
							<div class="tutorial-card__image">
								<div class="tutorial-card__image-placeholder">
									<?php echo gal_inline_svg_placeholder( $tut['title'] ); ?>
								</div>
							</div>
							<div class="tutorial-card__meta-bar">
								<span class="tag tag-outline"><?php echo esc_html( $tut['category'] ); ?></span>
								<span class="tag tag-beginner"><?php echo esc_html( $tut['level'] ); ?></span>
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
					</div>
					<?php endforeach; ?>
				</div>

				<!-- Empty state for no tutorials published -->
				<div class="empty-state" id="tutorials-empty" style="display:none;" role="status">
					<div class="empty-state__icon" aria-hidden="true">◇</div>
					<h3 class="empty-state__title">No tutorials in this category yet</h3>
					<p class="empty-state__text">
						Try another filter, or <a href="<?php echo esc_url( home_url( '/tutorials/' ) ); ?>">see all tutorials</a>.
					</p>
				</div>
			<?php endif; ?>

			<?php gal_pagination(); ?>

		</div>
	</div>

</main>

<?php get_footer(); ?>
