<?php
/**
 * Template Name: About
 *
 * GameAssetLab — About Page (page-about.php)
 * URL: /about/
 *
 * Assign this template to the WordPress Page with slug "about".
 * WordPress loads this file directly via the Page Template system,
 * so get_header() / get_footer() are correct here — page.php is bypassed.
 *
 * @package GameAssetLab
 */

get_header();
?>

<main id="main" tabindex="-1">

	<!-- About hero -->
	<div class="about-hero">
		<div class="container">
			<?php gal_breadcrumbs(); ?>
			<div style="max-width: 44ch; margin-top: var(--space-6);">
				<span class="section-eyebrow">What this is and why it exists</span>
				<h1 style="font-family:var(--font-display); font-size:var(--text-3xl); color:var(--color-paper); line-height:1.15; margin-bottom:var(--space-6);">
					About GameAssetLab
				</h1>
				<p style="font-size:var(--text-md); color:rgba(244,241,236,0.65); line-height:1.7;">
					GameAssetLab is an educational website focused on helping beginners understand the full
					workflow for creating low-poly props in Blender and preparing those assets for use in
					real-time game engines.
				</p>
			</div>
		</div>
	</div>

	<section class="section section--paper">
		<div class="container">
			<div style="display:grid; grid-template-columns:1fr 1fr; gap:var(--space-16); align-items:start;">
				<div>
					<h2 style="font-size:var(--text-2xl); margin-bottom:var(--space-6);">The problem this site addresses</h2>
					<p>
						Learning to create a game-ready asset in Blender typically requires consulting several
						different resources: one for modeling, another for UV unwrapping, a third for texturing,
						and separate documentation for each game engine you want to target.
					</p>
					<p>
						The transition between these stages is where beginners tend to get stuck. A tutorial that
						shows you how to model a barrel does not necessarily tell you how to unwrap it, or how to
						move the finished prop into Unity without scale or material problems.
					</p>
					<p>
						GameAssetLab organizes the stages into a connected sequence. The tutorials follow the same
						example prop through modeling, UV unwrapping, texturing, and FBX export. This means the
						decisions made at one stage carry forward into the next, and a beginner can see how the
						whole process fits together rather than encountering each step in isolation.
					</p>

					<h2 style="font-size:var(--text-2xl); margin-top:var(--space-12); margin-bottom:var(--space-6);">What this site covers</h2>
					<ul style="list-style:none; padding:0; display:flex; flex-direction:column; gap:var(--space-3);">
						<?php
						$topics = [
							'Low-poly modeling in Blender — geometry, edge flow, and polygon budget',
							'UV unwrapping — seams, UV islands, packing, and texel density',
							'Material and texture setup for game-ready props',
							'FBX export from Blender into Unity — scale, axes, and material handoff',
						];
						foreach ( $topics as $t ) :
						?>
						<li style="display:flex; gap:var(--space-3); align-items:flex-start;">
							<span style="color:var(--color-accent); font-weight:700; margin-top:2px;" aria-hidden="true">—</span>
							<?php echo esc_html( $t ); ?>
						</li>
						<?php endforeach; ?>
					</ul>

					<h2 style="font-size:var(--text-2xl); margin-top:var(--space-12); margin-bottom:var(--space-6);">What this site does not claim</h2>
					<p>
						GameAssetLab is a student-built educational website developed as part of a university SEO project (CSET489 — Search Engine Optimization, Bennett University, B.Tech CSE Semester VII, 2026–27).
					</p>
					<p>
						The keyword data underpinning the site's structure was collected using freely available keyword suggestion tools. Most terms showed reported volumes below 100 searches per month; exact search counts and guaranteed ranking outcomes cannot be verified from this data alone.
					</p>
					<p>
						No fabricated testimonials, fake analytics, invented community statistics, or misleading performance claims appear on this site. Tutorial content reflects accurate Blender and Unity workflows, with external references to official documentation where appropriate.
					</p>
				</div>

				<div>
					<!-- Pillars -->
					<div class="about-pillars">
						<div class="pillar">
							<span class="pillar__number">01</span>
							<h3 class="pillar__title">Connected workflow</h3>
							<p class="pillar__text">Each tutorial follows the same example prop through a different stage of production, so you can see how modeling decisions affect UV unwrapping, and how UV layout choices affect texturing.</p>
						</div>
						<div class="pillar">
							<span class="pillar__number">02</span>
							<h3 class="pillar__title">Beginner-friendly explanations</h3>
							<p class="pillar__text">Instructions assume you know how to open Blender. Each step explains not just what to do, but why — including common mistakes and how to recognize them.</p>
						</div>
						<div class="pillar">
							<span class="pillar__number">03</span>
							<h3 class="pillar__title">Accurate technical details</h3>
							<p class="pillar__text">Technical claims are cross-checked against Blender's official documentation and Unity's import reference. Where something is uncertain or unverified, the tutorial says so.</p>
						</div>
					</div>

					<!-- Keyword strategy note (honest disclosure) -->
					<div class="callout callout--note" style="margin-top:var(--space-8);">
						<div class="callout__icon" aria-hidden="true">
							<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
						</div>
						<div class="callout__body">
							<p class="callout__title">A note on search engine optimization</p>
							<p style="font-size:var(--text-sm); color:var(--color-ink-60); margin-bottom:0;">
								This site was built with an explicit focus on on-page SEO, technical performance, and keyword-mapped content. The keyword strategy targets low-competition informational terms in the Blender and game-asset space. Actual ranking outcomes depend on many factors beyond the site's control and have not been measured yet. Data collection will begin after deployment and Search Console verification.
							</p>
						</div>
					</div>

					<!-- Software versions note -->
					<div style="margin-top:var(--space-6); background:var(--color-paper-dark); border:1px solid var(--color-border-light); border-radius:var(--radius-md); padding:var(--space-6);">
						<p style="font-family:var(--font-mono); font-size:var(--text-xs); color:var(--color-ink-40); text-transform:uppercase; letter-spacing:0.1em; margin-bottom:var(--space-4);">Software covered</p>
						<dl style="display:grid; gap:var(--space-3);">
							<div>
								<dt style="font-size:var(--text-xs); color:var(--color-ink-40);">3D software</dt>
								<dd style="font-weight:600;">Blender 4.x (current LTS releases)</dd>
							</div>
							<div>
								<dt style="font-size:var(--text-xs); color:var(--color-ink-40);">Game engine (export tutorials)</dt>
								<dd style="font-weight:600;">Unity (recent LTS releases)</dd>
							</div>
							<div>
								<dt style="font-size:var(--text-xs); color:var(--color-ink-40);">Cost</dt>
								<dd style="font-weight:600;">Blender is free and open-source. Unity is free for personal use under the Personal plan.</dd>
							</div>
						</dl>
					</div>
				</div>
			</div>
		</div>
	</section>

	<!-- CTA -->
	<section class="section section--dark" style="text-align:center;">
		<div class="container--sm">
			<h2 style="font-family:var(--font-display); font-size:var(--text-2xl); color:var(--color-paper); margin-bottom:var(--space-4);">
				Start with the first tutorial
			</h2>
			<p style="color:rgba(244,241,236,0.6); margin-bottom:var(--space-8);">
				The barrel modeling tutorial is the best entry point. It requires only Blender and about 45 minutes.
			</p>
			<a href="<?php echo esc_url( home_url( '/tutorials/low-poly-modeling/low-poly-barrel-blender/' ) ); ?>" class="btn btn-primary btn-lg">
				Go to the barrel tutorial
			</a>
		</div>
	</section>

</main>

<?php get_footer(); ?>
