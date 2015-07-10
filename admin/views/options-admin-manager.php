<?php

/**
 * Newsletter Admin Manager
 */

require_once( plugin_dir_path( __FILE__ ) . '/../options-admin.php' );
?>

<div class="wrap" id="newsletter-display-data">
  <form method="post" action="options.php">
    <?php
      // This prints out all hidden settings fields
      settings_fields( \Newsletter_WP\Options_Admin::$options_group );
      do_settings_sections( \Newsletter_WP\Options_Admin::$section_name );
    ?>

    <a type="submit" name="save-options" class="button button-primary">Save Options</a>
    <a type="submit" name="download" class="button button-primary">Download</a>
    <a type="submit" name="generate" class="button button-primary">Generate</a>
  </form>

  <script>
  jQuery( function ( $ ) {
    var $lastButtonClicked;

    $( '#newsletter-display-data a.button' ).click( function markButtons( e ) {
      e.preventDefault();

      $lastButtonClicked = $( this );
      $( this ).parent( 'form' ).submit();
    } );

    $( '#newsletter-display-data form' ).submit(function routeFormSubmission( e ) {
      var route, tags = false, category = false, start_date = false, end_date = false, template = false;

      e.preventDefault();

      if ( $lastButtonClicked ) {
        route = $lastButtonClicked.attr( 'name' );
      } else {
        route = 'generate';
      }

      if ( route === 'save-options' ) {
        // TODO save the options!
        // Ajax post and update
      } else if ( route === 'download' || route === 'generate' ) {
        var url = "<?php echo plugins_url( '../../generators/email-templates.php', __FILE__ ) ?>";

        url += '?';
        url += 'type=' + route;

        if ( tags !== false ) {
          url += '&tags=' + tags;
        }

        if ( categories !== false ) {
          url += '&categories=' + categories;
        }

        if ( start_date !== false ) {
          url += '&start_date=' + start_date;
        }

        if ( end_date !== false ) {
          url += '&end_date=' + end_date;
        }

        if ( template !== false ) {
          url += '&template=' + template;
        }

        var win = window.open( url, '_blank' );
        win.focus();
      }
    });
  } );
  </script>
</div>