<?php 


         /**
          * These Functions are Specific to the Advanced Sidebar Menu
          * @author Mat Lipe
          * @since 10.12.12
          */
class advancedSidebarMenu{
	     private $instance; //The widget instance 
         private $top_id; //Either the top cat or page
         private $exclude;
         private $ancestors; //For the category ancestors
	     
	     
	     /**
	      * Adds the class for current page item etc to the page list when using a custom post type
	      * @param array $css the currrent css classes
	      * @param obj $this_menu_item the page being checked
	      * @return array
	      * @since 10.10.12
	      */
	     function custom_post_type_css($css, $this_menu_item){
	         global $post;
	         if ( isset($post->ancestors) && in_array($this_menu_item->ID, (array)$post->ancestors) ){
	             $css[] = 'current_page_ancestor';
	         }
	         if ( $this_menu_item->ID == $post->ID ){
	             $css[] = 'current_page_item';
	         
	         } elseif ($this_menu_item->ID == $post->post_parent ){
	             $css[] = 'current_page_parent';
	         }
	         return $css;
	     }
	     

	
	/**
	 * Sets the instance of this widget to this class
	 * @param array $instance the widgets instance
	 * @since 7/16/12
	 */
	function set_widget_vars( $instance, $top_id, $exclude, $ancestors = array() ){
		$this->instance = $instance;
		$this->top_id = $top_id;
		$this->exclude = $exclude;
		$this->ancestors = $ancestors;
	}
	
	/**
	 * 
	 * IF this is a top level category
	 * @param obj $cat the cat object
	 */
	function first_level_category( $cat ){
		if( !in_array($cat->cat_ID, $this->exclude) && $cat->parent == $this->top_id){
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * If the cat is a second level cat
	 * @param obj $cat the cat
	 * @since 10.12.12
	 */
	function second_level_cat( $child_cat ){
		//if this is the currrent cat or a parent of the current cat
		if( $child_cat->cat_ID == get_query_var('cat' ) || in_array( $child_cat->cat_ID, $this->ancestors )){
			$all_children = array();
			$all_children = get_categories( array( 'child_of' => $child_cat->cat_ID ) );
			if( !empty( $all_children ) ){
				return true;
			} else {
				return false;
			}
			
		} else {
			return false;
		}
	}
	
	/**
	 * Determines if all the children should be included
	 * @since 7/16/12
	 * @return bool
	 */
	function display_all(){
		if( $this->instance['display_all'] == 'checked' ){
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * 
	 * Returns and array of all the children of a page
	 * @param int $pID the id of the page
	 * @since 10.5.12
	 */
	function page_children( $pID ){
		global $wpdb, $table_prefix;
		return $wpdb->get_results( "SELECT ID FROM ".$table_prefix."posts WHERE post_parent = ".$pID." AND post_status='publish'" );
		
	}
	
	/**
	 * 
	 * Determines if this is an ancestor or the current post
	 * @param obj $pID the post object
	 * @since 7/19/12
	 */
	function page_ancestor( $pID ){
		global $post;
		if($pID->ID == $post->ID or $pID->ID == $post->post_parent or @in_array($pID->ID, $post->ancestors) ){
			return true;
		} else {
			return false;
		}
	}
	
	
	/**
	 * Determines if the parent page or cat should be included
	 * @since 7/16/12
	 * @return bool
	 */
	function include_parent(){
		if( ($this->instance['include_parent'] == 'checked') && (!in_array($this->top_id, $this->exclude)) ){
			return true;
		} else {
			return false;
		}
	}
	
   
	/**
	 * Echos the title of the widget to the page
	 * @since 7/16/12
	 */
	function title(){
	    if( $this->instance['title'] != '' ){
	     	echo '<h4 class="widgettitle">' . $this->instance['title'] . '</h4>';
     	}
		
	}
	
	
	/**
	 * 
	 * Checks is this id is excluded or not
	 * @param int $id the id to check
	 * @return bool
	 */
	function exclude( $id ){
		if( !in_array( $id, $this->exclude ) ){
			return true;
		} else {
			return false;
		}
	}
	


	/**
 	* Allows for Overwritting files in the child theme
 	* @since 6/3/12
 	* @param string $file the name of the file to overwrite
 	*/
         
	 static function file_hyercy( $file ){
		if ( $theme_file = locate_template(array('advanced-sidebar-menu/'.$file)) ) {
			$file = $theme_file;
		} else {
			$file = ADVANCED_SIDEBAR_VIEWS_DIR . $file;
		}
		return $file;
	
	}

} //End class
