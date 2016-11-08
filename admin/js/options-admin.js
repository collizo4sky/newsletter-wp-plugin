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
      var section_number_element = $('#sectionnumber').val();
      var section = '';
      for(section_number = 1; section_number <= parseInt(section_number_element); section_number++){

        section = '-section-'+section_number;

        // Title
        title = $('#title'+section).isRequired( null, invalidCallback ).val();

        // Tags
        if(section_number == 1){
          tags = get_pill_data( $('#tags-pills-id .pill') );
        }
        else{
          tags = get_pill_data( $('#tags-pills-id-'+section_number+' .pill') );
        }

        // Categories
        categories = $('#categories-id'+section).isRequired( null, invalidCallback ).val();

        // start date
        start_date = $('#start-date-datepicker'+section).val();

        // end date
        end_date = $('#end-date-datepicker'+section).val();

        // template
        // We only take this input once and keep it consistent throughout the email.
        if(section_number == 1){
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

          if(section_number == 1){
            var url = window.url;

            url += '?';
            url += 'type=' + route;
          }


          if ( title !== false ) {
            url += '&title'+section+'=' + title;
          }

          // condition for tags changed. added && i == 1
          if ( tags !== false) {
            url += '&tags'+section+'=' + tags;
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
          if ( template !== false && section_number == 1) {
            url += '&template'+section+'=' + template;
          }

          url += '&limit'+section+'=' + limit;

          // condition for download changed. added && i == 1
          if ( route === 'download' && section_number == 1) {
            url += '&download=true';
          }
        }
      } // END OF FOR LOOP
      url += '&sections='+section_number_element;
      var win = window.open( url, '_blank' );
      win.focus();
    });



  } );

  function addSection(){
    var numberOfSections = parseInt(document.getElementById("sectionnumber").value) + 1;
    var sectionId = '-section-'+numberOfSections;
    document.getElementById('sectionnumber').value = numberOfSections;
    var element = document.createElement("div");
    element.setAttribute("id", "section-"+numberOfSections);

    var html = '<hr>';
    html = html + '<table class="form-table">';
    html = html +   '<tbody>';
    html = html +     '<tr>';
    html = html +       '<th scope="row">Title *</th>';
    html = html +       '<td>';
    html = html +         '<input type="text" placeholder="Email Title" class="regular-text" name="title'+sectionId+'" id="title'+sectionId+'" />';
    html = html +         '</br><b>Section # '+numberOfSections+'</b>'
    html = html +       '</td>';
    html = html +     '</tr>';
    html = html +     '<tr>';
    html = html +       '<tr><th scope="row">Category *</th>';
    html = html +       '<td class="category-'+numberOfSections+'"></td>';
    html = html +     '</tr>';
    html = html +     '<tr>';
    html = html +       '<th scope="row">Tags</th>';
    html = html +       '<td><div id="tags-div">';
    html = html +          '<em>Start typing the tag name, choose the tag from the auto complete and then click the button to actually add it to your newsletter.</em><br>';
    html = html +          '<input class="tags" type="text" name="tags" id="tags-'+numberOfSections+'" placeholder="Add Tags"/>';
    if(!getPillsString()){
      html = html +        '<button class="tags-button button" id="tags-button-id-'+numberOfSections+'">Use this Tag</button>';
      html = html +        '<div id="tags-pills-id-'+numberOfSections+'" class="pills"></div>';
    }
    html = html +       '</div></td>';
    html = html +     '</tr>';
    html = html +     '<tr>';
    html = html +       '<th scope="row">Start Date</th>';
    html = html +       '<td>';
    html = html +         '<input id="start-date-datepicker-section-'+numberOfSections+'" type="text" onfocus="showDate(this)" />';
    html = html +       '</td>';
    html = html +     '</tr>';
    html = html +     '<tr>';
    html = html +       '<th scope="row">End Date</th>';
    html = html +       '<td>';
    html = html +         '<input class="datepicker" id="end-date-datepicker-section-'+numberOfSections+'" type="text" onfocus="showDate(this)"/>';
    html = html +       '</td>';
    html = html +     '</tr>';
    html = html +     '<tr>';
    html = html +       '<th scope="row">Max Number of Posts</th>';
    html = html +       '<td>';
    html = html +         '<input min="-1" max="1000" name="limit" id="limit-id-section-'+numberOfSections+'" value="-1" type="number">';
    html = html +       '</td>';
    html = html +     '</tr>';
    html = html +   '</tbody>';
    html = html +  '</table>';

    element.innerHTML = html;
    var div = document.getElementById('section-container');
    div.appendChild(element);
    jQuery("#categories-id-section-1").clone().prop({ id: "categories-id-section-"+numberOfSections}).appendTo(".category-"+numberOfSections);

    jQuery(function ($) {
        var substringMatcher = function (strs) {
          return function findMatches(q, cb) {
            var matches, substringRegex;

            // an array that will be populated with substring matches
            matches = [];

            // regex used to determine if a string contains the substring `q`
            substrRegex = new RegExp(q, 'i');

            // iterate through the pool of strings and for any string that
            // contains the substring `q`, add it to the `matches` array
            $.each(strs, function(i, str) {
              if (substrRegex.test(str.name)) {
                matches.push(str);
              }
            });

            cb(matches);
          };
        }

        //var data = [{"term_id" : "5","term_slug" : "2016-accomplishments","name" : "2016-accomplishments"},{"term_id" : "8","term_slug" : "bl-2016-06","name" : "bl-2016-06"},{"term_id" : "16","term_slug" : "bl-2016-07","name" : "bl-2016-07"},{"term_id" : "12","term_slug" : "global","name" : "global"},{"term_id" : "15","term_slug" : "research","name" : "Research"},{"term_id" : "14","term_slug" : "solutions","name" : "solutions"}];
        var data = getTags();
        $('.tags').typeahead({
          hint: true,
          highlight: true,
          minLength: 1
        }, {
          name: 'tags',
          source : substringMatcher( data ),
          display: 'name'
        });

        if ( ! getPillsString() ) {
          $('#tags-'+numberOfSections).keypress( function(e) {
            if ( e.keyCode === 13 /* enter key */ ) {
              e.preventDefault();
              $('.tags-button').click();
              return;
            }
          });

          $('.tags-button').click(function(e) {
            e.preventDefault();

            var term = $('#tags-'+numberOfSections).val();
            var term_id = -1;
            var term_slug = '';
            var found = false;

            if ( term === '' ) {
              return;
            }

            for (var i = 0; i < data.length; i++) {
              if ( term === data[i]['name'] ) {
                term_id = data[i]['term_id'];
                term_slug = data[i]['term_slug'];
                found = true;
              }
            }

            if ( ! found ) {
              return;
            }

            var close = '<span class="close">X</span>';

            $('#tags-pills-id-'+numberOfSections).append(
              '<div class="pill" data-id="' + term_id + '" data-slug="' + term_slug + '">' + term + close + '</div>'
            );

            // Clear typeahead
            $('#tags-'+numberOfSections).val('');
          });

          $('#tags-pills-id-'+numberOfSections).on('click', '.pill .close', function ( e ) {
            $(this).parent().remove();
          });
        } // End if
        $(".tags").removeClass("tags");
    });



  }

  function deleteSection(){
    var numberOfSections = parseInt(document.getElementById("sectionnumber").value);
    if(numberOfSections <= 1){
      return;
    }
    // alert("section-" + numberOfSections);
    var id = "section-" + numberOfSections;
    var el = document.getElementById(id);
    el.parentNode.removeChild(el);
    document.getElementById("sectionnumber").value = numberOfSections - 1;
  }

  function showDate(id){
    new Pikaday({ field: id });
  }
