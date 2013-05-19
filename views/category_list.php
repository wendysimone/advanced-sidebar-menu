<?php 

            /**
             * The Ouput of tad Advanced Sidebar Categories Widget
             * @author Mat Lipe
             * @since 4.1.0
             * 
             * 
             * @uses to edit create a file named category_list.php and put in a folder in the your child theme called 'advanced-sidebar-menu
             * @uses copy the contents of the file into that file and edit at will
             * @param Do not edit this file in this location or it will break on update
             */




$content = '';

//Include the parent page if chosen
if( $asm->include_parent() ){
	$content .= '<ul class="parent-sidebar-menu">';
		$content .= $asm->openListItem( wp_list_categories( 'echo=0&title_li=&include=' . trim($top_cat)) );
      
}


    //If there are children to display
	if( !empty($all_categories) ){
			$content .= '<ul class="child-sidebar-menu">';

			#-- If they want all the child categories displayed always
			if( $asm->display_all() ){
				$content .=  wp_list_categories('echo=0&title_li=&child_of=' . $top_cat .'&depth=' .$instance['levels'] );

			} else {
            
			#-- to Display the categories based a parent child relationship
				foreach( $all_categories as $child_cat ){
					
					//IF this is a child category of the top one
					if( $asm->first_level_category( $child_cat ) ){
						
						//List the child category and the children if it is the current one
						$content .= $asm->openListItem(wp_list_categories('echo=0&title_li=&include=' . $child_cat->cat_ID . '&depth=1' ) );

						
						//If there are children of this cat and it is a parent or child or the current cat
						if( $asm->second_level_cat( $child_cat ) ){
								#-- Create a new menu with all the children under it
								$content .= '<ul class="grandchild-sidebar-menu">';
								$content .=	wp_list_categories("echo=0&title_li=&exclude=".$instance['exclude']."&depth=3&child_of=" .$child_cat->cat_ID );
								$content .= '</ul>';
							
				  	    }
                        
                        $content .= '</li>';
			   		}
		   		 } //End foreach
		    
				

			} //End if display all is not checked
			
			$content .= '</ul><!-- End #child-sidebar-menu -->';
			
		} //End if the are child categories
		
		
		#-- if a parent category was displayed
		if( $asm->include_parent() ){
				$content .= '</li>
				    </ul><!-- End #parent-sidebar-menu -->';
		}
        