<?php if ( has_nav_menu( 'primary' ) ) : ?>
<div class="row no-margin red" id="container-menu-topo">
	<nav class="vermelho">
		<div class="col s12 nav-wrapper">
			<a href="#" class="brand-logo">Controle de estoque</a>
			<?php 
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'menu_id' => 'nav-mobile',
					'menu_class'     => 'right hide-on-med-and-down',

				 ) );
			?>
		</div>
	</nav>
<?php endif; ?>	
