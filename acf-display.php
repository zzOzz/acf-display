<?php
/**
 * Plugin Name:     Acf Display
 * Description:     Example block written with ESNext standard and JSX support – build step required.
 * Version:         0.1.0
 * Author:          The WordPress Contributors
 * License:         GPL-2.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     create-block
 *
 * @package         create-block
 */

/**
 * Registers all block assets so that they can be enqueued through the block editor
 * in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/tutorials/block-tutorial/applying-styles-with-stylesheets/
 */
function create_block_acf_display_block_init() {
	$dir = dirname( __FILE__ );

	$script_asset_path = "$dir/build/index.asset.php";
	if ( ! file_exists( $script_asset_path ) ) {
		throw new Error(
			'You need to run `npm start` or `npm run build` for the "create-block/acf-display" block first.'
		);
	}
	$index_js     = 'build/index.js';
	$script_asset = require( $script_asset_path );
	wp_register_script(
		'create-block-acf-display-block-editor',
		plugins_url( $index_js, __FILE__ ),
		$script_asset['dependencies'],
		$script_asset['version']
	);

	$editor_css = 'editor.css';
	wp_register_style(
		'create-block-acf-display-block-editor',
		plugins_url( $editor_css, __FILE__ ),
		array(),
		filemtime( "$dir/$editor_css" )
	);

	$style_css = 'style.css';
	wp_register_style(
		'create-block-acf-display-block',
		plugins_url( $style_css, __FILE__ ),
		array(),
		filemtime( "$dir/$style_css" )
	);

	register_block_type( 'create-block/acf-display', array(
		'editor_script' => 'create-block-acf-display-block-editor',
		'editor_style'  => 'create-block-acf-display-block-editor',
		'style'         => 'create-block-acf-display-block',
	) );
}

// add_action( 'init', 'create_block_acf_display_block_init' );


function register_acf_block_types_for_acf_display() {
	// echo '<code>'.json_encode(acf_get_field_groups(),JSON_PRETTY_PRINT).'</code><br><br>';
	foreach(acf_get_field_groups() as $_field_group) {
		// register a testimonial block.
		acf_register_block_type(array(
			'name'              => 'acfviewer'.$_field_group["ID"],
			'title'             => __('acfviewer ').$_field_group["title"],
			'description'       => __('A custom acf-viewer block.'),
			'render_template'   => plugin_dir_path( __FILE__ ) . 'template-parts/acf-viewer.php',
			'category'          => 'formatting',
			'icon'              => 'admin-comments',
			'keywords'          => array( 'acf-viewer', 'quote' ),
		));
	}

	wp_enqueue_style(
		'uikit_css',
		'https://cdn.jsdelivr.net/npm/uikit@3.4.6/dist/css/uikit.min.css',
		array()
	);

	wp_enqueue_script(
		'uikit_js',
		'https://cdn.jsdelivr.net/npm/uikit@3.4.6/dist/js/uikit.min.js',
		array()
	);

	wp_enqueue_script(
		'uikit_icon_js',
		'https://cdn.jsdelivr.net/npm/uikit@3.4.6/dist/js/uikit-icons.min.js',
		array()
	);
}

// wp_enqueue_style(
//     'materialize_css',
//     'https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css',
//     array()
// );

// wp_enqueue_script(
//     'materialize_js',
//     'https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js',
//     array()
// );


// Check if function exists and hook into setup.
// if( function_exists('acf_register_block_type') ) {
	add_action('acf/init', 'register_acf_block_types_for_acf_display');
	
// } else {
	// echo 'fuckok';
// }


add_action(
	'rest_api_init',
	function () {

		if ( ! function_exists( 'use_block_editor_for_post_type' ) ) {
			require ABSPATH . 'wp-admin/includes/post.php';
		}

		// Surface all Gutenberg blocks in the WordPress REST API
		$post_types = get_post_types_by_support( [ 'editor' ] );
		foreach ( $post_types as $post_type ) {
			if ( use_block_editor_for_post_type( $post_type ) ) {
				register_rest_field(
					$post_type,
					'blocks',
					[
						'get_callback' => function ( array $post ) {
							return parse_blocks( $post['content']['raw'] );
						},
					]
				);
			}
		}
	}
);
