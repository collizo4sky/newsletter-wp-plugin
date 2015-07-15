jQuery( function ( $ ) {
    var $lastButtonClicked;

    /**
     * Map and reduce the pills for their delicious data
     */
    var get_pill_data = function( $selector ) {
      if ( $selector.length === 0 ) {
        return false;
      }

      var result = $selector.map(function (i, e) {
        return encodeURIComponent( $(this).attr('data-slug') );
      }).toArray();

      if ( result.length === 0 ) {
        return false;
      }

      return result.reduce(function(a, b) {
        return a + '+' + b;
      });
    }

    /**
     * Route button clicks to submit the form
     */
    $( '#newsletter-display-data a.button' ).click( function markButtons( e ) {
      e.preventDefault();

      $lastButtonClicked = $( this );
      $( this ).parent( 'form' ).submit();
    } );

    /**
     * Router for form submission
     */
    $( '#newsletter-display-data form' ).submit(function routeFormSubmission( e ) {
      var route, title = false, tags = false, categories = false, start_date = false, end_date = false, template = false;

      e.preventDefault();

      if ( $lastButtonClicked ) {
        route = $lastButtonClicked.attr( 'name' );
      } else {
        route = 'generate';
      }

      // Title
      title = $('#title').val();

      // Tags
      tags = get_pill_data( $('#tags-pills-id .pill') );

      // Categories
      categories = get_pill_data( $('#categories-pills-id .pill') );

      // TODO start date

      // TODO end date

      // TODO template

      if ( route === 'save-options' ) {
        // TODO save the options!
        // Ajax post and update
      } else if ( route === 'download' || route === 'generate' ) {
        var url = window.url;

        url += '?';
        url += 'type=' + route;

        if ( title !== false ) {
          url += '&title=' + title;
        }

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