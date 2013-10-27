<?php

/*******************************
 * make sure ajax is working otherwise the like button won't work
*******************************/

function add_ajax_url() {
    echo '<script type="text/javascript">var ajaxurl = "' . admin_url('admin-ajax.php') . '";</script>';
}
// Add hook for admin <head></head>
add_action('wp_head', 'add_ajax_url');



/*******************************
 * likeCount:
 * Get current like count, this is used to show the amount of likes to the user and it's output will be used by the like_callback() function
*******************************/

function likeCount($id){

   $likes = get_post_meta( $id, '_likers', true );

   if(!empty($likes)){
      return count(explode(', ', $likes));
   }else{
      return '0';
   }

}



/*******************************
 * like_callback:
 * add or remove likes from the Wordpress metabox field
*******************************/

add_action('wp_ajax_like_callback', 'like_callback');
add_action('wp_ajax_nopriv_like_callback', 'like_callback');

function like_callback() {

   $id = json_decode($_GET['data']); // Get the ajax call
   $feedback = array("likes" => "");


   // Get metabox values
   $currentvalue = get_post_meta( $id, '_likers', true );
   $likes = intval(get_post_meta( $id, '_likes_count', true ));


   // Convert likers string to an array
   $likesarray = explode(', ', $currentvalue);


   // Check if the likers metabox already has a value to determine if the new entry has to be prefixed with a comma or not
   if(!empty($currentvalue)){
      $newvalue = $currentvalue .', '. $_SERVER['REMOTE_ADDR'];
   }else{
      $newvalue = $_SERVER['REMOTE_ADDR'];
   }


   // Check if the IP address is already present, if not, add it
   if(strpos($currentvalue, $_SERVER['REMOTE_ADDR']) === false){
      $nlikes = $likes + 1;
      if(update_post_meta($id, '_likers', $newvalue, $currentvalue) && update_post_meta($id, '_likes_count', $nlikes, $likes)){
         $feedback = array("likes" => likeCount($id), "status" => true);
      }
   }else{

      $key = array_search($_SERVER['REMOTE_ADDR'], $likesarray);
      unset($likesarray[$key]);
      $nlikes = $likes - 1;

      if(update_post_meta($id, '_likers', implode(", ", $likesarray), $currentvalue) && update_post_meta($id, '_likes_count', $nlikes, $likes)){
         $feedback = array("likes" => likeCount($id), "status" => false);
      }

   }

   echo json_encode($feedback);

   die(); // A kitten gif will be removed from the interwebs if you delete this line

}
?>