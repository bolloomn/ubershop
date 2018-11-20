<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Pukka_Product_List extends WC_Widget {
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->widget_cssclass    = 'pukka woocommerce widget_best_sellers';
		$this->widget_description = __( "List products.", 'pukka' );
		$this->widget_id          = 'pukka_products_list';
		$this->widget_name        = __( 'Pukka - Product List', 'pukka' );
		$this->settings           = array(
			'title'  => array(
				'type'  => 'text',
				'std'   => __( 'Products', 'woocommerce' ),
				'label' => __( 'Title', 'woocommerce' )
			),
			'number' => array(
				'type'  => 'number',
				'step'  => 1,
				'min'   => 1,
				'max'   => '',
				'std'   => 4,
				'label' => __( 'Number of products to show', 'woocommerce' )
			),
			'show' => array(
				'type'  => 'select',
				'std'   => '',
				'label' => __( 'Show', 'woocommerce' ),
				'options' => array(
					''         => __( 'All Products', 'woocommerce' ),
					'featured' => __( 'Featured Products', 'woocommerce' ),
					'onsale'   => __( 'On-sale Products', 'woocommerce' ),
				)
			),
			'orderby' => array(
				'type'  => 'select',
				'std'   => 'date',
				'label' => __( 'Order by', 'woocommerce' ),
				'options' => array(
					'date'   => __( 'Date', 'woocommerce' ),
					'price'  => __( 'Price', 'woocommerce' ),
					'rand'   => __( 'Random', 'woocommerce' ),
					'sales'  => __( 'Sales', 'woocommerce' ),
					//'rating' => __( 'Rating', 'woocommerce' ),
				)
			),
			'order' => array(
				'type'  => 'select',
				'std'   => 'desc',
				'label' => _x( 'Order', 'Sorting order', 'woocommerce' ),
				'options' => array(
					'asc'  => __( 'ASC', 'woocommerce' ),
					'desc' => __( 'DESC', 'woocommerce' ),
				)
			),
			'width' => array(
				'type'  => 'select',
				'std'   => 'desc',
				'label' => __( 'Widget width', 'woocommerce' ),
				'options' => array(
					'narrow' => __( 'Narrow', 'woocommerce' ),
					'normal'  => __( 'Normal', 'woocommerce' ),
				)
			),
			'hide_free' => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => __( 'Hide free products', 'woocommerce' )
			),
//			'show_hidden' => array(
//				'type'  => 'checkbox',
//				'std'   => 0,
//				'label' => __( 'Show hidden products', 'woocommerce' )
//			)
		);
		parent::__construct();
	}

	function widget( $args, $instance ) {
		if ( $this->get_cached_widget( $args ) )
			return;

		ob_start();
		extract( $args );

		$title       = apply_filters( 'widget_title', $instance['title'], $instance, $this->id_base );
		$number      = absint( $instance['number'] );
		$show        = sanitize_title( $instance['show'] );
		$orderby     = sanitize_title( $instance['orderby'] );
		$order       = sanitize_title( $instance['order'] );
		$width       = sanitize_title( $instance['width'] );
		$show_rating = false;
		
//		if('rating' == $orderby){
//			add_filter( 'posts_clauses',  array( WC()->query, 'order_by_rating_post_clauses' ) );
//		}

    	$query_args = array(
    		'posts_per_page' => $number,
    		'post_status' 	 => 'publish',
    		'post_type' 	 => 'product',
    		'no_found_rows'  => 1,
    		'order'          => $order == 'asc' ? 'asc' : 'desc'
    	);

    	$query_args['meta_query'] = array();

//    	if ( empty( $instance['show_hidden'] ) ) {
//			$query_args['meta_query'][] = WC()->query->visibility_meta_query();
//			$query_args['post_parent']  = 0;
//		}

		if ( ! empty( $instance['hide_free'] ) ) {
    		$query_args['meta_query'][] = array(
			    'key'     => '_price',
			    'value'   => 0,
			    'compare' => '>',
			    'type'    => 'DECIMAL',
			);
    	}

//	    $query_args['meta_query'][] = WC()->query->stock_status_meta_query();
	    $query_args['meta_query']   = array_filter( $query_args['meta_query'] );

    	switch ( $show ) {
    		case 'featured' :
    			$query_args['meta_query'][] = array(
					'key'   => '_featured',
					'value' => 'yes'
				);
    			break;
    		case 'onsale' :
    			$product_ids_on_sale = wc_get_product_ids_on_sale();
				$product_ids_on_sale[] = 0;
				$query_args['post__in'] = $product_ids_on_sale;
    			break;
    	}

    	switch ( $orderby ) {
			case 'price' :
				$query_args['meta_key'] = '_price';
    			$query_args['orderby']  = 'meta_value_num';
				break;
			case 'rand' :
    			$query_args['orderby']  = 'rand';
				break;
			case 'sales' :
				$query_args['meta_key'] = 'total_sales';
    			$query_args['orderby']  = 'meta_value_num';
				break;
			case 'rating' :			
				$query_args[] = WC()->query->get_meta_query();
				break;
			default :
				$query_args['orderby']  = 'date';
    	}

		$r = new WP_Query( $query_args );
		
		if('narrow' == $width){
			$class = 'offer-narrow';
		}else{
			$class = 'offer-normal';
		}
		
		if ( $r->have_posts() ) {
			
			$before_widget = str_replace('class="', 'class="' . $class . ' ', $before_widget);
			
			echo $before_widget;

			if ( $title )
				echo $before_title . $title . $after_title;

			echo '<ul>';
			
			global $product;
			while ( $r->have_posts()) {
				$r->the_post();
				?>
				<li class="clearfix">
					<span class="offer-img">
						<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail('shop_catalog'); ?></a>
					</span> <!-- .offer-img -->
					<div class="offer-content">
						<h5><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
						<span class="offer-price"><?php $product = new WC_Product( get_the_ID() ); echo $product->get_price_html(); ?></span>
					</div> <!-- .offer-content -->
				</li>
				<?php
			}

			echo '</ul>';

			echo $after_widget;
		}
				
//		if('rating' == $orderby){
//			remove_filter( 'posts_clauses', array( WC()->query, 'order_by_rating_post_clauses' ) );
//		}
		
		wp_reset_postdata();

		$content = ob_get_clean();

		echo $content;

		$this->cache_widget( $args, $content );
	}
 
}

//add_action( 'widgets_init', create_function( '', 'register_widget("Pukka_Product_List");'), 99);