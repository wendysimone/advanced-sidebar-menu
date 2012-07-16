<?php 

            /**
             * The Ouput of tad Advanced Sidebar Categories Widget
             * @author Mat Lipe
             * @since 6/3/12
             * 
             * 
             * @uses to edit create a file named category_list.php and put in a folder in the your child theme called 'advanced-sidebar-menu
             * @uses copy the contents of the file into that file and edit at will
             * @param Do not edit this file or it will break on update
             */



		#-- if the checkbox to include parent is checked
		if( $instance['include_parent'] == 'checked' && !in_array($top_cat, $exclude)  ){

				echo '<ul class="parent-sidebar-menu">';
						wp_list_categories( 'title_li=&include=' . $top_cat);

				$parent = 'yes';
		}

		if( !empty($all) ){

			echo '<ul class="child-sidebar-menu">';

			#-- If they want all the child categories displayed always
			if( $instance['display_all'] == 'checked' ){
				wp_list_categories('title_li=&child_of=' . $top_cat .'&depth=' .$instance['levels'] );
				echo '</ul><!-- End #child-sidebar-menu -->';
			} else {

				foreach( $all as $child_cat ){

					if( !in_array($child_cat->cat_ID, $exclude) && $child_cat->parent == $top_cat){

						//List the child category and the children if it is the current one
						wp_list_categories('title_li=&include=' . $child_cat->cat_ID . '&depth=1' );

						if( $child_cat->cat_ID == get_query_var('cat' ) || in_array( $child_cat->cat_ID, $cat_ancestors ) ){

							$all_children = array();
							$all_children = get_categories( array( 'child_of' => $child_cat->cat_ID ) );

							if( !empty( $all_children ) ){
								#-- Create a new menu with all the children under it
								echo '<ul class="grandchild-sidebar-menu">';

								wp_list_categories("title_li=&exclude=".$instance['exclude']."&depth=3&child_of=" .$child_cat->cat_ID );

								echo '</ul>';
							}
				  		 }
			   		}
		   		 } //End foreach
		    
				echo '</ul><!-- End #child-sidebar-menu -->';

			} //End if display all is not checked
			
		
		} //End if the are child categories
		
		
		#-- if a parent category was displayed
		if( isset($parent) ){
				echo '</ul><!-- End #parent-sidebar-menu -->';
				
				unset( $parent); //for next time
		}
		
		



