			<?php
			$footer_art_preset = get_theme_mod( 'footer_art_preset', 'hills.png' );
			if ( $footer_art_preset != 'none' ) {
				if ( $footer_art_preset == 'custom' ) {
					// find the source of the custom footer image
					$footer_art = get_theme_mod( 'footer_art', '' );
					if ( !empty( $footer_art ) ) {
						$footer_src_info = wp_get_attachment_image_src( $footer_art, array( 1500, 300 ) );
						$footer_src = $footer_src_info[0];
					}
				} else {
					// preset footer image
					$footer_src = get_stylesheet_directory_uri() . '/library/images/footers/' . $footer_art_preset;
				}
				
				// output the footer image, if we have one
				if ( $footer_src ) {
					?>
					<div id="footer-image-wrap">
						<div id="footer-image-center">
							<img src="<?php echo $footer_src ?>" alt="" id="footer-image" />
						</div>
					</div>
					<?php
				}
			}
			?>
			
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
