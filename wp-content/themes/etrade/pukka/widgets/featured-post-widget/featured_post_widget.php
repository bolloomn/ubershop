<?php
/**
* Pukka Themes Featured post widget
*/

class Pukka_Featured_Post_Widget extends WP_Widget {

	public function __construct() {

		parent::__construct(
			'pukka-featured-post-widget',
			__( 'Pukka - Featured Post Widget', 'pukka' ),
			array(
				'classname'  => 'pukka-featured-post-widget',
				'description' => __( 'Featured post widget.', 'pukka' )
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
		$cache = wp_cache_get( 'pukka-featured-post-widget', 'widget' );

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

		if( !empty($instance['post_id']) ){

			$post = get_post($instance['post_id']);

			if( $post != null ){

				$post_title = apply_filters('post_title', $post->post_title);
				$post_title_align = !empty($instance['post_title_align']) ? $instance['post_title_align'] : '';
				$title_above = !empty($instance['title_above']) ? true : false;

				$post_content = !empty($instance['text']) ? $instance['text'] : $post->post_excerpt;
				$text_align = !empty($instance['text_align']) ? $instance['text_align'] : '';

				$image_size = !empty($instance['image_size']) ? $instance['image_size'] : 'thumbnail';
				$image_align = !empty($instance['image_align']) ? $instance['image_align'] : '';

				$button_align = !empty($instance['button_align']) ? $instance['button_align'] : '';

				// build title
				$args_title = array();

				if( $post_title_align == 'left' ){
					$args_title['style'] = 'text-align: left;';
				}
				elseif( $post_title_align == 'right' ){
					$args_title['style'] = 'text-align: right;';
				}
				elseif( $post_title_align == 'center' ){
					$args_title['style'] = 'text-align: center;';
				}

				$html_title = '<h4';

				foreach( $args_title as $key => $value ){
					$html_title .= ' '. $key .'="'. $value .'"';
				}

				$html_title .= '><a href="'. get_permalink($post->ID) .'" title="'. esc_attr($post_title) .'">'. $post_title .'</a></h4>' ."\n";

				// build image
				$attachment_id = get_post_thumbnail_id($post->ID);
				if( !empty($attachment_id) ){
					$post_image = wp_get_attachment_image_src($attachment_id, $image_size);

					$image_class = '';
					if( $image_align == 'left' ){
						$image_class = 'alignleft';
					}
					elseif( $image_align == 'right' ){
						$image_class = 'alignright';
					}
					elseif( $image_align == 'center' ){
						$image_class = 'aligncenter';
					}

					$html_image = '<a href="'. get_permalink($post->ID) .'" class="read-more button">' ."\n";
					$html_image .= '<img src="'. $post_image[0] .'" class="'. $image_class .'" />';
					$html_image .= '</a>' ."\n";
				}
				else{
					$html_image = '';
				}

				// build content
				$args_txt = array();
				$args_txt['class'] = 'featured-post-content';
				$html_content = '';

				if( $text_align == 'left' ){
					$args_txt['style'] = 'text-align: left;';
				}
				elseif( $text_align == 'right' ){
					$args_txt['style'] = 'text-align: right;';
				}
				elseif( $text_align == 'center' ){
					$args_txt['style'] = 'text-align: center;';
				}

				$html_content .= '<div';

				foreach( $args_txt as $key => $value ){
					$html_content .= ' '. $key .'="'. $value .'"';
				}

				$html_content .= '>';
				$html_content .= $post_content;
				$html_content .= '</div>' ."\n";


				// build button
				$args_button = array();
				$args_button['class'] = 'featured-post-more';

				if( $button_align == 'left' ){
					$args_button['style'] = 'text-align: left;';
				}
				elseif( $button_align == 'right' ){
					$args_button['style'] = 'text-align: right;';
				}
				elseif( $button_align == 'center' ){
					$args_button['style'] = 'text-align: center;';
				}

				$html_button = '<div';
				foreach( $args_txt as $key => $value ){
					$html_button .= ' '. $key .'="'. $value .'"';
				}
				$html_button .= '>';

				$html_button .= '<a href="'. get_permalink($post->ID) .'" class="read-more button">'. __('Read post', 'pukka') .'</a>' ."\n";

				$html_button .= '</div>' ."\n";


				// build widget HTML
				if( $title_above ){
					$widget_string .= $html_title;
					$widget_string .= $html_image;
				}
				else{
					$widget_string .= $html_image;
					$widget_string .= $html_title;
				}

				$widget_string .= $html_content;

				$widget_string .= $html_button;
			}
		}

		$widget_string .= $after_widget;

		$cache[ $args['widget_id'] ] = $widget_string;

		wp_cache_set( 'pukka-featured-post-widget', $cache, 'widget' );

		print $widget_string;
	} // end widget


	public function flush_widget_cache()
	{
    	wp_cache_delete( 'pukka-featured-post-widget', 'widget' );
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
		$instance['post_title_align'] = $new_instance['post_title_align'];
		$instance['title_above'] = $new_instance['title_above'];
		$instance['text'] = $new_instance['text'];
		$instance['text_align'] = $new_instance['text_align'];
		$instance['image_align'] = $new_instance['image_align'];
		$instance['image_size'] = $new_instance['image_size'];
		$instance['post_id'] = $new_instance['post_id'];

		$instance['button_align'] = $new_instance['button_align'];

		return $instance;

	} // end widget

	/**
	 * Generates the administration form for the widget.
	 *
	 * @param array instance The array of keys and values for the widget.
	 */
	public function form( $instance ) {

		// TODO: Define default values for your variables
		$instance = wp_parse_args(
			(array) $instance
		);

		//set widget values
		$title = isset( $instance['title'] ) ? $instance['title'] : '';

		$post_id = isset( $instance['post_id'] ) ? $instance['post_id'] : '';
		$post_title_align = isset( $instance['post_title_align'] ) ? $instance['post_title_align'] : '';
		$title_above = isset( $instance['title_above'] ) ? $instance['title_above'] : '';

		$text = isset( $instance['text'] ) ? $instance['text'] : '';
		$text_align = isset( $instance['text_align'] ) ? $instance['text_align'] : '';

		$image_align = isset( $instance['image_align'] ) ? $instance['image_align'] : '';
		$image_size = isset( $instance['image_size'] ) ? $instance['image_size'] : '';

		$button_align = isset( $instance['button_align'] ) ? $instance['button_align'] : '';

		$image_sizes = pukka_get_thumbnail_sizes();
		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:','pukka' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>

		<?php // Autocomplete field ?>
		<?php

			$lang = '';
			if( defined("ICL_LANGUAGE_CODE") ){
				$lang = ICL_LANGUAGE_CODE;
			}

			if( !isset($subtype) ){
				// search 'post types'
				$subtype = 'post';
			}

			if( !isset($source) ){
				// search 'post' post type
				$source = 'post';
			}

			$post_title = '';
			if( $post_id != '' ){
				$post_title = get_the_title($post_id);
			}
		?>

		<p data-field_id="<?php echo $this->get_field_id('post_id'); ?>" data-widget_id="<?php echo $this->id; ?>">
			<label for="<?php echo $this->get_field_id( 'link_target' ); ?>"><?php _e( 'Post title:','pukka' ); ?></label>
			<input type="text" value="<?php echo $post_title; ?>" id="<?php echo $this->id; ?>-autocomplete" class="pukka-featured-post-autocomplete" data-type="post" data-source="" data-lang="<?php echo $lang; ?>" />
			<input type="hidden" id="<?php echo $this->get_field_id( 'post_id' );; ?>" name="<?php echo $this->get_field_name( 'post_id' );; ?>" value="<?php echo $post_id; ?>" />
			<input type="hidden" id="nonce-<?php echo $this->id; ?>" value="<?php echo wp_create_nonce('pukka_ajax_autocomplete'); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'post_title_align' ); ?>"><?php _e( 'Post title alignment:','pukka' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'post_title_align' ); ?>" name="<?php echo $this->get_field_name( 'post_title_align' ); ?>">
	            <option value="" ><?php _e('None', 'pukka'); ?></option>
	            <option value="left" <?php selected($post_title_align, "left") ?>><?php _e('Left', 'pukka'); ?></option>
	            <option value="right" <?php selected($post_title_align, "right") ?>><?php _e('Right', 'pukka'); ?></option>
	            <option value="center" <?php selected($post_title_align, "center") ?>><?php _e('Center', 'pukka'); ?></option>
			</select>
        </p>

		<p>
			<label for="<?php echo $this->get_field_id( 'text' ); ?>"><?php _e( 'Text (or post excerpt will be used instead):','pukka'); ?></label>
        	<textarea class="widefat" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" cols="5" rows="5"><?php echo esc_textarea( $text ); ?></textarea>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'text_align' ); ?>"><?php _e( 'Text alignment:','pukka' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'text_align' ); ?>" name="<?php echo $this->get_field_name( 'text_align' ); ?>">
	            <option value="" ><?php _e('None', 'pukka'); ?></option>
	            <option value="left" <?php selected($text_align, "left") ?>><?php _e('Left', 'pukka'); ?></option>
	            <option value="right" <?php selected($text_align, "right") ?>><?php _e('Right', 'pukka'); ?></option>
	            <option value="center" <?php selected($text_align, "center") ?>><?php _e('Center', 'pukka'); ?></option>
			</select>
        </p>

		<p>
			<label for="<?php echo $this->get_field_id( 'title_above' ); ?>"><?php _e( 'Title above image:','pukka' ); ?></label>
			<input id="<?php echo $this->get_field_id( 'title_above' ); ?>" name="<?php echo $this->get_field_name( 'title_above' ); ?>" type="checkbox" value="on" <?php checked("on", $title_above); ?> />
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
        	<label for="<?php echo $this->get_field_id( 'image_size' ); ?>"><?php _e( 'Image size:','pukka' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'image_size' ); ?>" name="<?php echo $this->get_field_name( 'image_size' ); ?>">
			<?php foreach($image_sizes as $size => $atts) : ?>
				<option value="<?php echo $size; ?>" <?php selected($size, $image_size); ?>><?php echo implode( 'x', $atts ); ?></option>
			<?php endforeach; ?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'button_align' ); ?>"><?php _e( 'Read more button alignment:','pukka' ); ?></label>
			<select id="<?php echo $this->get_field_id( 'button_align' ); ?>" name="<?php echo $this->get_field_name( 'button_align' ); ?>">
	            <option value="" ><?php _e('None', 'pukka'); ?></option>
	            <option value="left" <?php selected($button_align, "left") ?>><?php _e('Left', 'pukka'); ?></option>
	            <option value="right" <?php selected($button_align, "right") ?>><?php _e('Right', 'pukka'); ?></option>
	            <option value="center" <?php selected($button_align, "center") ?>><?php _e('Center', 'pukka'); ?></option>
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

		wp_enqueue_script('pukka-featured-post-widget', get_template_directory_uri() .'/pukka/widgets/featured-post-widget/js/jquery.featured.post.widget.js', array('jquery', 'jquery-ui-autocomplete'));
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
	register_widget( 'Pukka_Featured_Post_Widget' );
} );
