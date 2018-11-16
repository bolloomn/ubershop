<?php
/**
 * Features list item template
 */
?>
<div class="brands-list__item <?php
	echo jet_elements_tools()->col_classes( array(
		'desk' => $this->__get_html( 'columns' ),
		'tab'  => $this->__get_html( 'columns_tablet' ),
		'mob'  => $this->__get_html( 'columns_mobile' ),
	) );
	echo wp_kses_post( $this->__loop_item( array( 'item_url', 'url' ), ' with-link ' ) );
?>"><?php
	echo wp_kses_post( $this->__loop_item( array( 'item_url', 'url' ), '<a href="%s" class="brands-list__item-link">' ) );
	echo wp_kses_post( $this->__loop_item( array( 'item_image', 'url' ), '<img src="%s" alt="" class="brands-list__item-img">'  ));
	echo wp_kses_post( $this->__loop_item( array( 'item_name' ), '<h5 class="brands-list__item-name">%s</h5>'  ));
	echo wp_kses_post( $this->__loop_item( array( 'item_desc' ), '<div class="brands-list__item-desc">%s</div>'  ));
	echo wp_kses_post( $this->__loop_item( array( 'item_url', 'url' ), '</a>' ) );
?></div>
