<?php

if ( version_compare( $GLOBALS['wp_version'], '4.4-alpha', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}

if ( ! function_exists( 'controle_estoque_setup' ) ) :

function controle_estoque_setup() {

	load_theme_textdomain( 'controle_estoque' );	
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );

	add_theme_support( 'custom-logo', array(
		'height'      => 240,
		'width'       => 240,
		'flex-height' => true,
	) );


	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 1200, 9999 );

	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'controle_estoque' ),
		'social'  => __( 'Social Links Menu', 'controle_estoque' ),
	) );

	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
		'gallery',
		'status',
		'audio',
		'chat',
	) );

	add_editor_style( array( 'css/editor-style.css' ) );

	add_theme_support( 'customize-selective-refresh-widgets' );
}
endif; 
add_action( 'after_setup_theme', 'controle_estoque_setup' );

function controle_estoque_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'controle_estoque_content_width', 840 );
}
add_action( 'after_setup_theme', 'controle_estoque_content_width', 0 );

function controle_estoque_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'controle_estoque' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here to appear in your sidebar.', 'controle_estoque' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

}
add_action( 'widgets_init', 'controle_estoque_widgets_init' );


function controle_estoque_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'controle_estoque_javascript_detection', 0 );

function controle_estoque_scripts() {
	wp_enqueue_style( 'controle_estoque-style', get_stylesheet_uri() );
	wp_enqueue_style( 'materialize-style', get_template_directory_uri() . '/materialize/css/materialize.css' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	wp_enqueue_script( 'materialize-js', get_template_directory_uri() . '/materialize/js/materialize.js', array( 'jquery' ), '20160816', true );
}
add_action( 'wp_enqueue_scripts', 'controle_estoque_scripts' );

function controle_estoque_body_classes( $classes ) {
	if ( get_background_image() ) {
		$classes[] = 'custom-background-image';
	}

	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'controle_estoque_body_classes' );

function controle_estoque_hex2rgb( $color ) {
	$color = trim( $color, '#' );

	if ( strlen( $color ) === 3 ) {
		$r = hexdec( substr( $color, 0, 1 ).substr( $color, 0, 1 ) );
		$g = hexdec( substr( $color, 1, 1 ).substr( $color, 1, 1 ) );
		$b = hexdec( substr( $color, 2, 1 ).substr( $color, 2, 1 ) );
	} else if ( strlen( $color ) === 6 ) {
		$r = hexdec( substr( $color, 0, 2 ) );
		$g = hexdec( substr( $color, 2, 2 ) );
		$b = hexdec( substr( $color, 4, 2 ) );
	} else {
		return array();
	}

	return array( 'red' => $r, 'green' => $g, 'blue' => $b );
}


function controle_estoque_content_image_sizes_attr( $sizes, $size ) {
	$width = $size[0];

	840 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 62vw, 840px';

	if ( 'page' === get_post_type() ) {
		840 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
	} else {
		840 > $width && 600 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 61vw, (max-width: 1362px) 45vw, 600px';
		600 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
	}

	return $sizes;
}
add_filter( 'wp_calculate_image_sizes', 'controle_estoque_content_image_sizes_attr', 10 , 2 );


function controle_estoque_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
	if ( 'post-thumbnail' === $size ) {
		is_active_sidebar( 'sidebar-1' ) && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 60vw, (max-width: 1362px) 62vw, 840px';
		! is_active_sidebar( 'sidebar-1' ) && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 88vw, 1200px';
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'controle_estoque_post_thumbnail_sizes_attr', 10 , 3 );

/*					REGISTRANDO PRODUTO  			*/
add_action('init', 'produto_register');
function produto_register() {
    $labels = array(
        'name' => _x('Produtos', 'post type general name'),
        'singular_name' => _x('Produtos', 'post type singular name'),
        'add_new' => _x('Adicionar Novo', 'produto  item'),
        'add_new_item' => __('Adicionar Novo produto'),
        'edit_item' => __('Editar produto'),
        'new_item' => __('Nova produto'),
        'view_item' => __('Ver produto'),
        'search_items' => __('Procurar produto'),
        'not_found' =>  __('Nada Encontrado'),
        'not_found_in_trash' => __('Nada Encontrado na Lixeira'),
        'parent_item_colon' => ''
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_icon'   => 'dashicons-format-aside',
        'supports' => array('title','editor')
      ); 
    register_post_type( 'produto' , $args );
}

add_action('add_meta_boxes', 'produto_metabox');

function produto_metabox(){
    add_meta_box(
        'produto',
        'Dados produto',
        'produto_html',
        'produto',
        'normal',
        'low'
    );
}

add_action('save_post', 'produto_save');

function produto_save($post_id){
    global $wpdb;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	
	$preco = $_POST['preco'];
	
	$wpdb->replace(
		"ce_produto",
		array(
			"produto_id" => $post_id,
			"preco" => $preco
		)
	);
}

function produto_html($post){
    global $wpdb;
    $post_id = get_the_ID();
	$produto = $wpdb->get_row("SELECT * FROM ce_produto WHERE produto_id = $post_id ");
?>
    <link type="text/css" rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/materialize/css/materialize.css" />
	<script src="<?php echo get_template_directory_uri(); ?>/materialize/js/materialize.min.js"></script>

	<div class="wrap">
		<div class="row">
			<div class="input-field col s12 m4">
				<input type="text" name="preco" id="preco" value="<?php echo $produto->preco; ?>"/>
				<label for="destaque">Preço do produto </label>
	        </div>
		</div>
	</div>
	
<?php
}

/*					REGISTRANDO CLIENTE  			*/
add_action('init', 'cliente_register');
function cliente_register() {
    $labels = array(
        'name' => _x('Cliente', 'post type general name'),
        'singular_name' => _x('Cliente', 'post type singular name'),
        'add_new' => _x('Adicionar Novo', 'cliente  item'),
        'add_new_item' => __('Adicionar Novo cliente'),
        'edit_item' => __('Editar cliente'),
        'new_item' => __('Nova cliente'),
        'view_item' => __('Ver cliente'),
        'search_items' => __('Procurar cliente'),
        'not_found' =>  __('Nada Encontrado'),
        'not_found_in_trash' => __('Nada Encontrado na Lixeira'),
        'parent_item_colon' => ''
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_icon'   => 'dashicons-format-aside',
        'supports' => array('title','editor')
      ); 
    register_post_type( 'cliente' , $args );
}

add_action('add_meta_boxes', 'cliente_metabox');

function cliente_metabox(){
    add_meta_box(
        'cliente',
        'Dados cliente',
        'cliente_html',
        'cliente',
        'normal',
        'low'
    );
}

add_action('save_post', 'cliente_save');

function cliente_save($post_id){
    global $wpdb;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	
	$email = $_POST['email'];
	$telefone = $_POST['telefone'];

	$wpdb->replace(
		"ce_cliente",
		array(
			"cliente_id" => $post_id,
			"email" => $email,
			"telefone" => $telefone
		)
	);
}

function cliente_html($post){
    global $wpdb;
    $post_id = get_the_ID();
	$cliente = $wpdb->get_row("SELECT * FROM ce_cliente WHERE cliente_id = $post_id ");
?>
    <link type="text/css" rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/materialize/css/materialize.css" />
	<script src="<?php echo get_template_directory_uri(); ?>/materialize/js/materialize.min.js"></script>

	<div class="wrap">
		<div class="row">
			<div class="input-field col s12 m6">
				<input type="text" name="email" id="email" value="<?php echo $cliente->email; ?>"/>
				<label for="">Email </label>
	        </div>
	      	<div class="input-field col s12 m6">
				<input type="text" name="telefone" id="telefone" value="<?php echo $cliente->telefone; ?>" />
				<label for="">Telefone </label>
	        </div>
		</div>
	</div>
	
<?php
}

/*					REGISTRANDO PEDIDO  			*/
add_action('init', 'pedido_register');
function pedido_register() {
    $labels = array(
        'name' => _x('Pedidos', 'post type general name'),
        'singular_name' => _x('Pedido', 'post type singular name'),
        'add_new' => _x('Adicionar Novo', 'pedido  item'),
        'add_new_item' => __('Adicionar Novo pedido'),
        'edit_item' => __('Editar pedido'),
        'new_item' => __('Nova pedido'),
        'view_item' => __('Ver pedido'),
        'search_items' => __('Procurar pedido'),
        'not_found' =>  __('Nada Encontrado'),
        'not_found_in_trash' => __('Nada Encontrado na Lixeira'),
        'parent_item_colon' => ''
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => true,
        'capability_type' => 'post',
        'hierarchical' => false,
        'menu_icon'   => 'dashicons-format-aside',
        'supports' => array('title','editor', 'thumbnail')
      ); 
    register_post_type( 'pedido' , $args );
}

add_action('add_meta_boxes', 'pedido_metabox');

function pedido_metabox(){
    add_meta_box(
        'pedido',
        'Dados pedido',
        'pedido_html',
        'pedido',
        'normal',
        'low'
    );
}

add_action('save_post', 'pedido_save');

function pedido_save($post_id){
    global $wpdb;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	
	$cliente_id = explode(" - ", $_POST['cliente']);
	
	$wpdb->replace(
		"ce_pedido",
		array(
			"pedido_id" => $post_id,
			"cliente_id" => $cliente_id[0]
		)
	);
}

function pedido_html($post){
    global $wpdb;
    $post_id = get_the_ID();
	$pedido = $wpdb->get_row("
		SELECT cep.*, concat(wpp.ID, ' - ', wpp.post_title) as cliente_nome
		FROM ce_pedido cep, wp_posts wpp
		WHERE cep.pedido_id = $post_id 
		AND wpp.ID = cep.cliente_id
	");
	
	$clientes = $wpdb->get_results("
		SELECT cec.cliente_id, wpp.post_title
		FROM wp_posts wpp, ce_cliente cec
		WHERE wpp.ID = cec.cliente_id
		AND wpp.post_status = 'publish'
	");
	
	foreach( $clientes as $c ){
		$nome = $c->cliente_id . " - " .$c->post_title;
		$dados[$nome] = null;
	}
?>
    <link type="text/css" rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/materialize/css/materialize.css" />
	<script src="<?php echo get_template_directory_uri(); ?>/materialize/js/materialize.min.js"></script>
	<script>
		jQuery(document).ready(function(data){
			jQuery('input.autocomplete').autocomplete({
				limit: 20,
				data: <?php echo json_encode($dados); ?>
			});
		});
	</script>
	<div class="wrap">
		<div class="row">
	        <div class="input-field col s12 m6">
	        	<input type="text" id="autocomplete-input" name="cliente" class="autocomplete" value="<?php echo $pedido->cliente_nome; ?>">
				<label>Cliente</label>
			</div>
		</div>
	</div>
	
<?php
}