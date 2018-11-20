<?php
/**
* Pukka Themes Facebook widget
*/

class Pukka_Ads_50_Widget extends WP_Widget {

	/*--------------------------------------------------*/
	/* Constructor
	/*--------------------------------------------------*/

	/**
	 * Specifies the classname and description, instantiates the widget,
	 * loads localization files, and includes necessary stylesheets and JavaScript.
	 */
	public function __construct() {

		parent::__construct(
			'pukka-ads-50-widget',
			__( 'Pukka - Ads widget 8 x 50', 'pukka' ),
			array(
				'classname'  => 'pukka-ads-50-widget',
				'description' => __( 'Ads 8 x 50 widget', 'pukka' )
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


		// Check if there is a cached output
		$cache = wp_cache_get( 'pukka-ads-50-widget', 'widget' );

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

		// widget html
		for( $i=1; $i<9; $i++ ){

			if( !empty($instance['image_'.$i]) ){

				$widget_string .= '<span>' ."\n";
				if( !empty($instance['href_'.$i]) ){
					$widget_string .= '<a href="'. $instance['href_'.$i] .'">' ."\n";
				}


				$widget_string .= '<img src="'. $instance['image_'.$i] .'" />' ."\n";

				if( !empty($instance['href_'.$i]) ){
					$widget_string .= '</a>' ."\n";
				}

				$widget_string .= '</span>' ."\n";

			} // if( !empty($instance['image_'.$i]) )
		}

		$widget_string .= $after_widget;


		$cache[ $args['widget_id'] ] = $widget_string;

		wp_cache_set( 'pukka-ads-50-widget', $cache, 'widget' );

		print $widget_string;

	} // end widget


	public function flush_widget_cache()
	{
    	wp_cache_delete( 'pukka-ads-50-widget', 'widget' );
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

		for( $i=1; $i<9; $i++ ){
			$instance['image_'. $i] = $new_instance['image_'.$i];
			$instance['href_'. $i] = $new_instance['href_'.$i];
		}

		return $instance;

	} // end widget

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param array instance The array of keys and values for the widget.
	 */
	public function form( $instance ) {

		$defaults = array('title' => '');

		for( $i=1; $i<9; $i++ ){
			$defaults['image_'. $i] = '';
			$defaults['href_'. $i] = '';
		}

		$instance = wp_parse_args(
			(array) $instance,
			$defaults
		);

		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:','pukka' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<?php for($i=1; $i<9; $i++ ) : ?>
		<p>
			<label for="<?php echo $this->get_field_id( 'image_'. $i ); ?>"><?php echo sprintf(__('Image %d URL:', 'pukka'), $i); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'image_'. $i ); ?>" name="<?php echo $this->get_field_name( 'image_'. $i ); ?>" value="<?php echo $instance['image_'. $i]; ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'href_'. $i ); ?>"><?php echo sprintf(__('Target %d URL:', 'pukka'), $i); ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'href_'. $i ); ?>" name="<?php echo $this->get_field_name( 'href_'. $i ); ?>" value="<?php echo $instance['href_'. $i]; ?>"/>
		</p>
		<?php endfor; ?>


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
	register_widget( 'Pukka_Ads_50_Widget' );
} );
