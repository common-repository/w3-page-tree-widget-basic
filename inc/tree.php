<?php

function w3ptw_sidebar_page_tree($pid = null, $cpt = null, &$html = "", $level = 1) {

	// we need to check if it's a single page since on a post archive page get_the_ID()	could return the id for the first post found (which is not what one would expect).
	if($pid == null){
		if(is_singular()){
			$pid = intval(get_the_ID());
		} else {
			$pid = 0;
		}
	}
	
	if($cpt == null){
		$cpt = get_post_type();
	}

	// Get an array of Ancestors and Parents if they exist
	if($pid !== 0){
		//like get_the_ID, get_post_ancestors will return the first post id found on an archive page instead of 0 (which one would expect).
		$parents = get_post_ancestors( $pid );
			
		// The top level parent is the last value.
		$topParentID = ($parents) ? $parents[count($parents)-1]: $pid;		
	} else {
		$topParentID = 0;
	}	

		
	if($level == 1){
		if(w3ptw_has_children($pid, $cpt)){
			$parentID = $pid;
		} else {
			$parentID = $topParentID;
		}
	} else {
		$parentID = $pid;	
	}
	
	$args = array(
		"post_type" => $cpt,
		"post_parent" => $parentID,
		'orderby' => 'menu_order',
		'order' => 'ASC',
		'posts_per_page' => -1,
		'numberposts' => -1,
		'post_status' => 'publish',
	);
		
	$pages = get_posts($args); 
	
	$counter[$level] = 0;
	
	if(!empty($pages)){
		foreach($pages as $page){
			
			$counter[$level] = $counter[$level] + 1; 		
	
			$pid = $page->ID;
	
			//NOTE: data-title is included for diagnostic purposes (it's easier to json arrays by their post title rather than the post id).
	
			$html .= "<li data-id='".$pid."' data-position='".$page->menu_order."' data-counter='".$counter[$level]."'  data-parentid='".$page->post_parent."'  data-name='".$page->post_title."'><a href='".get_permalink($pid)."'>".$page->post_title."</a>";
			

			// If top parent == 0, then only top level pages should be displayed.
			if( w3ptw_has_children($pid, $cpt) ) {	
				$level++;
				$html .= "<ol>";		
				w3ptw_sidebar_page_tree($pid, $cpt, $html, $level);
				$level = $level - 1;				
				$html .= "</ol>";		
			}
			
			$html .= "</li>";
			
	
		}
	} else {
		$html = null;
	}
	

	
	if($html == null){
		return null;
	} else {
		return '<ol id="w3-page-tree-widget" class="w3-page-tree-widget">'.$html.'</ol>';	
	}
	
	return $html;
}