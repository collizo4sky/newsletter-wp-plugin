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

function strip_only( $html, $html_tags, $strip_content = false ) {
  $content = ''; 
  if( ! is_array( $html_tags ) ) { 
    $html_tags = ( strpos( $html, '>' ) !== false ? explode( '>', str_replace( '<', '', $html_tags ) ) : array( $html_tags ) ); 
    if ( end( $html_tags ) == '' ) {
      array_pop( $html_tags ); 
    }
  }

  foreach ( $html_tags as $tag ) { 
    if ( $strip_content ) {
      $content = '(.+</'. $tag .'[^>]*>|)';
    }
    $html = preg_replace( '#</?'.$tag.'[^>]*>' . $content . '#is', '', $html ); 
  }

  return $html; 
}

function get_attached_image( $post ) {
  $image_attributes   = wp_get_attachment_image_src(
      get_post_thumbnail_id( $post->ID )
  );

  return $image_attributes ? $image_attributes[0] : null;
}

function get_content_image( $post ) {
  $content    = $post->post_content;
  $list_item  = null;
  // Take the image tag src attribute from the content and store it in pics
  // variable (?<!_)negative lookbehind  [\'"] match either ' or
  // " (abc)capture group \1 backreference to group #1
  preg_match_all( '/(?<!_)src=([\'"])?(.*?)\\1/', $content, $pics );
  if ( ! empty( $pics[2] ) ) {
    if ( parse_url( $pics[2][0], PHP_URL_SCHEME ) == '' && substr( $pics[2][0], 0, strlen('//')) !== '//' ) {
      $pics[2][0] = home_url( $pics[2][0] );
    }
    return $pics[2][0];
  }

  return null;
}

function get_page_feature_image( $post ) {
  $page_feature_image = get_post_meta(
      $post->ID,
      'page_feature_image',
      true
  );

  if ( ! empty( $page_feature_image ) ) {
    if ( parse_url( $page_feature_image, PHP_URL_SCHEME ) == '' && substr( $page_feature_image, 0, strlen('//')) !== '//' ) {
      $page_feature_image = home_url( $page_feature_image );
    }
    
    return $page_feature_image;
  }

  return null;
}

function get_images_from_post( $post ) {
  $images = array();

  $temp = get_attached_image( $post );

  if ( $temp ) {
    $images[] = $temp;
  }

  $temp = get_content_image( $post );

  if ( $temp ) {
    $images[] = $temp;
  }

  $temp = get_page_feature_image( $post );

  if ( $temp ) {
    $images[] = $temp;
  }

  return $images;
}

function generate_data( $title, $posts ) {
  $data = array(
      'title' => $title,
      'posts' => $posts
  );

  foreach ( $data['posts'] as &$post ) {
    $meta = get_post_meta( $post->ID );
    $post->meta = $meta;

    if ( empty( $post->post_excerpt ) ) {
      $post_more = explode( '<!--more-->', $post->post_content )[0];
      $post_more = strip_only( nl2br( $post_more ), 'img', true );
      $post->post_excerpt = $post_more;
    }

    // Run shortcodes on the excerpt
    // Most likely do not need to ob_start
    // But useful in case WordPress tries to print something out
    ob_start();
    setup_postdata( $post );

    $excerpt = $post->post_excerpt;

    if ( empty( $excerpt ) ) {
      if ( empty( $excerpt = get_the_excerpt() ) ) {
        $post_more = explode( '<!--more-->', $post->post_content )[0];
        $post_more = strip_only( nl2br( $post_more ), 'img', true );
        $excerpt   = $post_more;
      }
    }

    $post->post_excerpt = do_shortcode( $excerpt );
    ob_end_clean();


    // Get image
    $images = get_images_from_post( $post );

    if ( count( $images ) > 0 ) {
      $post->image = $images[0];
    } else {
      $post->image = '/images/noimage.jpg'; // TODO better noimage
    }

    // Get Source URL
    if ( array_key_exists( 'RedirectURL', $meta ) && $meta['RedirectURL'][0] ) {
      $post->source = $meta['RedirectURL'][0];
    } else {
      $post->source = 'https://sustainability.asu.edu/news/archive/' . $post->post_name . '/';
    }
  }
  wp_reset_postdata();

  return $data;
}

// ====
// Main
// ====
$post_options = array(
  'posts_per_page' => -1, // Show all posts
  'post_status'    => 'publish',
  'post_type'      => 'post',
  'orderby'        => 'menu_order',
  'order'          => 'ASC',
);

$download   = get_parameter( 'download', false );
$title      = get_parameter( 'title', '' );
$tags       = get_parameter( 'tags', false );
$categories = get_parameter( 'categories', false );
$start_date = get_parameter( 'start_date', false );
$end_date   = get_parameter( 'end_date', false );
$template   = get_parameter( 'template', 'board-letter' );
$limit      = get_parameter( 'limit', '-1' );

// Validate input
if ( empty( $title ) ) {
  print 'Title is required.';
  die();
}

if ( empty( $categories ) ) {
  print 'A category is required.';
  die();
}

if ( empty( $template ) ) {
  print 'A template is required.';
  die();
}


if ( false !== $tags ) {
  $post_options['tag'] = $tags;
}

if ( false !== $categories && $categories !== 'no-category' ) {
  $post_options['category_name'] = $categories;
}

if ( false !== $start_date || false !== $end_date ) {
  $date_query = array();

  if ( false !== $start_date ) {
    $date_query['after'] = $start_date;
  }

  if ( false !== $end_date ) {
    $date_query['before'] = $end_date;
  }

  $post_options['date_query'] = $date_query;
}

if ( $limit !== '-1' ) {
  $post_options['posts_per_page'] = intval( $limit );
}

$posts = get_posts( $post_options );
$data  = generate_data( $title, $posts );

// Step 1. Generate CSS from SCSS
$scss = new scssc;
$scss->setImportPaths( '../email-templates/css/scss/' );
$css  = $scss->compile('@import "main.scss"');

// Step 2. Compile Handlebars template with Data
$template_path =  __DIR__ . '/../email-templates/emails/';

try {
  $engine = new Handlebars(
      array(
        'loader' => new \Handlebars\Loader\FilesystemLoader( $template_path ),
        'partials_loader' => new \Handlebars\Loader\FilesystemLoader( $template_path, array( 'prefix' => '_' ) ),
      )
  );

  $compiled = $engine->render( $template, $data );
} catch (Exception $e) {
  echo 'Could not find template.';
  die();
}

// Step 3. Inline CSS into compiled template
$emogrifier = new \Pelago\Emogrifier( $compiled, $css );
$html       = $emogrifier->emogrify();

if ( $download === 'true' ) {
  // YOU NEED TO TELL THE BROWSER EVERYTHING IS OK
  header('HTTP/1.0 200 OK');
  header('Content-Description: File Transfer');
  header('Content-Type: application/octet-stream');
  header("Content-Disposition: attachment; filename=\"" . urlencode($title) . '.html\"');
  header('Expires: 0');
  header('Cache-Control: must-revalidate');
  header('Pragma: public');
  header("Content-Length: " . strlen( $html ) );
}

echo $html;
