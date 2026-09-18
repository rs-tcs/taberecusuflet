<?php
if (!class_exists('GummNavMenuWalker')) App::uses('GummNavMenuWalker', 'Lib/Walker');

class GummResponsiveNavMenuWalker extends GummNavMenuWalker {
    
	/**
	 * Traverse elements to create list from elements.
	 *
	 * Display one element if the element doesn't have any children otherwise,
	 * display the element and its children. Will only traverse up to the max
	 * depth and no ignore elements under that depth. It is possible to set the
	 * max depth to include all depths, see walk() method.
	 *
	 * This method shouldn't be called directly, use the walk() method instead.
	 *
	 * @since 2.5.0
	 *
	 * @param object $element Data object
	 * @param array $children_elements List of elements to continue traversing.
	 * @param int $max_depth Max depth to traverse.
	 * @param int $depth Depth of current element.
	 * @param array $args
	 * @param string $output Passed by reference. Used to append additional content.
	 * @return null Null on failure with no changes to parameters.
	 */
	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
        if (!$element) return;
        
        $element->hasChildren = isset($children_elements[$element->ID]) && !empty($children_elements[$element->ID]);
        
        return parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output);
	}
    
    /**
    * @see Walker::start_lvl()
    * @since 3.0.0
    *
    * @param string $output Passed by reference. Used to append additional content.
    * @param int $depth Depth of page. Used for padding.
    */
    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"sub-menu dropdown-menu\" role=\"menu\" aria-labelledby=\"dLabel\">\n";
    }
    
	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item Menu item data object.
	 * @param int $depth Depth of menu item. Used for padding.
	 * @param int $current_page Menu item ID.
	 * @param object $args
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $value . $class_names .'>';

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

		$item_output = $args->before;
		$item_output .= $this->beforeLink($item, $depth);
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $this->afterLink($item, $depth);
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
    
    public function beforeLink($item, $depth=0) {
        $outputHtml = '';
        if (isset($item->gummicon) && $item->gummicon && $depth === 0) {
            $outputHtml = '<i class="' . $item->gummicon . '"></i>';
        } elseif ($depth === 0) {
            $outputHtml = '<i class="icon-angle-right"></i>';
        }
        

        
        return $outputHtml;
    }
    
    public function afterLink($item, $depth=0) {
        $outputHtml = '';
        if ($item->hasChildren) {
            $iconClass = ($item->current_item_ancestor) ? 'icon-caret-up' : 'icon-caret-down';
            $outputHtml = '<a class="dropdown-link ' . $iconClass . '" href="#"></a>';
        }
        
        return $outputHtml;
    }
}
?>