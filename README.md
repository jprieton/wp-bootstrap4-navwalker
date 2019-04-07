# WP Bootstrap 4.x Navwalker

A custom WordPress nav walker class based on 3.x version of https://github.com/wp-bootstrap/wp-bootstrap-navwalker/ to fully implement the Twitter Bootstrap 4.x navigation style in a custom theme using the WordPress built in menu manager

## Installation

### Using it as a plugin

Place **wp-bootstrap-navwalker.php** in your WordPress plugin folder `/wp-content/plugins/wp-bootstrap4-navwalker/` and activate.

### ...Or using it as a dependency on your theme

Place **wp-bootstrap-navwalker.php** in your WordPress theme folder `/wp-content/your-theme/`

Open your WordPress themes **functions.php** file  `/wp-content/your-theme/functions.php` and add the following code:

```php
// Register Custom Navigation Walker
require_once get_template_directory() . '/wp-bootstrap-navwalker.php';
```

If you encounter errors with the above code use a check like this to return clean errors to help diagnose the problem.

```php
if ( ! file_exists( get_template_directory() . '/wp-bootstrap-navwalker.php' ) ) {
  // file does not exist... return an error.
  return new WP_Error( 'wp-bootstrap-navwalker-missing', __( 'It appears the wp-bootstrap-navwalker.php file may be missing.', 'wp-bootstrap-navwalker' ) );
} else {
  // file exists... require it.
  require_once get_template_directory . 'wp-bootstrap-navwalker.php';
}
```

## Usage

Update your `wp_nav_menu()` function in `header.php` to use the new walker by adding a "walker" item to the wp_nav_menu array.

```php
<?php
wp_nav_menu( array(
    'menu'              => 'primary',
    'theme_location'    => 'primary',
    'depth'             => 2,
    'container'         => 'div',
    'container_id'      => 'navbarNavDropdown',
    'container_class'   => 'collapse navbar-collapse',
    'container_id'      => 'bs-example-navbar-collapse-1',
    'menu_class'        => 'nav navbar-nav',
    'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
    'walker'            => new WP_Bootstrap_Navwalker())
);
?>
```


Typically the menu is wrapped with additional markup, here is an example of a menu that collapse for responsive navigation.

```php
<?php
$custom_logo = wp_get_attachment_image( get_theme_mod( 'custom_logo' ), 'full' );
?>

<nav class="navbar navbar-light bg-light navbar-expand-lg justify-content-between">

  <a class="navbar-brand" href="<?php echo home_url() ?>">
    <?php echo $custom_logo ?>
  </a>

  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <?php
  wp_nav_menu( array(
      'theme_location'  => 'top-menu',
      'depth'           => 2,
      'container'       => 'div',
      'container_id'    => 'navbarNavDropdown',
      'container_class' => 'collapse navbar-collapse',
      'menu_class'      => 'nav navbar-nav ml-auto',
      'fallback_cb'     => 'WP_Bootstrap_Navwalker::fallback',
      'walker'          => new WP_Bootstrap_Navwalker()
  ) );
  ?>
</nav>
```

### Dividers

Simply add a Link menu item with a **URL** of `#` and a **Link Text** or **Title Attribute** of `divider` (case-insensitive so ‘divider’ or ‘Divider’ will both work ) and the class will do the rest.

## Notes

If you need add custom texdomain to _Add a menu_ string, in line 316 you can add your textdomain identifier.

## Bug tracker

Have a bug? Please create an issue on GitHub at https://github.com/jprieton/wp-bootstrap4-navwalker/issues
