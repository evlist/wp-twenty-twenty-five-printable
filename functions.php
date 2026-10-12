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
