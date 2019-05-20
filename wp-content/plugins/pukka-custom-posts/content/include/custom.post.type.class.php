<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

if( !class_exists('PukkaCustomPostType') ) : 
class PukkaCustomPostType{
    protected $post_type;
    protected $post_args;
    protected $taxonomies;
    
    // The post type constructor
    public function __construct($post_type, $post_args, $taxonomies){
        
        $this->post_type = $post_type;
        $this->post_args = $post_args;
        $this->taxonomies = (array) $taxonomies;
        
        register_post_type($this->post_type, $this->post_args);

        // add taxonomy to post type
        $this->addTaxonomies();
        
        // Hooks
        add_filter('pre_get_posts', array(&$this, 'postAdminOrder'));
    }

    public function getPostSlug(){
        return $this->post_type;
    }

    public function addTaxonomies(){
        foreach( $this->taxonomies as $taxonomy )
            register_taxonomy_for_object_type($taxonomy, $this->post_type);
    }
    
    /* Sorts posts by date (admin area)
    *
    */
    public function postAdminOrder($wp_query){
      global $pagenow;

      if( is_admin() && $pagenow == 'edit.php' && isset($wp_query->query['post_type']) ){

        if( $wp_query->query['post_type'] == $this->post_type ){
          $wp_query->set('orderby', 'date');
          $wp_query->set('order', 'DESC');
        }
      }
    }
    
} // end of CustomPostType{} class
endif;