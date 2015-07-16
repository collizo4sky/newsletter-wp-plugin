jQuery( function ( $ ) {
    var $lastButtonClicked;

    $.fn.isRequired = function ( customValidator, invalidCallback ) {
      var value = this.val();
      var valid = false;

      this.attr( 'data-required', true );

      if ( customValidator ) {
        valid = customValidator( value );
      } else {
        valid = value !== '' ? true : false;
      }

      if ( ! valid ) {
        if ( invalidCallback ) {
          invalidCallback( this );
        }
      }

      return this;
    }

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
      var route, limit = -1, title = false, tags = false, categories = false, start_date = false, end_date = false, template = false;
      var hasError = false, errorMessage = '';
      var invalidCallback = function ( e ) {
        hasError = true;
        errorMessage = "Please fill out all required fields";

        e.css( 'border-color', 'red' );
      }

      e.preventDefault();

      if ( $lastButtonClicked ) {
        route = $lastButtonClicked.attr( 'name' );
      } else {
        route = 'generate';
      }

      // Title
      title = $('#title').isRequired( null, invalidCallback ).val();

      // Tags
      tags = get_pill_data( $('#tags-pills-id .pill') );

      // Categories
      categories = $('#categories-id').isRequired( null, invalidCallback ).val();

      // start date
      start_date = $('#start-date-datepicker').val();

      // end date
      end_date = $('#end-date-datepicker').val();

      // template
      template = $('#template-id').val();

      // template
      limit = $('#limit-id').isRequired( null, invalidCallback ).val();


      if ( hasError ) {
        $('.form-table').before( '<p style="color: red" class="error-message">' + errorMessage + '</p>' );
        return;
      } else {
        $('.error-message').remove();
      }

      if ( route === 'download' || route === 'generate' ) {
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

        url += '&limit=' + limit;

        if ( route === 'download' ) {
          url += '&download=true';
        }

        var win = window.open( url, '_blank' );
        win.focus();
      }
    });
  } );