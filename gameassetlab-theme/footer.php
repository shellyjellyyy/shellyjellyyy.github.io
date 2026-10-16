<?php
/**
 * GameAssetLab — Footer Template
 * @package GameAssetLab
 */
?>

<footer class="site-footer" role="contentinfo">
	<div class="site-footer__top">

		<!-- Brand column -->
		<div class="footer-brand">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="site-logo" rel="home" aria-label="<?php esc_attr_e( 'GameAssetLab — home', 'gameassetlab' ); ?>">
				<div class="site-logo__mark" aria-hidden="true">G</div>
				<span class="site-logo__text">Game<span>Asset</span>Lab</span>
			</a>
			<p class="footer-brand__desc">
				Step-by-step Blender tutorials for making low-poly props, UV maps, textures, and optimized assets for real-time game engines.
			</p>
		</div>

		<!-- Tutorials column -->
		<div class="footer-col">
			<h4 class="footer-col__heading"><?php esc_html_e( 'Tutorials', 'gameassetlab' ); ?></h4>
			<ul>
				<li><a href="<?php echo esc_url( home_url( '/tutorials/' ) ); ?>"><?php esc_html_e( 'All Tutorials', 'gameassetlab' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/tutorials/low-poly-modeling/' ) ); ?>"><?php esc_html_e( 'Low-Poly Modeling', 'gameassetlab' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/tutorials/uv-texturing/' ) ); ?>"><?php esc_html_e( 'UV Mapping & Texturing', 'gameassetlab' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/tutorials/game-engine-export/' ) ); ?>"><?php esc_html_e( 'Game Engine Export', 'gameassetlab' ); ?></a></li>
			</ul>
		</div>

		<!-- Resources column -->
		<div class="footer-col">
			<h4 class="footer-col__heading"><?php esc_html_e( 'Resources', 'gameassetlab' ); ?></h4>
			<ul>
				<li><a href="https://docs.blender.org/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Blender Manual', 'gameassetlab' ); ?></a></li>
				<li><a href="https://docs.unity3d.com/Manual/index.html" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Unity Documentation', 'gameassetlab' ); ?></a></li>
				<li><a href="https://www.blender.org/" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Blender.org', 'gameassetlab' ); ?></a></li>
			</ul>
		</div>

		<!-- Site column -->
		<div class="footer-col">
			<h4 class="footer-col__heading"><?php esc_html_e( 'Site', 'gameassetlab' ); ?></h4>
			<ul>
				<li><a href="<?php echo esc_url( home_url( '/about/' ) ); ?>"><?php esc_html_e( 'About', 'gameassetlab' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/sitemap.xml' ) ); ?>"><?php esc_html_e( 'Sitemap', 'gameassetlab' ); ?></a></li>
				<?php if ( get_privacy_policy_url() ) : ?>
					<li><a href="<?php echo esc_url( get_privacy_policy_url() ); ?>"><?php esc_html_e( 'Privacy Policy', 'gameassetlab' ); ?></a></li>
				<?php endif; ?>
			</ul>
		</div>

	</div><!-- /.site-footer__top -->

	<div class="site-footer__bottom">
		<p>
			&copy; <?php echo esc_html( date( 'Y' ) ); ?> GameAssetLab.
			<?php esc_html_e( 'Built for learners making game-ready 3D assets.', 'gameassetlab' ); ?>
		</p>
		<nav class="footer-legal" aria-label="<?php esc_attr_e( 'Legal', 'gameassetlab' ); ?>">
			<?php if ( get_privacy_policy_url() ) : ?>
				<a href="<?php echo esc_url( get_privacy_policy_url() ); ?>"><?php esc_html_e( 'Privacy', 'gameassetlab' ); ?></a>
			<?php endif; ?>
			<a href="<?php echo esc_url( home_url( '/sitemap.xml' ) ); ?>"><?php esc_html_e( 'Sitemap', 'gameassetlab' ); ?></a>
		</nav>
	</div><!-- /.site-footer__bottom -->

</footer>

<?php wp_footer(); ?>
</body>
</html>
