<?php
/**
 * GameAssetLab — Homepage (front-page.php)
 * Primary keyword: blender game assets
 * @package GameAssetLab
 */

get_header();
?>

<main id="main" tabindex="-1">

	<!-- ================================================================
	     HERO SECTION
	     ================================================================ -->
	<section class="hero" aria-labelledby="hero-title">
		<div class="hero__inner">
			<div class="hero__content">
				<p class="hero__overline">Open-source 3D workflow — Blender to game engine</p>

				<h1 class="hero__title" id="hero-title">
					Make props<br>
					<em>ready for games.</em>
				</h1>

				<p class="hero__subtitle">
					Practical, step-by-step tutorials for modeling low-poly assets, unwrapping UVs, applying textures,
					and exporting finished props into a real-time game engine — all in Blender.
				</p>

				<div class="hero__cta">
					<a href="<?php echo esc_url( home_url( '/tutorials/' ) ); ?>" class="btn btn-primary btn-lg">
						Browse tutorials
					</a>
					<a href="<?php echo esc_url( home_url( '/tutorials/low-poly-modeling/low-poly-barrel-blender/' ) ); ?>" class="btn btn-ghost-light btn-lg">
						Start with the barrel →
					</a>
				</div>

				<div class="hero__meta" aria-label="Site overview">
					<div class="hero__meta-item">
						<span class="hero__meta-label">Focus</span>
						<span class="hero__meta-value">Low-poly props</span>
					</div>
					<div class="hero__meta-item">
						<span class="hero__meta-label">Software</span>
						<span class="hero__meta-value">Blender 4.x</span>
					</div>
					<div class="hero__meta-item">
						<span class="hero__meta-label">Audience</span>
						<span class="hero__meta-value">Beginners</span>
					</div>
				</div>
			</div>

			<!-- Visual — SVG asset stage grid -->
			<div class="hero__visual" aria-hidden="true">
				<div class="hero__asset-stages">
					<?php
					$stages = [
						[ 'label' => '01 — Model', 'color' => '#2a2a2a', 'accent' => '#444' ],
						[ 'label' => '02 — Wireframe', 'color' => '#1e2a1e', 'accent' => '#b5cc1e' ],
						[ 'label' => '03 — UV layout', 'color' => '#1a1a2e', 'accent' => '#6060ff' ],
						[ 'label' => '04 — Textured', 'color' => '#2a1a1a', 'accent' => '#cc6633' ],
					];
					foreach ( $stages as $stage ) :
					?>
					<div class="hero__stage-card">
						<span class="hero__stage-label"><?php echo esc_html( $stage['label'] ); ?></span>
						<div class="hero__stage-preview">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 150" width="200" height="150" focusable="false">
								<rect width="200" height="150" fill="<?php echo esc_attr( $stage['color'] ); ?>"/>
								<polygon points="40,120 100,20 160,120" fill="none" stroke="<?php echo esc_attr( $stage['accent'] ); ?>" stroke-width="1.5" opacity="0.7"/>
								<polygon points="100,20 160,120 100,90" fill="<?php echo esc_attr( $stage['accent'] ); ?>" opacity="0.15"/>
								<circle cx="100" cy="70" r="25" fill="none" stroke="<?php echo esc_attr( $stage['accent'] ); ?>" stroke-width="1" opacity="0.3"/>
							</svg>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
			</div>

		</div>
	</section>

	<!-- ================================================================
	     WORKFLOW STRIP
	     ================================================================ -->
	<div class="workflow-strip" aria-label="Tutorial workflow overview">
		<div class="workflow-strip__inner">
			<a href="<?php echo esc_url( home_url( '/tutorials/low-poly-modeling/' ) ); ?>" class="workflow-step">
				<div class="workflow-step__number" aria-hidden="true">01</div>
				<div class="workflow-step__text">
					<span class="workflow-step__name">Model the prop</span>
					<span class="workflow-step__desc">Low-poly geometry in Blender</span>
				</div>
			</a>
			<span class="workflow-arrow" aria-hidden="true">→</span>
			<a href="<?php echo esc_url( home_url( '/tutorials/uv-texturing/' ) ); ?>" class="workflow-step">
				<div class="workflow-step__number" aria-hidden="true">02</div>
				<div class="workflow-step__text">
					<span class="workflow-step__name">Unwrap UVs</span>
					<span class="workflow-step__desc">Seams, islands, and layout</span>
				</div>
			</a>
			<span class="workflow-arrow" aria-hidden="true">→</span>
			<a href="<?php echo esc_url( home_url( '/tutorials/uv-texturing/' ) ); ?>" class="workflow-step">
				<div class="workflow-step__number" aria-hidden="true">03</div>
				<div class="workflow-step__text">
					<span class="workflow-step__name">Apply textures</span>
					<span class="workflow-step__desc">Materials, colour, roughness</span>
				</div>
			</a>
			<span class="workflow-arrow" aria-hidden="true">→</span>
			<a href="<?php echo esc_url( home_url( '/tutorials/game-engine-export/' ) ); ?>" class="workflow-step">
				<div class="workflow-step__number" aria-hidden="true">04</div>
				<div class="workflow-step__text">
					<span class="workflow-step__name">Export to engine</span>
					<span class="workflow-step__desc">FBX handoff to Unity</span>
				</div>
			</a>
		</div>
	</div>

	<!-- ================================================================
	     TUTORIAL CATEGORIES
	     ================================================================ -->
	<section class="section" aria-labelledby="categories-heading">
		<div class="container">
			<div class="section-header">
				<div>
					<span class="section-eyebrow">Learn by workflow stage</span>
					<h2 class="section-title" id="categories-heading">Where do you want to start?</h2>
				</div>
				<a href="<?php echo esc_url( home_url( '/tutorials/' ) ); ?>" class="btn btn-outline">
					View all tutorials
				</a>
			</div>

			<div class="grid grid-3">
				<a href="<?php echo esc_url( home_url( '/tutorials/low-poly-modeling/' ) ); ?>" class="category-card">
					<div class="category-card__icon" aria-hidden="true">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#b5cc1e" stroke-width="1.5"><path d="m12 2 9 4.5v9L12 20l-9-4.5v-9L12 2z"/><path d="m12 20v-9M3 6.5l9 4.5 9-4.5"/></svg>
					</div>
					<div>
						<h3 class="category-card__title">Low-Poly Modeling</h3>
						<p class="category-card__desc">
							Build props with clean, efficient geometry. Learn why keeping poly count low matters for real-time rendering, and how to model a barrel, crate, or rock from scratch.
						</p>
					</div>
					<div class="category-card__count">2 tutorials</div>
					<span class="category-card__arrow" aria-hidden="true">→</span>
				</a>

				<a href="<?php echo esc_url( home_url( '/tutorials/uv-texturing/' ) ); ?>" class="category-card">
					<div class="category-card__icon" aria-hidden="true">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#b5cc1e" stroke-width="1.5"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
					</div>
					<div>
						<h3 class="category-card__title">UV Mapping & Texturing</h3>
						<p class="category-card__desc">
							Understand seams, UV islands, and texel density. Practical unwrapping guides that show you how to lay out UVs so textures apply correctly when the prop reaches the engine.
						</p>
					</div>
					<div class="category-card__count">2 tutorials</div>
					<span class="category-card__arrow" aria-hidden="true">→</span>
				</a>

				<a href="<?php echo esc_url( home_url( '/tutorials/game-engine-export/' ) ); ?>" class="category-card">
					<div class="category-card__icon" aria-hidden="true">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#b5cc1e" stroke-width="1.5"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
					</div>
					<div>
						<h3 class="category-card__title">Game Engine Export</h3>
						<p class="category-card__desc">
							Transfer finished assets from Blender into Unity. Scale, orientation, materials, and common failure cases — the FBX handoff explained step by step.
						</p>
					</div>
					<div class="category-card__count">2 tutorials</div>
					<span class="category-card__arrow" aria-hidden="true">→</span>
				</a>
			</div>
		</div>
	</section>

	<!-- ================================================================
	     FEATURED TUTORIALS (dynamic)
	     ================================================================ -->
	<section class="section section--paper-dark" aria-labelledby="featured-heading">
		<div class="container">
			<div class="section-header">
				<div>
					<span class="section-eyebrow">Start here</span>
					<h2 class="section-title" id="featured-heading">Featured tutorials</h2>
				</div>
			</div>

			<?php
			$featured_query = new WP_Query( [
				'post_type'      => 'tutorial',
				'posts_per_page' => 3,
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'order'          => 'DESC',
			] );

			if ( $featured_query->have_posts() ) :
			?>
			<div class="grid grid-3">
				<?php while ( $featured_query->have_posts() ) : $featured_query->the_post(); ?>
					<?php gal_tutorial_card( get_the_ID() ); ?>
				<?php endwhile; wp_reset_postdata(); ?>
			</div>
			<?php else : ?>
			<!-- Placeholder cards shown when no tutorials are published yet -->
			<div class="grid grid-3">
				<?php
				$placeholder_tutorials = [
					[
						'title'    => 'Low-Poly Barrel in Blender',
						'url'      => home_url( '/tutorials/low-poly-modeling/low-poly-barrel-blender/' ),
						'excerpt'  => 'Model a game-ready barrel using cylinders, loop cuts, and minimal geometry — and understand why each decision matters for real-time rendering.',
						'category' => 'Low-Poly Modeling',
						'time'     => '45',
						'level'    => 'Beginner',
					],
					[
						'title'    => 'UV Unwrapping a Game Asset',
						'url'      => home_url( '/tutorials/uv-texturing/blender-uv-unwrapping-game-asset/' ),
						'excerpt'  => 'Place seams, project UV islands, and arrange the UV layout so textures land correctly on your prop — from first seam to final packed layout.',
						'category' => 'UV & Texturing',
						'time'     => '40',
						'level'    => 'Beginner',
					],
					[
						'title'    => 'Export Blender Assets to Unity via FBX',
						'url'      => home_url( '/tutorials/game-engine-export/blender-to-unity-fbx/' ),
						'excerpt'  => 'The practical Blender-to-Unity handoff: export settings, scale correction, material setup, and what to check before your prop hits the scene.',
						'category' => 'Engine Export',
						'time'     => '35',
						'level'    => 'Beginner',
					],
				];
				foreach ( $placeholder_tutorials as $tut ) : ?>
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
						<h3 class="tutorial-card__title"><?php echo esc_html( $tut['title'] ); ?></h3>
						<p class="tutorial-card__excerpt"><?php echo esc_html( $tut['excerpt'] ); ?></p>
					</div>
					<div class="tutorial-card__footer">
						<span class="text-mono">Blender 4.x</span>
						<span><?php echo esc_html( $tut['time'] ); ?> min</span>
					</div>
				</a>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>

			<div style="text-align:center; margin-top: var(--space-12);">
				<a href="<?php echo esc_url( home_url( '/tutorials/' ) ); ?>" class="btn btn-outline btn-lg">
					See all tutorials
				</a>
			</div>
		</div>
	</section>

	<!-- ================================================================
	     WHAT YOU WILL LEARN (editorial strip)
	     ================================================================ -->
	<section class="section section--dark" aria-labelledby="learn-heading">
		<div class="container">
			<div style="display:grid; grid-template-columns: 1fr 1fr; gap: var(--space-16); align-items: center;">
				<div>
					<span class="section-eyebrow">The GameAssetLab approach</span>
					<h2 class="section-title" id="learn-heading" style="color:var(--color-paper)">
						One prop. Four stages. A complete workflow.
					</h2>
					<p style="color:rgba(244,241,236,0.65); font-size:var(--text-md); line-height:1.7; margin-bottom:var(--space-6);">
						Most tutorials show you a technique in isolation. GameAssetLab connects the stages: you model a prop, unwrap its UVs, apply textures to it, and export that same prop into a game engine.
					</p>
					<p style="color:rgba(244,241,236,0.65); font-size:var(--text-md); line-height:1.7; margin-bottom:var(--space-8);">
						The barrel you make in the modeling tutorial is the same one you UV-unwrap in the next guide. When you reach the export tutorial, you already understand what you are handing off.
					</p>
					<a href="<?php echo esc_url( home_url( '/tutorials/low-poly-modeling/low-poly-barrel-blender/' ) ); ?>" class="btn btn-primary btn-lg">
						Start with the barrel tutorial
					</a>
				</div>
				<div>
					<!-- Asset stage checklist -->
					<div style="background:var(--color-surface); border:1px solid var(--color-border); border-radius:var(--radius-lg); padding:var(--space-8);">
						<p style="font-family:var(--font-mono); font-size:var(--text-xs); color:var(--color-ink-40); text-transform:uppercase; letter-spacing:0.1em; margin-bottom:var(--space-6);">Asset production checklist</p>
						<?php
						$checklist_items = [
							'Block out the prop shape with basic geometry',
							'Keep polygon count appropriate for real-time use',
							'Apply bevels and edge loops for silhouette',
							'Check topology before unwrapping',
							'Place seams to minimize UV distortion',
							'Pack UV islands to use texture space efficiently',
							'Apply and preview materials in the viewport',
							'Export FBX with correct scale and axis settings',
							'Import into Unity and verify scale and materials',
						];
						?>
						<ul class="checklist" aria-label="Asset production steps">
							<?php foreach ( $checklist_items as $i => $item ) : ?>
							<li>
								<span class="checklist__check" aria-hidden="true">
									<?php if ( $i < 6 ) : ?>
										<svg width="12" height="12" viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="2"><polyline points="2 6 5 9 10 3"/></svg>
									<?php endif; ?>
								</span>
								<span><?php echo esc_html( $item ); ?></span>
							</li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- ================================================================
	     CALL TO ACTION — START LEARNING
	     ================================================================ -->
	<section class="section" style="text-align:center; padding-block:var(--space-24);" aria-labelledby="cta-heading">
		<div class="container--sm">
			<span class="section-eyebrow">Free, no account required</span>
			<h2 class="section-title" id="cta-heading" style="font-size:var(--text-3xl);">
				Pick a tutorial and start building.
			</h2>
			<p style="font-size:var(--text-md); color:var(--color-ink-60); margin-block:var(--space-6) var(--space-8); max-width:52ch; margin-inline:auto;">
				Every tutorial on GameAssetLab assumes you know how to open Blender. Nothing else. No paid tools, no premium access, no prerequisites beyond curiosity.
			</p>
			<div class="flex-center gap-4" style="flex-wrap:wrap;">
				<a href="<?php echo esc_url( home_url( '/tutorials/low-poly-modeling/' ) ); ?>" class="btn btn-primary btn-lg">
					Low-poly modeling
				</a>
				<a href="<?php echo esc_url( home_url( '/tutorials/uv-texturing/' ) ); ?>" class="btn btn-outline btn-lg">
					UV & texturing
				</a>
				<a href="<?php echo esc_url( home_url( '/tutorials/game-engine-export/' ) ); ?>" class="btn btn-outline btn-lg">
					Engine export
				</a>
			</div>
		</div>
	</section>

</main>

<?php get_footer(); ?>
