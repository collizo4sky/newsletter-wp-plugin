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

      // ---------- START LOOP for # of SECTIONS -----------------
      var s = $('#sectionnumber').val();
      var section = '';
      for(i = 1; i <= parseInt(s); i++){

        section = '-section-'+i;

        // Title
        title = $('#title'+section).isRequired( null, invalidCallback ).val();

        // Tags
        tags = get_pill_data( $('#tags-pills-id .pill') );
        if(i == 1){

        }

        // Categories
        categories = $('#categories-id'+section).isRequired( null, invalidCallback ).val();

        // start date
        start_date = $('#start-date-datepicker'+section).val();

        // end date
        end_date = $('#end-date-datepicker'+section).val();

        // template
        // We only take this input once and keep it consistent throughout the email.
        if(i == 1){
          template = $('#template-id'+section).val();
        }

        // template
        limit = $('#limit-id'+section).isRequired( null, invalidCallback ).val();


        if ( hasError ) {
          $('.form-table').before( '<p style="color: red" class="error-message">' + errorMessage + '</p>' );
          return;
        } else {
          $('.error-message').remove();
        }

        if ( route === 'download' || route === 'generate' ) {

          if(i == 1){
            var url = window.url;

            url += '?';
            url += 'type=' + route;
          }


          if ( title !== false ) {
            url += '&title'+section+'=' + title;
          }

          // condition for tags changed. added && i == 1
          if ( tags !== false && i == 1) {
            url += '&tags=' + tags;
          }

          if ( categories !== false ) {
            url += '&categories'+section+'=' + categories;
          }

          if ( start_date !== false ) {
            url += '&start_date'+section+'=' + start_date;
          }

          if ( end_date !== false ) {
            url += '&end_date'+section+'=' + end_date;
          }

          // Template is read only once.
          if ( template !== false && i == 1) {
            url += '&template'+section+'=' + template;
          }

          url += '&limit'+section+'=' + limit;

          // condition for download changed. added && i == 1
          if ( route === 'download' && i == 1) {
            url += '&download=true';
          }
        }
      } // END OF FOR LOOP
      url += '&sections='+s;
      var win = window.open( url, '_blank' );
      win.focus();
    });
  } );
