<?php

add_action( 'widgets_init', function(){
	register_widget( 'w3ptw_widget' );
});

class w3ptw_widget extends WP_Widget {
	// class constructor
	public function __construct() {
		$widget_ops = array( 
			'classname' => 'w3ptw_widget',
			'description' => 'Displays page hierarchy',
		);
		parent::__construct( 'w3ptw_widget', 'W3 Page Tree', $widget_ops );
	}	

	// output the option form field in admin Widgets screen
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Title', 'w3ptw' );
		
		// TITLE
		
		echo "<p>";
		echo '<label for="'.esc_attr( $this->get_field_id( 'title' ) ).'">';
		echo esc_attr_e( 'Title:', 'w3ptw' );
		echo "</label>"; 

		echo '<input 
			class="widefat" 
			id="'.esc_attr( $this->get_field_id( 'title' ) ).'" 
			name="'.esc_attr( $this->get_field_name( 'title' ) ).'" 
			type="text" 
			value="'.esc_attr( $title ).'">
		</p>';	
	}
	
	// save options
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		$instance['post_type'] = ( ! empty( $new_instance['post_type'] ) ) ? strip_tags( $new_instance['post_type'] ) : '';
	
		return $instance;
	}



	// output the widget content on the front-end
	public function widget( $args, $instance ) {
		
		$pageTree = w3ptw_sidebar_page_tree(null);

		if(!empty($pageTree)){		
			if ( ! empty( $instance['title'] ) ) {
				echo $args['before_title'] . '<span class="w3ptw-title-icon"> ~ </span>' . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
			}
			
			echo $args['before_widget'];	
			echo $pageTree;			
			echo $args['after_widget'];
		}
	
	}
	
}

?>