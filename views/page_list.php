<?php 



/**
 * The Ouput of tad Advanced Sidebar Page Widget
 * @author Mat Lipe
 * @since 7/16/12
 *
 *
 * @uses to edit, create a file named page_list.php and put in a folder in the your theme called 'advanced-sidebar-menu
 * @uses copy the contents of the file into that file and edit at will
 * @uses Do not edit this file in its original location or it will break on upgrade
 */




//Start the menu
echo '<div id="'.$args['widget_id'].'" class="advanced-sidebar-menu widget advanced-sidebar-page">
			<div class="widget-wrap">';
     
     if( $instance['title'] != '' ){
	     echo '<h4 class="widgettitle">' . $instance['title'] . '</h4>';
     }

	#-- if the checkbox to include parent is checked
	if( $instance['include_parent'] == 'checked' ){
		echo   '<ul class="parent-sidebar-menu" >';

		#-- If the page is not excluded from the menu
		if( !in_array($top_parent, $exclude) ){
			#-- list the parent page
			wp_list_pages("sort_column=menu_order&title_li=&echo=1&depth=1&include=".$top_parent);
		}

	}




//If there are children start the Child Sidebar Menu
if( $result != FALSE ){
	echo '<ul class="child-sidebar-menu">';

	#-- If they want all the pages displayed always
	if( $instance['display_all'] == 'checked' ){
		wp_list_pages("sort_column=menu_order&title_li=&echo=1&child_of=".$top_parent."&depth=".$instance['levels']."&exclude=".$instance['exclude']);
	} else {

		#-- Display children of current page's parent only
		foreach($result as $pID){

				#-- If the page is not in the excluded ones
			if( !in_array($pID->ID, $exclude) ){
					#--echo the current page from the $result
				wp_list_pages("sort_column=menu_order&title_li=&echo=1&depth=1&include=".$pID->ID);
			}

			#-- if the link that was just listed is the current page we are on
			if($pID->ID == $post->ID or $pID->ID == $post->post_parent or @in_array($pID->ID, $post->ancestors) ){

				//must be done this way to prevent bloat or doubling up
				$grandkids = $wpdb->get_results( "SELECT ID FROM ".$table_prefix."posts WHERE post_parent = ".$pID->ID." AND post_type='page' AND post_status='publish'" );
				if( $grandkids ){

					#-- Create a new menu with all the children under it
					echo '<ul class="grandchild-sidebar-menu">';

							wp_list_pages("sort_column=menu_order&title_li=&echo=1&exclude=".$instance['exclude']."&depth=3&child_of=".$pID->ID);

					echo '</ul>';
				}
			}
		}
	}

	#-- Close the First Level menu
	echo '</ul><!-- End child-sidebar-menu -->';

}


	#-- If there was a menu close it off
	if($child_pages != false || ($instance['include_childless_parent'] == 'checked') ){

			if( $instance['include_parent'] == 'checked' ) {
				echo '</ul>';
			}
		echo '</div></div><!-- end of very-custom-menu -->';
	}
	
