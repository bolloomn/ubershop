<?php


/**
 * Define new (dashboard) Menu Walker
 * (because we need custom fields)
 */
add_filter( 'wp_edit_nav_menu_walker', 'pukka_edit_walker', 10, 2 );
function pukka_edit_walker($walker,$menu_id) {

	return 'Walker_Nav_Menu_Dashboard_Custom';

}
include_once('edit_custom_walker.php');

/**
 * Save menu custom fields
 */
add_action( 'wp_update_nav_menu_item', 'pukka_update_custom_nav_fields', 10, 3 );
function pukka_update_custom_nav_fields( $menu_id, $menu_item_db_id, $args ) {

	$check = array('nolink', 'hide', 'type_menu');

	foreach ( $check as $key )
	{
//		if(!isset($_POST['menu-item-'.$key][$menu_item_db_id]))
//		{
//			$_POST['menu-item-'.$key][$menu_item_db_id] = "";
//		}

		$new = isset($_POST['menu-item-'.$key][$menu_item_db_id])
				? $_POST['menu-item-'.$key][$menu_item_db_id]
				: '';

		$old = get_post_meta( $menu_item_db_id, '_menu_item_'.$key, true );

		if( '' != $new ) {
			update_post_meta( $menu_item_db_id, '_menu_item_' . $key, $new );
		}
		elseif( !empty($old) && empty($new) ){
			delete_post_meta($menu_item_db_id, '_menu_item_' . $key, $old);
		}
	}

}


/**
 * Set new properties based on menu item's custom fields
 */
add_filter( 'wp_setup_nav_menu_item', 'pukka_add_custom_nav_fields' );
function pukka_add_custom_nav_fields( $menu_item ) {

	$menu_item->nolink    = get_post_meta( $menu_item->ID, '_menu_item_nolink', true );
	$menu_item->hide      = get_post_meta( $menu_item->ID, '_menu_item_hide', true );
	$menu_item->type_menu = get_post_meta( $menu_item->ID, '_menu_item_type_menu', true );

	return $menu_item;
}




/**
* Custom Menu Nav Walker, used for adding columns and image to menus
*/
class PukkaNavWide extends Walker_Nav_Menu
{
	function start_lvl( &$output, $depth = 0, $args = array() ) {

//		if( $depth == 0 ){
//			$class_names = 'submenu-inner';
//		}
//		else{
//			$class_names = 'menu-column-links';
//		}

		if( 0 == $depth ){
			$class_names = 'menu-dropdown';
		}
		else{
			$class_names = 'menu-column-links';
		}

		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<div class=". $class_names ."><ul>\n";
	}

	function end_lvl( &$output, $depth = 1, $args = array() ) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul></div>\n";
	}

	function start_el ( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		global $wp_query;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $value . $class_names .'>';

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';


		// BEGIN: Custom <a> CSS classes
		$item_classes = array();

		// First level item ?
		if ( $depth == 0 ) {
			// If so add custom class
			$item_classes[] .= 'nav-top-link';
		}

		$attributes .= ' class="'. implode(' ', $item_classes) .'"';

		// END: Custom <a> CSS classes

//		$description = '';
//		if(strpos($class_names,'image-column') !== false){
//			$description = '<img src="'.$item->description.'" alt=" "/>';
//		}

		// quick fix for: https://core.trac.wordpress.org/ticket/18232
		if( is_array($args) ){
			$args = (object)$args;
		}


		// if "hide item" checkbox wasn't checked
		$item_output = '';
		if( empty($item->hide) ) {
			$item_output .= $args->before;
			$item_output .= '<a' . $attributes . '>';
			$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
			//		$item_output .= $description; // <img /> or empty
			$item_output .= '</a>';
			$item_output .= $args->after;
		}

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}


/* Menu item filters */

/**
 * Add some CSS classes to the <li> element (based on item custom fields)
 */
add_filter('nav_menu_css_class', 'pukka_menu_item_classes', 3, 10);
function pukka_menu_item_classes($classes, $item, $args ){

	if( !empty($item->type_menu)  ){
		$classes[] = 'submenu-'. $item->type_menu;
	}


	if( !empty($item->nolink) ){
		$classes[] = 'no-link';
	}

	return $classes;
}