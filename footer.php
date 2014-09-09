			<?php
			$footer_art = get_theme_mod( 'footer_art', '' );
			if ( !empty( $footer_art ) ) :
				$footer_src = wp_get_attachment_image_src( $footer_art, array( 1500, 300 ) );
				if ( $footer_src ) :
					?>
					<div id="footer-image-wrap">
						<div id="footer-image-center">
							<img src="<?php echo $footer_src[0] ?>" alt="" id="footer-image" />
						</div>
					</div>
				<?php endif; ?>
			<?php endif; ?>
			
			<footer class="footer clearfix" role="contentinfo">
				<div id="inner-footer" class="wrap clearfix">

					<nav role="navigation">
							<?php bones_footer_links(); ?>
					</nav>
					<?php if ( get_theme_mod('sm_footer') ) show_social_media_icons() ?>

				</div> <!-- end #inner-footer -->
				
				<p class="source-org copyright">&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
			</footer> <!-- end footer -->

		</div> <!-- end #container -->
		
		<div id="size-feedback">
			<div id="size-1030">1030</div>
			<div id="size-1240">1240</div>
			<div id="size-768">768</div>
			<div id="size-481">481</div>
			<div id="size-base">base</div>
		</div>
		
		<!-- all js scripts are loaded in library/bones.php -->
		<?php wp_footer(); ?>
		
	</body>

</html> <!-- end page. what a ride! -->
