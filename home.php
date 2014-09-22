<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap clearfix">

						<div id="main" class="eightcol first clearfix" role="main">
							<?php
							// display the page content before showing the blog
							$secondary_nav = false;
							if ( 'page' == get_option('show_on_front') && get_option( 'page_for_posts' ) && is_home() && intval(get_query_var('paged')) == 0 ) : 
								$page_for_posts_id = get_option( 'page_for_posts' );
								$post = get_page( $page_for_posts_id );
								setup_postdata( $post );
								$secondary_nav = get_secondary_nav( $id );
								?>
									
									<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">
										<header class="article-header">
											<?php if ( !is_front_page() ) : ?>
												<h1 class="page-title" itemprop="headline"><?php the_title(); ?></h1>
											<?php endif; ?>
										</header> <!-- end article header -->
		
										<section class="entry-content clearfix" itemprop="articleBody">
											<?php the_content(); ?>
											<hr />
										</section> <!-- end article section -->
									</article> <!-- end article -->
								<?php	
								rewind_posts();
							endif;
							
							// end page content. now show blog posts
							?>
							
							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class('clearfix'); ?> role="article">

								<header class="article-header">

									<h1 class="h2"><a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a></h1>
									<p class="byline vcard"><?php
										printf(__('<span class="author"><i class="icon-pencil"></i> By %3$s</span> <i class="icon-calendar"></i> <time class="updated" datetime="%1$s" pubdate>%2$s</time> <i class="icon-folder-open"></i> %4$s', 'bonestheme'), get_the_time('Y-m-j'), get_the_time(get_option('date_format')), bones_get_the_author_posts_link(), get_the_category_list(', '));
									?></p>

								</header> <!-- end article header -->

								<section class="entry-content clearfix">
									<?php the_content(); ?>
								</section> <!-- end article section -->

								<footer class="article-footer">
									<p class="tags"><?php the_tags('<i class="icon-tags"></i> ', ', ', ''); ?></p>

								</footer> <!-- end article footer -->

								<?php // comments_template(); // uncomment if you want to use them ?>

							</article> <!-- end article -->

							<?php endwhile; ?>

							<?php if (function_exists('bones_page_navi')) { ?>
									<?php bones_page_navi(); ?>
							<?php } else { ?>
									<nav class="wp-prev-next">
											<ul class="clearfix">
												<li class="prev-link"><?php next_posts_link(__('&laquo; Older Entries', "bonestheme")) ?></li>
												<li class="next-link"><?php previous_posts_link(__('Newer Entries &raquo;', "bonestheme")) ?></li>
											</ul>
									</nav>
							<?php } ?>
							
							
							<footer class="article-footer">
								<?php
								if ( !empty( $secondary_nav ) && $secondary_nav != false ) : ?>
									<nav id="mobile-child-nav" class="child-nav">
										<?php echo $secondary_nav; ?>
									</nav>
								<?php endif; ?>
							</footer> <!-- end article footer -->

							<?php else : ?>

									<article id="post-not-found" class="hentry clearfix">
											<header class="article-header">
												<h1><?php _e("Oops, Post Not Found!", "bonestheme"); ?></h1>
										</header>
											<section class="entry-content">
												<p><?php _e("Uh Oh. Something is missing. Try double checking things.", "bonestheme"); ?></p>
										</section>
										<footer class="article-footer">
												
										</footer>
									</article>

							<?php endif; ?>

						</div> <!-- end #main -->
						
						<div class="all-sidebars fourcol last clearfix">
							<?php
								if ( !empty( $secondary_nav ) ) { ?>
									<nav id="desktop-child-nav" class="child-nav sidebar">
										<?php echo $secondary_nav; ?>
									</nav> <?php
								}
								
								$num_sidebars = get_theme_mod('number_of_sidebars', '');
								if ( $num_sidebars == 'two' ) {
									get_sidebar('sidebar2');
								} else get_sidebar();
							?>
						</div>

				</div> <!-- end #inner-content -->

			</div> <!-- end #content -->

<?php get_footer(); ?>
