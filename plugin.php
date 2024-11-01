<?php
/**
 * Plugin Name: W3 Page Tree Widget (Basic)
 * Description: WordPress page tree widget.
 * Author: bookbinder
 * Version: 1.0
 */

function w3ptw_get_plugin_directory(){
	$directory = array();
	
	$directory['path'] = trailingslashit( plugin_dir_path( __FILE__ ) );
	$directory['url'] = plugin_dir_url( __FILE__ );
	return $directory;
}

include("inc/tree.php");
include("inc/widget.php");


# Include wiki css file on single wiki pages. 
function w3ptw_assets() {
	
	$pluginDirectory = w3ptw_get_plugin_directory();

	# REGISTER STYLES
	wp_register_style( 'w3-page-tree',  $pluginDirectory['url'].'assets/css/app.css', array(), 1 );


	# REGISTER SCRIPTS

	
	wp_register_script( "w3-page-tree", $pluginDirectory["url"].'assets/js/app.js', array(), null, true );


	# ENQUEUE
	wp_enqueue_script('jquery-sortable');	
	wp_enqueue_script('jquery-treemenu');	
	wp_enqueue_script('w3-page-tree');

	wp_enqueue_style('w3-page-tree');	

}
add_action ('wp_enqueue_scripts', 'w3ptw_assets', 100);


function w3ptw_has_children($pid, $cpt) {
	$args = array(
		'post_type' => $cpt,	
		'post_parent' => intval($pid),
		'posts_per_page' => 1,
		'numberposts' => 1,			
	);
	
	$children = get_posts($args);
	if( count( $children ) > 0 ) {
		return true;
	} else {
		return false;
	}
} 
