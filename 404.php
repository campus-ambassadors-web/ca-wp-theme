<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap clearfix">

					<div id="main" class="twelvecol first clearfix" role="main">

						<article id="post-not-found" class="hentry clearfix">

							<header class="article-header clearfix">
								
								<img src="<?php echo get_stylesheet_directory_uri() ?>/library/images/sadlogo.png" width="200" height="200" class="sadlogo" />
								<h1>404&mdash;Page not found</h1>

							</header> <!-- end article header -->

							<section class="entry-content">

								<p><?php _e("The page you were looking for was not found, but maybe try looking again!", "bonestheme"); ?></p>

							</section> <!-- end article section -->

							<section class="search">

									<p><?php get_search_form(); ?></p>

							</section> <!-- end search section -->

							<footer class="article-footer">
							</footer> <!-- end article footer -->

						</article> <!-- end article -->

					</div> <!-- end #main -->

				</div> <!-- end #inner-content -->

			</div> <!-- end #content -->

<?php get_footer(); ?>
