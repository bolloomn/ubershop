<?php
/**
* Pukka Themes Latest Posts widget
*/

class Pukka_Latest_Posts_Widget extends WP_Widget {

	/*--------------------------------------------------*/
	/* Constructor
	/*--------------------------------------------------*/

	/**
	 * Specifies the classname and description, instantiates the widget,
	 * loads localization files, and includes necessary stylesheets and JavaScript.
	 */
	public function __construct() {

		parent::__construct(
			'pukka-latest-posts-widget',
			__( 'Pukka - Latest posts widget', 'pukka' ),
			array(
				'classname'  => 'pukka-latest-posts-widget',
				'description' => __( 'Latest posts widget', 'pukka' )
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
		$cache = wp_cache_get( 'pukka-latest-posts-widget', 'widget' );

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

		$display_date = (!empty($instance['display_date']) && $instance['display_date'] == 'on') ? true : false;
		$display_comments = (!empty($instance['display_comments']) && $instance['display_comments'] == 'on') ? true : false;
		$posts_per_page = !empty($instance['number']) ? (int)$instance['number'] : 5;

		$query = new WP_Query(
							array(
								'posts_per_page' => $instance['number'],
								'post_type' => 'post',
								'post_status' => 'publish',
							)
						);

		if( $query->have_posts() ){
			$widget_string .= '<ul';
			if(!$display_date){
				$widget_string .= ' class="no-date"';
			}
			$widget_string .= '>' ."\n";
			while( $query->have_posts() ) : $query->the_post();

				if( $display_comments ){
					$num_comments = get_comments_number(); // get_comments_number returns only a numeric value

					if ( comments_open() ) {
						if ( $num_comments == 0 ) {
							$comments = __('No Comments', 'pukka');
						} elseif ( $num_comments > 1 ) {
							$comments = $num_comments . __(' Comments', 'pukka');
						} else {
							$comments = __('1 Comment', 'pukka');
						}
						$write_comments = '<a href="' . get_comments_link() .'">'. $comments.'</a>';
					} else {
						$write_comments =  __('Comments are off for this post.', 'pukka');
					}
				}

				$widget_string .= '<li>' ."\n";

				if( $display_date ){
					$widget_string .= '<span class="latest-date buttons">';
					$widget_string .= '<span>'. get_the_date('d') .'</span> '. get_the_date('M');
					$widget_string .= '</span>' ."\n";
				}

				$widget_string .= '<div class="latest-content">' ."\n";
				$widget_string .= '<h4><a href="'. get_permalink() .'" title="'. esc_attr(get_the_title()) .'">'. get_the_title() .'</a></h4>' ."\n";

				if( $display_comments ){
					$widget_string .= $write_comments . "\n";
				}

				$widget_string .= '</div> <!-- .latest-content -->' ."\n";

				$widget_string .= '</li>' ."\n";

			endwhile;
			$widget_string .= '</ul>' ."\n";
		} // if( $query->have_posts() )

		wp_reset_postdata();


		$widget_string .= $after_widget;

		$cache[ $args['widget_id'] ] = $widget_string;

		wp_cache_set( 'pukka-latest-posts-widget', $cache, 'widget' );

		print $widget_string;

	} // end widget


	public function flush_widget_cache()
	{
    	wp_cache_delete( 'pukka-latest-posts-widget', 'widget' );
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
		$instance['display_date'] = isset($new_instance['display_date']) ? 'on' : '';
		$instance['display_comments'] = isset($new_instance['display_comments']) ? 'on' : '';

		return $instance;

	} // end widget

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param array instance The array of keys and values for the widget.
	 */
	public function form( $instance ) {

		$defaults = array('title' => '', 'number' => 5, 'display_date' => '', 'display_comments'=> '');

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
			<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:','pukka' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo esc_attr( $instance['number'] ); ?>" />
		</p>

		<p>
			<input id="<?php echo $this->get_field_id( 'display_date' ); ?>" name="<?php echo $this->get_field_name( 'display_date' ); ?>" class="checkbox" type="checkbox" value="on" <?php checked($instance['display_date'], 'on') ?> />
			<label for="<?php echo $this->get_field_id( 'display_date' ); ?>"><?php _e( 'Display post date?','pukka' ); ?></label>
		</p>

		<p>
			<input id="<?php echo $this->get_field_id( 'display_comments' ); ?>" name="<?php echo $this->get_field_name( 'display_comments' ); ?>" class="checkbox" type="checkbox" value="on" <?php checked($instance['display_comments'], 'on') ?> />
			<label for="<?php echo $this->get_field_id( 'display_comments' ); ?>"><?php _e( 'Display post comments?','pukka' ); ?></label>
		</p>

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
	register_widget( 'Pukka_Latest_Posts_Widget' );
} );
