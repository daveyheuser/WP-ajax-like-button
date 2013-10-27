<?php

/*******************************
 * Add / show metabox
*******************************/

function add_like_metabox() {

   $show_on = array('post');
   if(in_array(get_post_type(), $show_on)){

      add_meta_box(
         'like_metabox',
         'Likes',
         'render_like_metabox',
         get_post_type(),
         'side',
         'low'
      );

   }

}

add_action('add_meta_boxes', 'add_like_metabox');



/*******************************
 * Render the metabox
*******************************/

function render_like_metabox(){
   global $post;
   $likers = get_post_meta($post->ID, '_likers', true);
   $likes_count = get_post_meta($post->ID, '_likes_count', true);

   wp_nonce_field(__FILE__, 'wp_nonce');
   ?>
   <p>
      <label for="likers">IP address array of the likers:</label>
      <textarea name="_likers" id="likers" class="widefat"><?php echo $likers; ?></textarea>
   </p>
   <p>
      <label for="likes_count">Likes count:</label>
      <input type="text" name="_likes_count" id="likes_count" class="widefat" value="<?php echo $likes_count; ?>" />
   </p>
   <?php
}



/*******************************
 * Save the metabox
*******************************/

function save_like_metabox(){
   global $post;

   if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;

   if($_POST && wp_verify_nonce($_POST['wp_nonce'], __FILE__)){
      if(isset($_POST['_likers']) || isset($_POST['_likes_count'])){
         update_post_meta($post->ID, '_likers', $_POST['_likers']);
         update_post_meta($post->ID, '_likes_count', $_POST['_likes_count']);
      }
   }
}

add_action('save_post', 'save_like_metabox');
?>