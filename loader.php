<?php
/*
Plugin Name: Read More, Copy Link
Plugin URI: http://www.stormation.info/portfolio-item/read-more-copy-link/
Version: 1.0.2
Author: Eliott Robson
Author URI: http://stormation.info
Description: Ever wanted to attach a read more link to copied content? Now you can, introducing - Read More, Copy Link.
Tested up to: 3.4.1
*/

/* Only load code that needs WordPress to run once WP is loaded and initialized. */
function read_more_copy_link_init_loader() {
    require( dirname( __FILE__ ) . '/read-more-copy-link.php' );
}
add_action( 'init', 'read_more_copy_link_init_loader' );

/* If you have code that does not need WordPress to run, then add it here. */
?>