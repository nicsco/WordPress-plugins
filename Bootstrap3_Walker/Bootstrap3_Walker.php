class Bootstrap3_Walker extends Walker {

	#	http://core.trac.wordpress.org/browser/tags/3.3.2/wp-includes/nav-menu-template.php
	#	http://core.trac.wordpress.org/browser/tags/3.3.2/wp-includes/class-wp-walker.php

	var $tree_type = array( 'post_type', 'taxonomy', 'custom' );
	var $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );

	function start_lvl( &$output, $depth ) {
		$indent = str_repeat( "\t\t", $depth );
		$output .= "\n\t{$indent}<ul class='dropdown-menu'>";
	}

	function end_lvl( &$output, $depth ) {
		$indent = str_repeat( "\t\t", $depth );
		$output .= "\n\t{$indent}</ul>";
	}

	function start_el( &$output, $item, $depth, $args ) {
		global $wp_query;
		$level = $depth + 1;

		$indent = str_repeat( "\t\t", $depth );

		$classes = join( ' ', array_filter( $item->classes ) );

		$class = "level{$level} item{$item->ID}";

		$data	=	'';
		$before	=	'';
		$after	=	'';

		$class .= ( strpos( $classes, 'first' ) )					? ' first' : '';
		$class .= ( strpos( $classes, 'last' ) )					? ' last' : '';
		$class .= ( strpos( $classes, 'current-menu-item' ) )		? ' active current' : '';
		$class .= ( strpos( $classes, 'current-menu-parent' ) )		? ' active' : '';
		$class .= ( strpos( $classes, 'parent' ) )					? ' dropdown' : '';

		$output .= "\n{$indent}<li class='{$class}'>";

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

		$class = str_replace( 'dropdown', 'dropdown-toggle', $class );


		/* if element is a parent, add classes, data attribute and icon */
		if ( strpos( $classes, 'parent' ) ){
			$data	.=	' data-toggle="dropdown"';
			$after	.=	'<b class="caret"></b>';
		}

		$item_output .= "\n\t{$indent}<a{$attributes} class='{$class}'{$data}>\n\t\t{$indent}{$before}";
		$item_output .= apply_filters( 'the_title', $item->title, $item->ID );
		$item_output .= "{$after}\n\t{$indent}</a>";

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	function end_el(&$output, $item, $depth) {
		$indent = str_repeat( "\t\t", $depth );
		$output .= "\n{$indent}</li>";
	}

	function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output ) {

		if ( ! $element ) return;

		$id_field = $this->db_fields['id'];

		//display this element
		if ( is_array( $args[0] ) )
			$args[0]['has_children'] = ! empty( $children_elements[$element->$id_field] );

		$id = $element->$id_field;


		/* add remove class "last" from all previous level1 items, then add to this level1 menu itel */
		if ( ! $depth ){
			$output = preg_replace( "/level1([^']+) last/i", 'level1${1}', $output );
			$element->classes[] = 'last';
		}

		if ( isset( $children_elements[$id] ) && ( $max_depth == 0 || $max_depth > $depth + 1 ) ){

			$children_elements[$id][key( $children_elements[$id] )]->classes[] = 'first';
			end( $children_elements[$id] );
			$children_elements[$id][key( $children_elements[$id] )]->classes[] = 'last';
			reset( $children_elements[$id] );

			$element->classes[] = 'parent';
			$cb_args = array_merge( array(&$output, $element, $depth), $args);
			call_user_func_array(array(&$this, 'start_el'), $cb_args);

			$this->start_lvl( &$output, $depth );

			foreach( $children_elements[$id] as $child ){
				$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
			}

			unset( $children_elements[$id] );
			$this->end_lvl( &$output, $depth );

		}
		else{
			if ( ! $this->first ){
				$element->classes[] = 'first';
				$this->first = TRUE;
			}
			$cb_args = array_merge( array(&$output, $element, $depth), $args);
			call_user_func_array(array(&$this, 'start_el'), $cb_args);
		}

		$cb_args = array_merge( array(&$output, $element, $depth), $args);
		call_user_func_array(array(&$this, 'end_el'), $cb_args);

	}

}

function bootstrap3_nav_menu( $menu, $menu_class = 'nav navbar-nav', $args = array() ){

	if ( ! is_array( $args ) ) return;

	$args['menu']		= $menu;
	$args['container']	= NULL;
	$args['menu_class']	= $menu_class;
	$args['walker']		= new Bootstrap3_Walker();

	wp_nav_menu( $args );

}