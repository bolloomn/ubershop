<?php
/**
* Pukka Themes Font Awesome Widget
*/

class Pukka_FA_Widget extends WP_Widget {

	/*--------------------------------------------------*/
	/* Constructor
	/*--------------------------------------------------*/

	/**
	 * Specifies the classname and description, instantiates the widget,
	 * loads localization files, and includes necessary stylesheets and JavaScript.
	 */
	public function __construct() {

		parent::__construct(
			'pukka-fa-widget',
			__( 'Pukka - Font Awesome Widget', 'pukka' ),
			array(
				'classname'  => 'pukka-fa-widget',
				'description' => __( 'Font awesome widget.', 'pukka' )
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
		$cache = wp_cache_get( 'pukka-fa-widget', 'widget' );

		if ( !is_array( $cache ) )
			$cache = array();

		if ( ! isset ( $args['widget_id'] ) )
			$args['widget_id'] = $this->id;

		if ( isset ( $cache[ $args['widget_id'] ] ) )
			return print $cache[ $args['widget_id'] ];

		// widget logic

		extract( $args, EXTR_SKIP );

		$widget_string = $before_widget;

		// build icon
		if( !empty($instance['icon_name']) ){

			// open link
			if( !empty($instance['link']) ){
				$widget_string .= '<a href="'. $instance['link'] .'" target="'.  $instance['link_target'] .'">';
			}

			$icon_attrs = array();

			// icon style
			if( !empty($instance['icon_size']) ){
				$icon_attrs['style'] = 'font-size: '. $instance['icon_size'] .'px;';
			}

			if( !empty($instance['icon_color']) ){
				$icon_attrs['style'] .= 'color: '. $instance['icon_color'] .';';
			}

			// icon css classes
			$icon_attrs['class'] = 'fa '. $instance['icon_name'];

			// icon HTML tag
			$widget_string .= '<span class="fa-widget-icon"';

			if( !empty($instance['icon_align']) ){
				$widget_string .= ' style="text-align: '. $instance['icon_align'] .';"';
			}

			$widget_string .= '>';

			$widget_string .= '<i';
			foreach( $icon_attrs as $key => $value){
				$widget_string .= ' '. $key .'="'. $value .'"';
			}

			$widget_string .= '></i>';

			$widget_string .= '</span>';

			// close link
			if( !empty($instance['link']) ){
				$widget_string .= '</a>';
			}

		}

		// build title
		if( !empty($instance['title']) ){

			$widget_title = '';

			if( !empty($instance['title_align']) ){
				$widget_title .= str_replace('class=', 'style="text-align: '. $instance['title_align'] .';" class=', $before_title);
			}
			else{
				$widget_title .= $before_title;
			}


			// open link
			if( !empty($instance['link']) ){
				$widget_title .= '<a href="'. $instance['link'] .'" target="'.  $instance['link_target'] .'">';
			}

			$widget_title .= apply_filters('widget_title', $instance['title']);

			// close link
			if( !empty($instance['link']) ){
				$widget_title .= '</a>';
			}

			$widget_title .= $after_title;

			// finally
			$widget_string .= $widget_title;
		}

		// build text
		if( !empty($instance['text']) ){

			$widget_string .= '<div class="fa-widget-text"';

			if( !empty($instance['text_align']) ){
				$widget_string .= ' style="text-align: '. $instance['text_align'] .';"';
			}

			$widget_string .= '>'. $instance['text'] .'</div>';
		}

		$widget_string .= $after_widget;


		$cache[ $args['widget_id'] ] = $widget_string;

		wp_cache_set( 'pukka-fa-widget', $cache, 'widget' );

		print $widget_string;

	} // end widget


	public function flush_widget_cache()
	{
    	wp_cache_delete( 'pukka-fa-widget', 'widget' );
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
		$instance['title_align'] = $new_instance['title_align'];
		$instance['text'] = $new_instance['text'];
		$instance['text_align'] = $new_instance['text_align'];
		$instance['icon_name'] = $new_instance['icon_name'];
		$instance['icon_align'] = $new_instance['icon_align'];
		$instance['icon_color'] = $new_instance['icon_color'];
		$instance['icon_size'] = $new_instance['icon_size'];

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
		$title_align = isset( $instance['title_align'] ) ? $instance['title_align'] : '';

		$text = isset( $instance['text'] ) ? $instance['text'] : '';
		$text_align = isset( $instance['text_align'] ) ? $instance['text_align'] : '';

		$icon_name = isset( $instance['icon_name'] ) ? $instance['icon_name'] : '';
		$icon_align = isset( $instance['icon_align'] ) ? $instance['icon_align'] : '';
		$icon_color = isset( $instance['icon_color'] ) ? $instance['icon_color'] : '';
		$icon_size = isset( $instance['icon_size'] ) ? $instance['icon_size'] : '64';
		$link = isset( $instance['link'] ) ? $instance['link'] : '';
		$link_target = isset( $instance['link_target'] ) ? $instance['link_target'] : '';

		$icon_html = '';
		if( !empty($icon_name) ){
			$icon_html = '<i class="fa '. $icon_name .'"></i>';
			$icon_css_class = '';

		}
		else{
			$icon_css_class = 'pukka-file-placeholder';
		}
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:','pukka' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'title_align' ); ?>"><?php _e( 'Title alignment:','pukka' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'title_align' ); ?>" name="<?php echo $this->get_field_name( 'title_align' ); ?>">
	            <option value=""><?php _e('None', 'pukka'); ?></option>
	            <option value="left" <?php selected($title_align, "left") ?>><?php _e('Left', 'pukka'); ?></option>
	            <option value="right" <?php selected($title_align, "right") ?>><?php _e('Right', 'pukka'); ?></option>
	            <option value="center" <?php selected($title_align, "center") ?>><?php _e('Center', 'pukka'); ?></option>
			</select>
        </p>

		<p>
			<label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Text:','pukka'); ?></label>
        	<textarea class="widefat" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" cols="5" rows="5"><?php echo esc_textarea( $text ); ?></textarea>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'text_align' ); ?>"><?php _e( 'Text alignment:','pukka' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'text_align' ); ?>" name="<?php echo $this->get_field_name( 'text_align' ); ?>">
	            <option value=""><?php _e('None', 'pukka'); ?></option>
	            <option value="left" <?php selected($text_align, "left") ?>><?php _e('Left', 'pukka'); ?></option>
	            <option value="right" <?php selected($text_align, "right") ?>><?php _e('Right', 'pukka'); ?></option>
	            <option value="center" <?php selected($text_align, "center") ?>><?php _e('Center', 'pukka'); ?></option>
			</select>
        </p>

		<p>
			<label for="<?php echo $this->get_field_id( 'icon_align' ); ?>"><?php _e( 'Icon alignment:','pukka' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'icon_align' ); ?>" name="<?php echo $this->get_field_name( 'icon_align' ); ?>">
	            <option value="" ><?php _e('None', 'pukka'); ?></option>
	            <option value="left" <?php selected($icon_align, "left") ?>><?php _e('Left', 'pukka'); ?></option>
	            <option value="right" <?php selected($icon_align, "right") ?>><?php _e('Right', 'pukka'); ?></option>
	            <option value="center" <?php selected($icon_align, "center") ?>><?php _e('Center', 'pukka'); ?></option>
			</select>
        </p>

        <p>
			<label for="<?php echo $this->get_field_id( 'icon_size' ); ?>"><?php _e( 'Icon size:','pukka' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'icon_size' ); ?>" name="<?php echo $this->get_field_name( 'icon_size' ); ?>" type="text" value="<?php echo esc_attr($icon_size); ?>" size="2" />px
        </p>

        <p>
        	<script type="text/javascript">
		        jQuery(document).ready(function($) {
		        	var pukka_cp_elems = jQuery("#widgets-right .pukka-color-picker, .inactive-sidebar .pukka-color-picker");

		            pukka_cp_elems.wpColorPicker();
		        }).ajaxComplete(function(e, xhr, settings) {
					// new widget added? re init color pickers

		        	var widget_id = "<?php echo $this->id_base; ?>";

		            if( typeof settings.data != "undefined" && settings.data.search("action=save-widget") != -1 && settings.data.search("id_base=" + widget_id) != -1 ) {
		            	var pukka_cp_elems = jQuery("#widgets-right .pukka-color-picker, .inactive-sidebar .pukka-color-picker");
		                pukka_cp_elems.wpColorPicker();
		            }
		        });
			</script>
			<label for="<?php echo $this->get_field_id( 'icon_color' ); ?>"><?php _e( 'Icon color:','pukka' ); ?></label>
			<input class="widefat pukka-color-picker" id="<?php echo $this->get_field_id( 'icon_color' ); ?>" name="<?php echo $this->get_field_name( 'icon_color' ); ?>" type="text" value="<?php echo esc_attr($icon_color); ?>" data-default-color="#76b2ce" />
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

        <?php // Icon picker ?>
		<p>
			<span id="<?php echo $this->get_field_id('icon_name'); ?>-preview" class="pukka-icon-preview <?php echo $icon_css_class; ?>"><?php echo $icon_html; ?></span>
		</p>
		<p>
			<span class="pukka-upload-buttons">
				<button id="<?php echo $this->get_field_id('icon_name'); ?>-add" class="button button-primary" onclick="pukkaFAPicker.picker('<?php echo $this->get_field_id('icon_name'); ?>'); return false;" data-field_id="<?php echo $this->get_field_id('icon_name'); ?>"><?php _e('Choose icon', 'pukka'); ?></button>
				<a href="#" id="<?php echo $this->get_field_id('icon_name'); ?>-remove" onclick="pukkaFAPicker.removeIcon('<?php echo $this->get_field_id('icon_name'); ?>'); return false;" style="display:<?php echo ($icon_name != '' ? 'block' : 'none') ?>"><?php _e('Remove', 'pukka'); ?></a>
			</span>
			<input type="hidden" id="<?php echo $this->get_field_id('icon_name'); ?>" name="<?php echo $this->get_field_name('icon_name'); ?>" value="<?php echo esc_attr($icon_name); ?>" />
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

		add_thickbox();
		wp_enqueue_script('pukka-fa-picker');
		wp_enqueue_style('font-awesome');

		wp_enqueue_script('wp-color-picker');
		wp_enqueue_style('wp-color-picker');

		//wp_enqueue_script('pukka-fa-widget', get_template_directory_uri() .'/pukka/widgets/fa-widget/js/jquery.fa.picker.js', array('jquery', 'jquery-ui-tabs', 'thickbox'));
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
	register_widget( 'Pukka_FA_Widget' );
} );
