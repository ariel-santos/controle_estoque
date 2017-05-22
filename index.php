<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<?php get_header('includes'); ?>
	</head>
	<body>
		<?php get_header('topo'); ?>	
		<div class="row container">
			<div class="col s12 m9">
				<?php
					while ( have_posts() ) : the_post();
						get_template_part( 'template-parts/content', get_post_format() );
					endwhile;

					the_posts_pagination( array(
						'prev_text'          => __( 'Previous page', 'twentysixteen' ),
						'next_text'          => __( 'Next page', 'twentysixteen' ),
						'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page', 'twentysixteen' ) . ' </span>',
					) );
				?>		
			</div>
			<div class="col s12 m3">
				<?php get_sidebar(); ?>		
			</div>
		</div>
		<?php get_footer(); ?>
	</body>
</html>
			

