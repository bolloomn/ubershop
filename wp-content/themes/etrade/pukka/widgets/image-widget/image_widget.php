<?php
/**
* Pukka Themes Image widget
*/

class Pukka_Image_Widget extends WP_Widget {

	/*--------------------------------------------------*/
	/* Constructor
	/*--------------------------------------------------*/

	/**
	 * Specifies the classname and description, instantiates the widget,
	 * loads localization files, and includes necessary stylesheets and JavaScript.
	 */
	public function __construct() {

		parent::__construct(
			'pukka-image-widget',
			__( 'Pukka - Image Widget', 'pukka' ),
			array(
				'classname'  => 'pukka-image-widget',
				'description' => __( 'Simple image widget.', 'pukka' )
			)
		);

		// Register admin styles and scripts
		//add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

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
		$cache = wp_cache_get( 'pukka-image-widget', 'widget' );

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

		$css_class = '';

		if( !empty($instance['image_id']) ){

			$img_info = wp_get_attachment_image_src($instance['image_id'], 'full');

			if (!empty($instance['image_align']) && $instance['image_align'] != 'none') {
				$css_class .= ' align'. $instance['image_align'];
			}

			$image = '<img src="'. $img_info[0].'" width="'. $img_info[1] .'" height="'. $img_info[2] .'" class="'. trim($css_class) .'" style="max-width:100%;height:auto;" />';


			if( !empty($instance['link']) ){

				$image = '<a href="'. $instance['link'] .'"'. ( !empty($instance['link_target'])  ? ' target="'. $instance['link_target'] .'"' : '') .'>'. $image .'</a>' ."\n";

			}

			$widget_string .= $image;

		}

		$widget_string .= $after_widget;


		$cache[ $args['widget_id'] ] = $widget_string;

		wp_cache_set( 'pukka-image-widget', $cache, 'widget' );

		print $widget_string;

	} // end widget


	public function flush_widget_cache()
	{
    	wp_cache_delete( 'pukka-image-widget', 'widget' );
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
		$instance['image_id'] = $new_instance['image_id'];
		$instance['image_align'] = $new_instance['image_align'];

		$instance['link'] = strip_tags( $new_instance['link'] );
		$instance['link_target'] = $new_instance['link_target'];

		return $instance;

	} // end widget

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param array instance The array of keys and values for the widget.
	 */
	public function form( $instance ) {

		$instance = wp_parse_args(
			(array) $instance
		);

		//set widget values
		$title = isset( $instance['title'] ) ? $instance['title'] : '';
		$image_id = isset( $instance['image_id'] ) ? $instance['image_id'] : '';
		$image_align = isset( $instance['image_align'] ) ? $instance['image_align'] : '';

		$link = isset( $instance['link'] ) ? $instance['link'] : '';
		$link_target = isset( $instance['link_target'] ) ? $instance['link_target'] : '';

		if( $image_id != '' ){
			$img_info = wp_get_attachment_image_src($image_id, 'full');
			$thumb_css_class = '';
		}
		else{
			$img_info = '';
			$thumb_css_class = 'pukka-file-placeholder';
		}
		?>

		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:','pukka' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'image_align' ); ?>"><?php _e( 'Image alignment:','pukka' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'image_align' ); ?>" name="<?php echo $this->get_field_name( 'image_align' ); ?>">
	            <option value="" ><?php _e('None', 'pukka'); ?></option>
	            <option value="left" <?php selected($image_align, "left") ?>><?php _e('Left', 'pukka'); ?></option>
	            <option value="right" <?php selected($image_align, "right") ?>><?php _e('Right', 'pukka'); ?></option>
	            <option value="center" <?php selected($image_align, "center") ?>><?php _e('Center', 'pukka'); ?></option>
			</select>
        </p>

        <p>
			<label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php _e( 'Link:','pukka' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" type="text" value="<?php echo esc_attr($link); ?>" />
        </p>

        <p>
			<label for="<?php echo $this->get_field_id( 'link_target' ); ?>"><?php _e( 'Link target:','pukka' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'link_target' ); ?>" name="<?php echo $this->get_field_name( 'link_target' ); ?>">
	            <option value="_self" <?php selected($link_target, "_blank") ?>><?php _e('Same tab', 'pukka'); ?></option>
	            <option value="_blank" <?php selected($link_target, "_blank") ?>><?php _e('New tab', 'pukka'); ?></option>
			</select>
        </p>


		<?php // Image upload ?>
		<p>
			<span id="<?php echo $this->id;?>-thumb" class="pukka-img-wrap <?php echo $thumb_css_class; ?>">
			<?php if( $img_info != '' ) : ?>
					<img src="<?php echo esc_attr($img_info[0]); ?>" style="max-width:200px;" />
			<?php endif; ?>
		</p>
		<p>
			<span class="pukka-upload-buttons" data-field_id="<?php echo $this->get_field_id('image_id'); ?>" data-widget_id="<?php echo $this->id; ?>">
				<button id="upload-<?php echo $this->id; ?>" class="button button-primary pukka-upload-image" data-uploader_title="<?php _e('Select image', 'pukka'); ?>" data-uploader_button_text="<?php _e('Select', 'pukka'); ?>"><?php _e('Upload', 'pukka'); ?></button>
				<a href="#" id="remove-upload-<?php echo $this->id; ?>" class="pukka-remove-image" style="display:<?php echo ($image_id != '' ? 'block' : 'none') ?>"><?php _e('Remove', 'pukka'); ?></a>
			</span>
			<input type="hidden" id="<?php echo $this->get_field_id('image_id'); ?>" name="<?php echo $this->get_field_name('image_id'); ?>" value="<?php echo esc_attr($image_id); ?>" />
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

		wp_enqueue_media();
		wp_enqueue_script('pukka-upload-widget', get_template_directory_uri() .'/pukka/widgets/image-widget/js/jquery.upload.widget.js', array('jquery'));
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
	register_widget( 'Pukka_Image_Widget' );
} );
