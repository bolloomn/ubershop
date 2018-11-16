<?php
/**
* Pukka Themes Facebook widget
*/

class Pukka_Facebook_Widget extends WP_Widget {

	/*--------------------------------------------------*/
	/* Constructor
	/*--------------------------------------------------*/

	/**
	 * Specifies the classname and description, instantiates the widget,
	 * loads localization files, and includes necessary stylesheets and JavaScript.
	 */
	public function __construct() {

		parent::__construct(
			'pukka-facebook-widget',
			__( 'Pukka - Facebook widget', 'pukka' ),
			array(
				'classname'  => 'pukka-facebook-widget',
				'description' => __( 'Facebook likebox widget.', 'pukka' )
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
		$cache = wp_cache_get( 'pukka-facebook-widget', 'widget' );

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

		$page_url = $instance['page_url'];
		$width = $instance['width'];
		$color_scheme = $instance['color_scheme'];
		$show_faces = isset($instance['show_faces']) ? 'true' : 'false';
		$show_stream = isset($instance['show_stream']) ? 'true' : 'false';
		$show_header = isset($instance['show_header']) ? 'true' : 'false';
		$show_border = isset($instance['show_border']) ? 'true' : 'false';
		$height = '65';

		if($show_faces == 'true') {
			$height = '260';
		}

		if($show_stream == 'true') {
			$height = '600';
		}

		if($show_header == 'true') {
			$height = '600';
		}

		if($page_url){
			$widget_string .= '<iframe src="http://www.facebook.com/plugins/likebox.php?href='. urlencode($page_url) .'&amp;width='. $width .'&amp;colorscheme='. $color_scheme .'&amp;show_faces='. $show_faces .'&amp;show_border='. $show_border .'&amp;stream='. $show_stream .'&amp;header='. $show_header .'&amp;height='. $height .'" scrolling="no" frameborder="0" style="overflow:hidden; width: '. $width .'px; height: '. $height .'px;" allowTransparency="true"></iframe>';
        }

		$widget_string .= $after_widget;


		$cache[ $args['widget_id'] ] = $widget_string;

		wp_cache_set( 'pukka-facebook-widget', $cache, 'widget' );

		print $widget_string;

	} // end widget


	public function flush_widget_cache()
	{
    	wp_cache_delete( 'pukka-facebook-widget', 'widget' );
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

		$instance['page_url'] = $new_instance['page_url'];
		$instance['width'] = $new_instance['width'];
		$instance['color_scheme'] = $new_instance['color_scheme'];
		$instance['show_faces'] = $new_instance['show_faces'];
		$instance['show_stream'] = $new_instance['show_stream'];
		$instance['show_header'] = $new_instance['show_header'];
		$instance['show_border'] = $new_instance['show_border'];

		return $instance;

	} // end widget

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param array instance The array of keys and values for the widget.
	 */
	public function form( $instance ) {

		$defaults = array('title' => 'Find us on Facebook', 'page_url' => '', 'width' => '267', 'color_scheme' => 'light', 'show_faces' => 'on', 'show_stream' => false, 'show_header' => false, 'show_border' => false);

		$instance = wp_parse_args(
			(array) $instance,
			$defaults
		);

		?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'pukka'); ?>:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('page_url'); ?>"><?php _e('Facebook Page URL', 'pukka'); ?>:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('page_url'); ?>" name="<?php echo $this->get_field_name('page_url'); ?>" type="text" value="<?php echo $instance['page_url']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width', 'pukka'); ?>:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo $instance['width']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('color_scheme'); ?>"><?php _e('Color Scheme', 'pukka'); ?>:</label>
			<select id="<?php echo $this->get_field_id('color_scheme'); ?>" name="<?php echo $this->get_field_name('color_scheme'); ?>" class="widefat" style="width:100%;">
				<option <?php if ('light' == $instance['color_scheme']) echo 'selected="selected"'; ?>>light</option>
				<option <?php if ('dark' == $instance['color_scheme']) echo 'selected="selected"'; ?>>dark</option>
			</select>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_faces'], 'on'); ?> id="<?php echo $this->get_field_id('show_faces'); ?>" name="<?php echo $this->get_field_name('show_faces'); ?>" />
			<label for="<?php echo $this->get_field_id('show_faces'); ?>"><?php _e('Show faces', 'pukka'); ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_stream'], 'on'); ?> id="<?php echo $this->get_field_id('show_stream'); ?>" name="<?php echo $this->get_field_name('show_stream'); ?>" />
			<label for="<?php echo $this->get_field_id('show_stream'); ?>"><?php _e('Show stream', 'pukka'); ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_header'], 'on'); ?> id="<?php echo $this->get_field_id('show_header'); ?>" name="<?php echo $this->get_field_name('show_header'); ?>" />
			<label for="<?php echo $this->get_field_id('show_header'); ?>"><?php _e('Show facebook header', 'pukka'); ?></label>
		</p>

		<p>
			<input class="checkbox" type="checkbox" <?php checked($instance['show_border'], 'on'); ?> id="<?php echo $this->get_field_id('show_border'); ?>" name="<?php echo $this->get_field_name('show_border'); ?>" />
			<label for="<?php echo $this->get_field_id('show_border'); ?>"><?php _e('Show border', 'startit'); ?></label>
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
	register_widget( 'Pukka_Facebook_Widget' );
} );
