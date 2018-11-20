<?php
/**
* Pukka Themes Facebook widget
*/

class Pukka_CTA_Widget extends WP_Widget {

	/*--------------------------------------------------*/
	/* Constructor
	/*--------------------------------------------------*/

	/**
	 * Specifies the classname and description, instantiates the widget,
	 * loads localization files, and includes necessary stylesheets and JavaScript.
	 */
	public function __construct() {

		parent::__construct(
			'pukka-cta-widget',
			__( 'Pukka - CTA widget', 'pukka' ),
			array(
				'classname'  => 'pukka-cta-widget',
				'description' => __( 'CTA widget', 'pukka' )
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
		$cache = wp_cache_get( 'pukka-cta-widget', 'widget' );

		if ( !is_array( $cache ) )
			$cache = array();

		if ( ! isset ( $args['widget_id'] ) )
			$args['widget_id'] = $this->id;

		if ( isset ( $cache[ $args['widget_id'] ] ) )
			return print $cache[ $args['widget_id'] ];

		// widget logic


		extract( $args, EXTR_SKIP );

		$widget_string = $before_widget;

		$widget_string .= '<div class="widget-cta-wrap">' ."\n";

		// background
		$background_style = '';
		if( $instance['bg_image_id'] ){
			$bg_image = wp_get_attachment_image_src($instance['bg_image_id'], 'full');
			$background_style = 'background-image: url('. $bg_image[0] .');';
			$background_style .= 'background-color: transparent;';
		}
		elseif( $instance['bg_color'] ) {
			$background_style .= 'background-color: '. $instance['bg_color'] .';';
		}

		if( $background_style != '' ){
			$widget_string .= '<div class="widget-cta-img" style="'. $background_style .'"></div>' ."\n";
		}


		$widget_string .= '<div class="widget-cta-content">' ."\n";

		if( !empty($instance['title']) ){
			$title_style = 'color:'. $instance['title_color'] .';';
			$title_style .= 'font-size:'. $instance['title_size'] .'px;';
			$title_style .= 'margin-top:'. $instance['title_margin_top'] .'px;';
			$widget_string .= $before_title . '<span style="'. $title_style .'">'. apply_filters('widget_title', $instance['title']) .'</span>'. $after_title;
		}

		if( !empty($instance['content']) ){
			$content_style = 'color:'. $instance['content_color'] .';';
			$content_style .= 'font-size:'. $instance['content_size'] .'px;';
			$content_style .= 'margin-top:'. $instance['content_margin_top'] .'px;';
			$widget_string .= '<div style="'. $content_style .'">'. $instance['content'] .'</div>' ."\n";
		}

		if( !empty($instance['button_text']) ){
			$button_style = 'background-color: '. $instance['button_color'] .';';
			$button_style .= 'color:'. $instance['button_text_color'] .';';
			$button_style .= 'font-size:'. $instance['button_text_size'] .'px;';
			$button_style .= 'margin-top:'. $instance['button_margin_top'] .'px;';
			$widget_string .= '<a href="'. $instance['link'] .'" class="button" '. ( !empty($instance['link_target'])  ? ' target="'. $instance['link_target'] .'"' : '') .' style="'. $button_style .'">'. $instance['button_text'] .'</a>' ."\n";
		}

		$widget_string .= '</div> <!-- .cta-content -->' ."\n";

		$widget_string .= '</div> <!-- .cta-wrap -->' ."\n";

		$widget_string .= $after_widget;


		$cache[ $args['widget_id'] ] = $widget_string;

		wp_cache_set( 'pukka-cta-widget', $cache, 'widget' );

		print $widget_string;

	} // end widget


	public function flush_widget_cache()
	{
    	wp_cache_delete( 'pukka-cta-widget', 'widget' );
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
		$instance['title_color'] = $new_instance['title_color'];
		$instance['title_size'] = $new_instance['title_size'];
		$instance['title_margin_top'] = $new_instance['title_margin_top'];

		$instance['content'] = $new_instance['content'];
		$instance['content_color'] = $new_instance['content_color'];
		$instance['content_size'] = $new_instance['content_size'];
		$instance['content_margin_top'] = $new_instance['content_margin_top'];

		$instance['button_text'] = strip_tags( $new_instance['button_text'] );
		$instance['button_text_color'] = $new_instance['button_text_color'];
		$instance['button_text_size'] = $new_instance['button_text_size'];
		$instance['button_color'] = $new_instance['button_color'];
		$instance['button_margin_top'] = $new_instance['button_margin_top'];

		$instance['link'] = $new_instance['link'];
		$instance['link_target'] = $new_instance['link_target'];

		$instance['bg_image_id'] = $new_instance['bg_image_id'];
		$instance['bg_color'] = $new_instance['bg_color'];

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
						'title_color' => '#ffffff',
						'title_size' => '110',
						'title_margin_top' => '70',
						'content' => '',
						'content_size' => '16',
						'content_color' => '#ffffff',
						'content_margin_top' => '30',
						'button_text' => '',
						'button_text_color' => '#ffffff',
						'button_text_size' => '16',
						'button_color' => '#0871a2',
						'button_margin_top' => '40',
						'link' => '',
						'link_target' => '_self',
						'bg_image_id' =>'',
						'bg_color' => '',
					);

		$instance = wp_parse_args(
			(array) $instance,
			$defaults
		);

		if( $instance['bg_image_id'] != '' ){
			$img_info = wp_get_attachment_image_src($instance['bg_image_id'], 'full');
			$thumb_css_class = '';
		}
		else{
			$img_info = '';
			$thumb_css_class = 'pukka-file-placeholder';
		}
		?>
		<?php /* scrip that inits color pickers */ ?>
		<script type="text/javascript">
        		var pukka_cp_elems = jQuery("#widgets-right .pukka-color-picker, .inactive-sidebar .pukka-color-picker");

		        jQuery(document).ready(function($) {
		            pukka_cp_elems.wpColorPicker();
		        }).ajaxComplete(function(e, xhr, settings) {
					// new widget added? re init color pickers
		        	var widget_id = "<?php echo $this->id_base; ?>";

		            if( settings.data.search("action=save-widget") != -1 && settings.data.search("id_base=" + widget_id) != -1 ) {
		                pukka_cp_elems.wpColorPicker();
		            }
		        });
			</script>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:','pukka' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'title_color' ); ?>"><?php _e( 'Title color:','pukka' ); ?></label>
			<input class="widefat pukka-color-picker" id="<?php echo $this->get_field_id( 'title_color' ); ?>" name="<?php echo $this->get_field_name( 'title_color' ); ?>" type="text" value="<?php echo esc_attr( $instance['title_color'] ); ?>" data-default-color="#ffffff" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'title_size' ); ?>"><?php _e( 'Title size:','pukka' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'title_size' ); ?>" name="<?php echo $this->get_field_name( 'title_size' ); ?>" type="text" value="<?php echo esc_attr($instance['title_size']); ?>" size="2" />px
        </p>

        <p>
			<label for="<?php echo $this->get_field_id( 'title_margin_top' ); ?>"><?php _e( 'Title top margin:','pukka' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'title_margin_top' ); ?>" name="<?php echo $this->get_field_name( 'title_margin_top' ); ?>" type="text" value="<?php echo esc_attr($instance['title_margin_top']); ?>" size="2" />px
        </p>

		<p>
			<label for="<?php echo $this->get_field_id( 'content' ); ?>"><?php _e( 'Content','pukka'); ?></label>
			<textarea class="widefat" id="<?php echo $this->get_field_id( 'content' ); ?>" name="<?php echo $this->get_field_name( 'content' ); ?>" cols="5" rows="5"><?php echo esc_textarea( $instance['content'] ); ?></textarea>
        </p>

        <p>
			<label for="<?php echo $this->get_field_id( 'content_color' ); ?>"><?php _e( 'Content color:','pukka' ); ?></label>
			<input class="widefat pukka-color-picker" id="<?php echo $this->get_field_id( 'content_color' ); ?>" name="<?php echo $this->get_field_name( 'content_color' ); ?>" type="text" value="<?php echo esc_attr( $instance['content_color'] ); ?>" data-default-color="#ffffff" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'content_size' ); ?>"><?php _e( 'Content size:','pukka' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'content_size' ); ?>" name="<?php echo $this->get_field_name( 'content_size' ); ?>" type="text" value="<?php echo esc_attr($instance['content_size']); ?>" size="2" />px
        </p>

        <p>
			<label for="<?php echo $this->get_field_id( 'button_text' ); ?>"><?php _e( 'Button text:','pukka' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'button_text' ); ?>" name="<?php echo $this->get_field_name( 'button_text' ); ?>" type="text" value="<?php echo esc_attr( $instance['button_text'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'content_margin_top' ); ?>"><?php _e( 'Content top margin:','pukka' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'contetn_margin_top' ); ?>" name="<?php echo $this->get_field_name( 'content_margin_top' ); ?>" type="text" value="<?php echo esc_attr($instance['content_margin_top']); ?>" size="2" />px
        </p>

		<p>
			<label for="<?php echo $this->get_field_id( 'link' ); ?>"><?php _e( 'Link:','pukka' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'link' ); ?>" name="<?php echo $this->get_field_name( 'link' ); ?>" type="text" value="<?php echo esc_attr( $instance['link'] ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'link_target' ); ?>"><?php _e( 'Link target:','pukka' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'link_target' ); ?>" name="<?php echo $this->get_field_name( 'link_target' ); ?>">
	            <option value="_self" <?php selected($instance['link_target'], "_blank") ?>><?php _e('Same tab', 'pukka'); ?></option>
	            <option value="_blank" <?php selected($instance['link_target'], "_blank") ?>><?php _e('New tab', 'pukka'); ?></option>
			</select>
        </p>

		<p>
			<label for="<?php echo $this->get_field_id( 'button_text_color' ); ?>"><?php _e( 'Button text color:','pukka' ); ?></label>
			<input class="widefat pukka-color-picker" id="<?php echo $this->get_field_id( 'button_text_color' ); ?>" name="<?php echo $this->get_field_name( 'button_text_color' ); ?>" type="text" value="<?php echo esc_attr( $instance['button_text_color'] ); ?>" data-default-color="#ffffff" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'button_color' ); ?>"><?php _e( 'Button color:', 'pukka' ); ?></label>
			<input class="widefat pukka-color-picker" id="<?php echo $this->get_field_id( 'button_color' ); ?>" name="<?php echo $this->get_field_name( 'button_color' ); ?>" type="text" value="<?php echo esc_attr($instance['button_color']); ?>" data-default-color="#0871a2" />
        </p>

        <p>
			<label for="<?php echo $this->get_field_id( 'button_text_size' ); ?>"><?php _e( 'Button text size:','pukka' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'button_text_size' ); ?>" name="<?php echo $this->get_field_name( 'button_text_size' ); ?>" type="text" value="<?php echo esc_attr($instance['button_text_size']); ?>" size="2" />px
        </p>

        <p>
			<label for="<?php echo $this->get_field_id( 'button_margin_top' ); ?>"><?php _e( 'Button top margin:','pukka' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'button_margin_top' ); ?>" name="<?php echo $this->get_field_name( 'button_margin_top' ); ?>" type="text" value="<?php echo esc_attr($instance['button_margin_top']); ?>" size="2" />px
        </p>

        <p>
			<label for="<?php echo $this->get_field_id( 'bg_color' ); ?>"><?php _e( 'Widget background color:', 'pukka' ); ?></label>
			<input class="widefat pukka-color-picker" id="<?php echo $this->get_field_id( 'bg_color' ); ?>" name="<?php echo $this->get_field_name( 'bg_color' ); ?>" type="text" value="<?php echo esc_attr($instance['bg_color']); ?>" data-default-color="" />
        </p>

        <?php // Image upload ?>
		<p>
			<span id="<?php echo $this->id;?>-thumb" class="pukka-img-wrap <?php echo $thumb_css_class; ?>">
			<?php if( $img_info != '' ) : ?>
					<img src="<?php echo esc_attr($img_info[0]); ?>" style="max-width:200px;" />
			<?php endif; ?>
		</p>
		<p>
			<span class="pukka-upload-buttons" data-field_id="<?php echo $this->get_field_id('bg_image_id'); ?>" data-widget_id="<?php echo $this->id; ?>">
				<button id="upload-<?php echo $this->id; ?>" class="button button-primary pukka-upload-image" data-uploader_title="<?php _e('Select image', 'pukka'); ?>" data-uploader_button_text="<?php _e('Select', 'pukka'); ?>"><?php _e('Upload', 'pukka'); ?></button>
				<a href="#" id="remove-upload-<?php echo $this->id; ?>" class="pukka-remove-image" style="display:<?php echo ($instance['bg_image_id'] != '' ? 'block' : 'none') ?>"><?php _e('Remove', 'pukka'); ?></a>
			</span>
			<input type="hidden" id="<?php echo $this->get_field_id('bg_image_id'); ?>" name="<?php echo $this->get_field_name('bg_image_id'); ?>" value="<?php echo esc_attr($instance['bg_image_id']); ?>" />
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

		wp_enqueue_script('wp-color-picker');
		wp_enqueue_style('wp-color-picker');

		wp_enqueue_media();
		wp_enqueue_script('pukka-upload-widget', get_template_directory_uri() .'/pukka/widgets/cta-widget/js/jquery.upload.widget.js', array('jquery'));

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
	register_widget( 'Pukka_CTA_Widget' );
} );
