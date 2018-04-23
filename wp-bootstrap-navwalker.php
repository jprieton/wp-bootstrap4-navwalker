<?php

/**
 * If this file is called directly, abort.
 */
if ( !defined( 'ABSPATH' ) ) {
  die( 'Direct access is forbidden.' );
}

/**
 * Class Name: WP_Bootstrap_Navwalker
 * Plugin Name: WP Bootstrap4 Navwalker
 * Plugin URI:  https://github.com/jprieton/wp-bootstrap4-navwalker
 * Description: A custom WordPress nav walker class to implement the Bootstrap 4.x navigation style in a custom theme using the WordPress built in menu manager.
 * Author: Javier Prieto
 * Version: 1.1.2
 * Author URI: https://github.com/jprieton/
 * GitHub Plugin URI: https://github.com/jprieton/wp-bootstrap4-navwalker
 * GitHub Branch: master
 * License: GPL-3.0+
 * License URI: https://www.gnu.org/licenses/gpl-3.0.txt
 */
/* Check if Class Exists. */
if ( !class_exists( 'WP_Bootstrap_Navwalker' ) ) {

  /**
   * WP_Bootstrap_Navwalker class
   *
   * @package        Template
   * @subpackage     Bootstrap4
   *
   * @since          1.0.0
   * @see            https://getbootstrap.com/docs/4.0/components/navbar/
   * @extends        Walker_Nav_Menu
   * @author         Javier Prieto
   */
  class WP_Bootstrap_Navwalker extends Walker_Nav_Menu {

    /**
     * @since       1.0.0
     * @access      public
     * @var type    bool
     */
    private $dropdown = false;

    /**
     * Starts the list before the elements are added.
     *
     * @since       1.0.0
     *
     * @see Walker::start_lvl()
     *
     * @param string   $output Passed by reference. Used to append additional content.
     * @param int      $depth  Depth of menu item. Used for padding.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     */
    public function start_lvl( &$output, $depth = 0, $args = array() ) {
      if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
        $t = '';
        $n = '';
      } else {
        $t = "\t";
        $n = "\n";
      }

      $this->dropdown = true;
      $output         .= $n . str_repeat( $t, $depth ) . '<div class="dropdown-menu" role="menu">' . $n;
    }

    /**
     * Ends the list of after the elements are added.
     *
     * @since       1.0.0
     *
     * @see Walker::end_lvl()
     *
     * @param string   $output Passed by reference. Used to append additional content.
     * @param int      $depth  Depth of menu item. Used for padding.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     */
    public function end_lvl( &$output, $depth = 0, $args = array() ) {
      if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
        $t = '';
        $n = '';
      } else {
        $t = "\t";
        $n = "\n";
      }

      $this->dropdown = false;
      $output         .= $n . str_repeat( $t, $depth ) . '</div>' . $n;
    }

    /**
     * Starts the element output.
     *
     * @since 1.0.0
     *
     * @see Walker::start_el()
     *
     * @param string   $output Passed by reference. Used to append additional content.
     * @param WP_Post  $item   Menu item data object.
     * @param int      $depth  Depth of menu item. Used for padding.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     * @param int      $id     Current item ID.
     */
    public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
      if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
        $t = '';
        $n = '';
      } else {
        $t = "\t";
        $n = "\n";
      }

      $indent = str_repeat( $t, $depth );

      if ( 0 === strcasecmp( $item->attr_title, 'divider' ) && $this->dropdown ) {
        $output .= $indent . '<div class="dropdown-divider"></div>' . $n;
        return;
      } elseif ( 0 === strcasecmp( $item->title, 'divider' ) && $this->dropdown ) {
        $output .= $indent . '<div class="dropdown-divider"></div>' . $n;
        return;
      }

      $classes   = empty( $item->classes ) ? array() : (array) $item->classes;
      $classes[] = 'menu-item-' . $item->ID;
      $classes[] = 'nav-item';

      if ( $args->walker->has_children ) {
        $classes[] = 'dropdown';
      }

      if ( 0 < $depth ) {
        $classes[] = 'dropdown-menu';
      }

      /**
       * Filters the arguments for a single nav menu item.
       *
       * @since 1.2.0
       *
       * @param stdClass $args  An object of wp_nav_menu() arguments.
       * @param WP_Post  $item  Menu item data object.
       * @param int      $depth Depth of menu item. Used for padding.
       */
      $args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

      /**
       * Filters the CSS class(es) applied to a menu item's list item element.
       *
       * @since 3.0.0
       * @since 4.1.0 The `$depth` parameter was added.
       *
       * @param array    $classes The CSS classes that are applied to the menu item's `<li>` element.
       * @param WP_Post  $item    The current menu item.
       * @param stdClass $args    An object of wp_nav_menu() arguments.
       * @param int      $depth   Depth of menu item. Used for padding.
       */
      $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
      $class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

      /**
       * Filters the ID applied to a menu item's list item element.
       *
       * @since 3.0.1
       * @since 4.1.0 The `$depth` parameter was added.
       *
       * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
       * @param WP_Post  $item    The current menu item.
       * @param stdClass $args    An object of wp_nav_menu() arguments.
       * @param int      $depth   Depth of menu item. Used for padding.
       */
      $id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
      $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

      if ( !$this->dropdown ) {
        $output .= $indent . '<li' . $id . $class_names . '>' . $n . $indent . $t;
      }

      $atts           = array();
      $atts['title']  = !empty( $item->attr_title ) ? $item->attr_title : '';
      $atts['target'] = !empty( $item->target ) ? $item->target : '';
      $atts['rel']    = !empty( $item->xfn ) ? $item->xfn : '';
      $atts['href']   = !empty( $item->url ) ? $item->url : '';

      /**
       * Filters the HTML attributes applied to a menu item's anchor element.
       *
       * @since 3.6.0
       * @since 4.1.0 The `$depth` parameter was added.
       *
       * @param array $atts {
       *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
       *
       *     @type string $title  Title attribute.
       *     @type string $target Target attribute.
       *     @type string $rel    The rel attribute.
       *     @type string $href   The href attribute.
       * }
       * @param WP_Post  $item  The current menu item.
       * @param stdClass $args  An object of wp_nav_menu() arguments.
       * @param int      $depth Depth of menu item. Used for padding.
       */
      $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

      if ( $args->walker->has_children ) {
        $atts['data-toggle']   = 'dropdown';
        $atts['aria-haspopup'] = 'true';
        $atts['aria-expanded'] = 'false';
      }

      $attributes = '';
      foreach ( $atts as $attr => $value ) {
        if ( !empty( $value ) ) {
          $value      = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
          $attributes .= ' ' . $attr . '="' . $value . '"';
        }
      }

      /** This filter is documented in wp-includes/post-template.php */
      $title = apply_filters( 'the_title', $item->title, $item->ID );

      /**
       * Filters a menu item's title.
       *
       * @since 4.4.0
       *
       * @param string   $title The menu item's title.
       * @param WP_Post  $item  The current menu item.
       * @param stdClass $args  An object of wp_nav_menu() arguments.
       * @param int      $depth Depth of menu item. Used for padding.
       */
      $title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

      $item_classes = array( 'nav-link' );

      if ( $args->walker->has_children ) {
        $item_classes[] = 'dropdown-toggle';
      }

      if ( 0 < $depth ) {
        $item_classes = array_diff( $item_classes, [ 'nav-link' ] );
        $item_classes[] = 'dropdown-item';
      }

      $item_output = $args->before;
      $item_output .= '<a class="' . implode( ' ', $item_classes ) . '" ' . $attributes . '>';
      $item_output .= $args->link_before . $title . $args->link_after;
      $item_output .= '</a>';
      $item_output .= $args->after;

      /**
       * Filters a menu item's starting output.
       *
       * The menu item's starting output only includes `$args->before`, the opening `<a>`,
       * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
       * no filter for modifying the opening and closing `<li>` for a menu item.
       *
       * @since 3.0.0
       *
       * @param string   $item_output The menu item's starting HTML output.
       * @param WP_Post  $item        Menu item data object.
       * @param int      $depth       Depth of menu item. Used for padding.
       * @param stdClass $args        An object of wp_nav_menu() arguments.
       */
      $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    }

    /**
     * Ends the element output, if needed.
     *
     * @since 1.0.0
     *
     * @see Walker::end_el()
     *
     * @param string   $output Passed by reference. Used to append additional content.
     * @param WP_Post  $item   Page data object. Not used.
     * @param int      $depth  Depth of page. Not Used.
     * @param stdClass $args   An object of wp_nav_menu() arguments.
     */
    public function end_el( &$output, $item, $depth = 0, $args = array() ) {
      if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
        $t = '';
        $n = '';
      } else {
        $t = "\t";
        $n = "\n";
      }

      $output .= $this->dropdown ? '' : str_repeat( $t, $depth ) . '</li>' . $n;
    }

    /**
     * Menu Fallback
     *
     * @since 1.0.0
     *
     * @param array $args passed from the wp_nav_menu function.
     */
    public static function fallback( $args ) {
      if ( current_user_can( 'edit_theme_options' ) ) {

        $defaults = array(
            'container'       => 'div',
            'container_id'    => false,
            'container_class' => false,
            'menu_class'      => 'menu',
            'menu_id'         => false,
        );
        $args     = wp_parse_args( $args, $defaults );
        if ( !empty( $args['container'] ) ) {
          echo sprintf( '<%s id="%s" class="%s">', $args['container'], $args['container_id'], $args['container_class'] );
        }
        echo sprintf( '<ul id="%s" class="%s">', $args['container_id'], $args['container_class'] ) .
        '<li class="nav-item">' .
        '<a href="' . admin_url( 'nav-menus.php' ) . '" class="nav-link">' . __( 'Add a menu' ) . '</a>' .
        '</li></ul>';
        if ( !empty( $args['container'] ) ) {
          echo sprintf( '</%s>', $args['container'] );
        }
      }
    }

  }

}
