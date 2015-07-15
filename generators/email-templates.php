<?php
/**
 * Load in WordPress functionality so that we can get posts without reimplementing
 * the post db functionality.
 */
define('WP_USE_THEMES', false);

require '../../../../wp-blog-header.php';
require_once '../vendor/autoload.php';

use Handlebars\Handlebars;

function get_parameter( $name, $default ) {
  if ( array_key_exists( $name, $_GET ) ) {
    return $_GET[ $name ];
  } else {
    return $default;
  }
}

function generate_data( $title, $posts ) {
  $data = array(
      'title' => $title,
      'posts' => $posts
  );

  return $data;
}

// ====
// Main
// ====
$post_options = array(
  'posts_per_page' => -1, // Show all posts
  'post_status'    => 'publish',
  'post_type'      => 'post',
);

$title      = get_parameter( 'title', '' );
$tags       = get_parameter( 'tags', false );
$categories = get_parameter( 'categories', false );
$start_date = get_parameter( 'start_date', false );
$end_date   = get_parameter( 'end_date', false );
$template   = get_parameter( 'template', 'board-letter' ); // TODO this should have a real default

if ( false !== $tags ) {
  $post_options['tag'] = $tags;
}

if ( false !== $categories ) {
  $post_options['category_name'] = $categories;
}

if ( false !== $start_date || false !== $end_date ) {
  $date_query = array();

  if ( false !== $start_date ) {
    $date_query['after'] = $start_date;
  }

  if ( false !== $end_date ) {
    $date_query['before'] = $start_date;
  }

  $post_options['date_query'] = $date_query;
}

$posts = get_posts( $post_options );
$data  = generate_data( $title, $posts );

// Step 1. Generate CSS from SCSS
$scss = new scssc;
$scss->setImportPaths( '../email-templates/css/scss/' );
$css  = $scss->compile('@import "main.scss"');

// Step 2. Compile Handlebars template with Data
$template_path =  __DIR__ . '/../email-templates/emails/';
$engine        = new Handlebars(
    array(
      'loader' => new \Handlebars\Loader\FilesystemLoader( $template_path ),
      'partials_loader' => new \Handlebars\Loader\FilesystemLoader( $template_path, array( 'prefix' => '_' ) ),
    )
);
$compiled      = $engine->render( $template, $data );

// Step 3. Inline CSS into compiled template
$emogrifier = new \Pelago\Emogrifier( $compiled, $css );
$html       = $emogrifier->emogrify();

print $html;