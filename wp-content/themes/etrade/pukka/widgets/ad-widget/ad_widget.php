<?php
/**
* Pukka Themes Facebook widget
*/

class Pukka_Ad_Widget extends WP_Widget {

	/*--------------------------------------------------*/
	/* Constructor
	/*--------------------------------------------------*/

	/**
	 * Specifies the classname and description, instantiates the widget,
	 * loads localization files, and includes necessary stylesheets and JavaScript.
	 */
	public function __construct() {

		parent::__construct(
			'pukka-ad-widget',
			__( 'Pukka - Ad widget', 'pukka' ),
			array(
				'classname'  => 'pukka-ad-widget',
				'description' => __( 'Ad widget', 'pukka' )
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
		$cache = wp_cache_get( 'pukka-ad-widget', 'widget' );

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

		if( !empty($instance['ad_code']) ){
			$ad_size = explode('x', $instance['ad_size']);
			$ad_align = !empty($instance['ad_align']) ? $instance['ad_align'] : '';

			$ad_css = 'width: '. $ad_size[0] .'px; height: '. $ad_size[1] .'px;';

			$ad_class = '';
			if( $ad_align == 'left' ){
				$ad_class = 'alignleft';
			}
			elseif( $ad_align == 'right' ){
				$ad_class = 'alignright';
			}
			elseif( $ad_align == 'center' ){
				$ad_class = 'aligncenter';
			}

			$widget_string .= '<div class="pukka-ad '. $ad_class .'" style="'. $ad_css .'">' . $instance['ad_code'] .'</div>' ."\n";


		}

		$widget_string .= $after_widget;


		$cache[ $args['widget_id'] ] = $widget_string;

		wp_cache_set( 'pukka-ad-widget', $cache, 'widget' );

		print $widget_string;

	} // end widget


	public function flush_widget_cache()
	{
    	wp_cache_delete( 'pukka-ad-widget', 'widget' );
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
		$instance['ad_code'] = $new_instance['ad_code'];
		$instance['ad_size'] = $new_instance['ad_size'];
		$instance['ad_align'] = $new_instance['ad_align'];


		return $instance;

	} // end widget

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param array instance The array of keys and values for the widget.
	 */
	public function form( $instance ) {

		$defaults = array('title' => '', 'ad_code' => '', 'ad_size' => '125x125', 'ad_align' => 'none');

		$instance = wp_parse_args(
			(array) $instance,
			$defaults
		);

		$ad_sizes = array(
							array(336, 280),
							array(300, 250),
							array(250, 250),
							array(160, 600),
							array(120, 600),
							array(240, 400),
							array(234, 60),
							array(180, 150),
							array(125, 125),
							array(120, 90),
							array(190, 60),
							array(88, 31),
						);
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:','pukka' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'ad_code' ); ?>"><?php _e( 'Ad code','pukka'); ?></label>
        	<textarea class="widefat" id="<?php echo $this->get_field_id( 'ad_code' ); ?>" name="<?php echo $this->get_field_name( 'ad_code' ); ?>" cols="5" rows="5"><?php echo esc_textarea( $instance['ad_code'] ); ?></textarea>
		</p>

		<p>
        	<label for="<?php echo $this->get_field_id( 'ad_size' ); ?>"><?php _e( 'Banner size:','pukka' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'ad_size' ); ?>" name="<?php echo $this->get_field_name( 'ad_size' ); ?>">
			<?php foreach($ad_sizes as $s => $atts) : ?>
				<option value="<?php echo implode( 'x', $atts ); ?>" <?php selected($s, $instance['ad_size']); ?>><?php echo implode( 'x', $atts ); ?></option>
			<?php endforeach; ?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'ad_align' ); ?>"><?php _e( 'Ad alignment:','pukka' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'ad_align' ); ?>" name="<?php echo $this->get_field_name( 'ad_align' ); ?>">
	            <option value="" ><?php _e('None', 'pukka'); ?></option>
	            <option value="left" <?php selected($instance['ad_align'], "left") ?>><?php _e('Left', 'pukka'); ?></option>
	            <option value="right" <?php selected($instance['ad_align'], "right") ?>><?php _e('Right', 'pukka'); ?></option>
	            <option value="center" <?php selected($instance['ad_align'], "center") ?>><?php _e('Center', 'pukka'); ?></option>
			</select>
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
	register_widget( 'Pukka_Ad_Widget' );
} );
