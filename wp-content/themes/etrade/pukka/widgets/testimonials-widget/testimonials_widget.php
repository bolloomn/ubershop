<?php
/**
* Pukka Themes Testimonials widget
*/

class Pukka_Testimonials_Widget extends WP_Widget {

	/*--------------------------------------------------*/
	/* Constructor
	/*--------------------------------------------------*/

	/**
	 * Specifies the classname and description, instantiates the widget,
	 * loads localization files, and includes necessary stylesheets and JavaScript.
	 */
	public function __construct() {

		parent::__construct(
			'pukka-testimonials-widget',
			__( 'Pukka - Testimonials widget', 'pukka' ),
			array(
				'classname'  => 'pukka-testimonials-widget',
				'description' => __( 'Testimonials widget', 'pukka' )
			)
		);

		// Register admin styles and scripts
		//add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
		//add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

		// Register site styles and scripts
		//add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );
		//add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_scripts' ) );

		// Refreshing the widget's cached output with each new post
		add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );

	} // end constructor

	/*--------------------------------------------------*/
	/* Widget API Functions
	/*--------------------------------------------------*/

	/**
	 * Outputs the content of the widget.
	 *
	 * @param array args  The array of form elements
	 * @param array instance The current instance of the widget
	 */
	public function widget( $args, $instance ) {
		global $post;

		// Check if there is a cached output
		$cache = wp_cache_get( 'pukka-testimonials-widget', 'widget' );

		if ( !is_array( $cache ) )
			$cache = array();

		if ( ! isset ( $args['widget_id'] ) )
			$args['widget_id'] = $this->id;

		if ( isset ( $cache[ $args['widget_id'] ] ) )
			return print $cache[ $args['widget_id'] ];

		// widget logic

		extract( $args, EXTR_SKIP );

		$widget_string = $before_widget;

		if( !empty($instance['title']) ){
			$widget_string .= $before_title . apply_filters('widget_title', $instance['title']) . $after_title;
		}

		$posts_per_page = !empty($instance['number']) ? (int)$instance['number'] : 5;
		$cat_id = !empty($instance['testimonials_cat']) ? (int)$instance['testimonials_cat'] : '';

		$query_args = array(
					'posts_per_page' => $posts_per_page,
					'post_type' => 'testimonial',
					'post_status' => 'publish',
				);

		if( !empty($cat_id) ){
			$query_args['tax_query'] = array(
									array(
										'taxonomy' => 'testimonial_category',
										'field' => 'id',
										'terms' => $cat_id,
									)
								);
		}

		$query = new WP_Query($query_args);

		if( $query->have_posts() ){
			$widget_string .= '<div class="testimonials-slider">' ."\n";
			$widget_string .= '<ul class="slides">' ."\n";
			while( $query->have_posts() ) : $query->the_post();

				$widget_string .= '<li>' ."\n";

				if( $instance['hide_testimonial_title'] != 'on' ){
					$widget_string .= '<h4 class="testimonial-title">'. get_the_title() .'</h4>' ."\n";
				}

				if( $instance['show_image'] == 'on' && has_post_thumbnail() ){
					$widget_string .= '<span class="testimonial-thumb">'. get_the_post_thumbnail($post->ID, 'full').'</span>' ."\n";
				}

				$widget_string .= '<p>'. get_the_content() .'</p>'. "\n";

				$author_meta = '';
				if( $instance['show_author'] && get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX .'author' , true) != '' ){
					$author_meta .= '<span class="testimonial-author">'. get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX .'author', true) .'</span>';
				}

				if( $instance['show_website'] && get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX .'website' , true) != '' ){
					if( $author_meta != '' ){
						$author_meta .= ', ';
					}

					$author_meta .= '<a href="'. get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX .'website', true) .'" target="_blank" class="testimonial-website">'. preg_replace('#^https?://#', '', get_post_meta($post->ID, PUKKA_POSTMETA_PREFIX .'website', true)) .'</a>';
				}

				if( $author_meta != '' ){

					$widget_string .= '<span class="testimonial-meta">' ."\n";
					$widget_string .=  $author_meta;
					$widget_string .=  '</span>' ."\n";;
				}


				$widget_string .= '</li>' ."\n";

			endwhile;
			$widget_string .= '</ul>' ."\n";
			$widget_string .= '</div> <!-- testimonials-slider -->' ."\n";
		} // if( $query->have_posts() )

		wp_reset_postdata();

		$widget_string .= $after_widget;

		$cache[ $args['widget_id'] ] = $widget_string;

		wp_cache_set( 'pukka-testimonials-widget', $cache, 'widget' );

		print $widget_string;

	} // end widget


	public function flush_widget_cache()
	{
    	wp_cache_delete( 'pukka-testimonials-widget', 'widget' );
	}
	/**
	 * Processes the widget's options to be saved.
	 *
	 * @param array new_instance The new instance of values to be generated via the update.
	 * @param array old_instance The previous instance of values before the update.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = $old_instance;

		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = (int)strip_tags( $new_instance['number'] );
		$instance['show_image'] = $new_instance['show_image'];
		$instance['show_author'] = $new_instance['show_author'];
		$instance['show_website'] = $new_instance['show_website'];
		$instance['hide_testimonial_title'] = isset($new_instance['hide_testimonial_title']) ? 'on' : '';
		$instance['testimonials_cat'] = $new_instance['testimonials_cat'];

		return $instance;

	} // end widget

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param array instance The array of keys and values for the widget.
	 */
	public function form( $instance ) {

		$defaults = array(
						'title' => '',
						'number' => 5,
						'testimonials_cat' => '',
						'show_image' => '',
						'show_author' => '',
						'show_website' => '',
						'hide_testimonial_title' => '',
					);

		$instance = wp_parse_args(
			(array) $instance,
			$defaults
		);

	?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:','pukka' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of testimonials to show:','pukka' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo esc_attr( $instance['number'] ); ?>" />
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_image'], 'on'); ?> id="<?php echo $this->get_field_id('show_image'); ?>" name="<?php echo $this->get_field_name('show_image'); ?>" />
			<label for="<?php echo $this->get_field_id('show_image'); ?>"><?php _e('Show image', 'pukka'); ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_author'], 'on'); ?> id="<?php echo $this->get_field_id('show_author'); ?>" name="<?php echo $this->get_field_name('show_author'); ?>" />
			<label for="<?php echo $this->get_field_id('show_author'); ?>"><?php _e('Show author', 'pukka'); ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_website'], 'on'); ?> id="<?php echo $this->get_field_id('show_website'); ?>" name="<?php echo $this->get_field_name('show_website'); ?>" />
			<label for="<?php echo $this->get_field_id('show_website'); ?>"><?php _e('Show website', 'pukka'); ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['hide_testimonial_title'], 'on'); ?> id="<?php echo $this->get_field_id('hide_testimonial_title'); ?>" name="<?php echo $this->get_field_name('hide_testimonial_title'); ?>" />
			<label for="<?php echo $this->get_field_id('hide_testimonial_title'); ?>"><?php _e('Hide testimonial title', 'pukka'); ?></label>
		</p>

		<?php /* Testimonials categories */ ?>
		<?php
			$testimonial_cats = get_terms('testimonial_category', array('hide_empty' => 0));

			if( !is_wp_error($testimonial_cats) && !empty($testimonial_cats) ) :
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'testimonials_cat' ); ?>"><?php _e( 'Testimonials category:','pukka' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'testimonials_cat' ); ?>" name="<?php echo $this->get_field_name( 'testimonials_cat' ); ?>">
				<option value=""><?php _e('Select Category', 'pukka'); ?></option>
			<?php
				foreach( (array)$testimonial_cats as $cat ){
					echo '<option value="'. $cat->term_id .'" '. selected($instance['testimonials_cat'], $cat->term_id, false) .'>'. $cat->name .'</option>' ."\n";
				}
			?>
			</select>
		</p>
		<?php endif; // if( !is_wp_error($testimonial_cats) && !empty($testimonial_cats) ) :  ?>

		<?php
	} // end form

	/*--------------------------------------------------*/
	/* Public Functions
	/*--------------------------------------------------*/

	/**
	 * Registers and enqueues admin-specific styles.
	 */
	public function register_admin_styles() {

	} // end register_admin_styles

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */
	public function register_admin_scripts() {
		global $pagenow;

		if( $pagenow != 'widgets.php' ){
			return;
		}

	} // end register_admin_scripts

	/**
	 * Registers and enqueues widget-specific styles.
	 */
	public function register_widget_styles() {

	} // end register_widget_styles

	/**
	 * Registers and enqueues widget-specific scripts.
	 */
	public function register_widget_scripts() {

	} // end register_widget_scripts

} // end class

add_action( 'widgets_init', function() {
	register_widget( 'Pukka_Testimonials_Widget' );
} );
