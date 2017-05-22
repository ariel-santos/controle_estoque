<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<?php get_header('includes'); ?>
	</head>
	<body>
		<?php get_header('topo'); ?>	
		<div class="row container">
			<div class="col s12">
				<?php
					while ( have_posts() ) : the_post();
						get_template_part( 'template-parts/content', 'page' );
						
						if ( comments_open() || get_comments_number() ) {
							comments_template();
						}
					endwhile;
				?>
			</div>
		</div>
		<?php get_footer(); ?>
	</body>
</html>