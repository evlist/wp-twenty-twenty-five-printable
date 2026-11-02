<?php
/**
 * SPDX-FileCopyrightText: 2026 Eric van der Vlist <vdv@dyomedea.com>
 * SPDX-License-Identifier: GPL-3.0-or-later
 *
 * Child theme of Twenty Twenty-Five: loads the style.css of the child theme, which a block theme does not do by itself.
 *
 * @package Twenty_Twenty_Five_Printable
 */

defined( 'ABSPATH' ) || exit;

/**
 * Loads the style.css of this theme after the styles of the parent, on the site and in the editors of blocks.
 *
 * @return void
 */
function twentytwentyfive_printable_styles() {
	wp_enqueue_style(
		'twentytwentyfive-printable-style',
		get_stylesheet_uri(),
		array( 'twentytwentyfive-style' ),
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'twentytwentyfive_printable_styles' );

/**
 * Loads the same file in the editors, so that what is written in the templates looks the same there.
 *
 * @return void
 */
function twentytwentyfive_printable_editor_styles() {
	add_editor_style( 'style.css' );
}
add_action( 'after_setup_theme', 'twentytwentyfive_printable_editor_styles' );

/**
 * Declares the image sizes of the print modes, for the module Books of Otherguise.
 *
 * Only names and fractions of the usable width of the page (the page minus its margins) are given here: Books
 * computes the pixels for 300 ppi and registers the sizes. Harmless when the plugin is not active.
 *
 * - books-print-full: the main photo of the print templates (about 0.9 of the usable width).
 * - books-print-thumb: a thumbnail of the gallery in the right column of publication-randonnee-print (2 columns in
 *   70% of the width), cropped 16:9 (about 0.34 of the usable width, 0.36 kept: a little above the measure).
 * - books-print-half: a thumbnail of the full-width gallery (2 columns) of journee-print and of the monthly
 *   summary, cropped 16:9 (about 0.49 of the usable width, 0.5 kept).
 *
 * @param array $sizes Sizes declared so far.
 * @return array
 */
function twentytwentyfive_printable_books_print_sizes( $sizes ) {
	$sizes['books-print-full']  = array( 'fraction' => 0.9 );
	$sizes['books-print-thumb'] = array(
		'fraction' => 0.36,
		'ratio'    => '16:9',
	);
	$sizes['books-print-half']  = array(
		'fraction' => 0.5,
		'ratio'    => '16:9',
	);
	return $sizes;
}
add_filter( 'books_print_sizes', 'twentytwentyfive_printable_books_print_sizes' );

/**
 * Registers the custom field "byline" (the line of credits of a post or a page) so that it shows in the block editor.
 *
 * @return void
 */
function twentytwentyfive_printable_register_byline_meta() {
	foreach ( array( 'post', 'page' ) as $post_type ) {
		register_post_meta(
			$post_type,
			'byline',
			array(
				'type'              => 'string',
				'single'            => true,
				'show_in_rest'      => true,
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
	}
}
add_action( 'init', 'twentytwentyfive_printable_register_byline_meta' );

/**
 * Block binding source "wp-twenty-twenty-five-printable/byline": the custom field "byline" of the post when it is filled in, the
 * name of the author of the post otherwise.
 *
 * @param array         $source_args    Arguments of the binding (unused).
 * @param WP_Block|null $block_instance The block that is bound.
 * @return string
 */
function twentytwentyfive_printable_byline_value( $source_args, $block_instance ) {
	unset( $source_args );
	$post_id = ( $block_instance && ! empty( $block_instance->context['postId'] ) ) ? (int) $block_instance->context['postId'] : (int) get_the_ID();
	if ( ! $post_id ) {
		return '';
	}
	$byline = trim( (string) get_post_meta( $post_id, 'byline', true ) );
	if ( '' === $byline ) {
		$byline = (string) get_the_author_meta( 'display_name', (int) get_post_field( 'post_author', $post_id ) );
	}
	return esc_html( $byline );
}

/**
 * Declares the binding source above (WordPress 6.5 or later; nothing happens on an older version).
 *
 * @return void
 */
function twentytwentyfive_printable_register_byline_source() {
	if ( ! function_exists( 'register_block_bindings_source' ) ) {
		return;
	}
	register_block_bindings_source(
		'wp-twenty-twenty-five-printable/byline',
		array(
			'label'              => __( 'Byline or author', 'twentytwentyfive-printable' ),
			'get_value_callback' => 'twentytwentyfive_printable_byline_value',
			'uses_context'       => array( 'postId' ),
		)
	);
}
add_action( 'init', 'twentytwentyfive_printable_register_byline_source' );
