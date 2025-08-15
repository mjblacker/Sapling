<?php
/**
 * @package WordPress
 * @subpackage Sapling
 * @since Sapling 0.0.1
 */

require_once 'vendor/autoload.php';

Timber\Timber::init();
Timber::$dirname    = array( 'views', 'blocks' );
Timber::$autoescape = false;

// Autoloaded through composer configuration
new Sapling();
