<?php
/**
 * Plugin Name:     Acf Display
 * Description:     Example block written with ESNext standard and JSX support – build step required.
 * Version:         22.09.3
 * Author:          The WordPress Contributors
 * License:         GPL-2.0-or-later
 * License URI:     https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     create-block
 * Plugin URI:        https://github.com/zzozz/acf-display
 * GitHub Plugin URI: https://github.com/zzozz/acf-display
 *
 * @package         create-block
 */


function register_acf_block_types_for_acf_display() {

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

	add_action('acf/init', 'register_acf_block_types_for_acf_display');
	