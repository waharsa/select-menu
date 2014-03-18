<?php
	/**
 * Plugin Name: Select menu
 * Plugin URI: http://waharsa.com
 * Description: A widget that can make select menu for custom menu.
 * Version: 0.2
 * Author: Agus Sabda
 * Author URI: http://waharsa.com
 */
 
add_action( 'widgets_init', 'select_menuna' );

function select_menuna(){
	register_widget('select_menu');
}

class Select_Menu extends WP_Widget{
	
	function select_menu(){
		$widget_ops = array( 
			'classname'   => 'select_menu_widget', 
			'description' => __('Select menu to showing custom menu.', 'SelectArchives') 
		);

		$control_ops = array( 
			'width'   => 250, 
			'height'  => 350, 
			'id_base' => 'select_menu-widget' 
		);

		$this->WP_Widget( 'select_menu-widget', __('Select menu to showing custom menu', 'SelectArchives'), $widget_ops, $control_ops );
	}
	
	function widget($args, $instance){
		global $wpdb;
		extract($args);
		
		$themenu_name = apply_filters('widget_menu', $instance['themenu'] );
		
		echo $before_widget;
		$menu_name = $themenu_name;

		if ( isset( $menu_name )) {
			$menu = wp_get_nav_menu_object( $menu_name );
		
			$menu_items = wp_get_nav_menu_items($menu->term_id);
		
			$menu_list = '<select id="menu-' . $menu_name . '" onchange="location.href=this.options[this.selectedIndex].value;">';
			$menu_list .= '<option value="">- Select menu -</option>';
		
				foreach ( (array) $menu_items as $key => $menu_item ) {
					$title = $menu_item->title;
					$url = $menu_item->url;
					$menu_list .= '<option value="'.$url.'">' . $title . '</option>';
				}
			$menu_list .= '</select>';
		} else {
			$menu_list = '<ul><li>Menu "' . $menu_name . '" not defined.</li></ul>';
		}
		echo $menu_list;	
		echo $after_widget;
	}
	

	function update( $new_instance, $old_instance ){
		$instance = $old_instance;
		
		$instance['themenu']  = $new_instance['themenu'];
		return $instance;
	}
	
	function form($instance){
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		
		?>
        <p>
			<label ><?php _e('Select Menu:', 'hybrid'); ?></label>
            <select name="<?php echo $this->get_field_name( 'themenu' ); ?>">
            	<?php 
				$nav_menus = wp_get_nav_menus();
				if ( empty( $nav_menus ) || ! is_array( $nav_menus ) )
					return;
		
				foreach ( $nav_menus as $menu ) {
					if($menu->slug=="main-menu"){
						$menu->slug="primary-menu";
					}
					if($menu->slug==$instance['themenu']){
						$selek="selected=selected";
					}else{
						$selek="";
					}
					echo "<option value=$menu->slug $selek>$menu->name</option>";
				}
		?>
            </select>
		</p>
        <?php 
	}
}
?>
