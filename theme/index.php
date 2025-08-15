<?php
/**
 * The main template file
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists
 *
 * @package WordPress
 * @subpackage Sapling
 * @since Sapling 0.0.1
 */

$context   = Timber::context();
$templates = array( 'index.twig' );
if ( is_home() ) {
	array_unshift( $templates, 'front-page.twig', 'home.twig' );
}
//$context['foo'] = 'bar'; // Example of adding a variable to the context
Timber::render( $templates, $context );
