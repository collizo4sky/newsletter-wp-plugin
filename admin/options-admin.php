<?php

namespace Newsletter_WP;

class Options_Admin extends Base_Registrar {
  public static $options_name                  = 'newsletter_options';
  public static $options_group                 = 'newsletter_options_group';
  public static $section_name                  = 'gios_newsletter_display_admin';
  public static $section_id                    = 'gios_newsletter_display_admin_id';
  public static $section_newsletter_title      = 'gios_newsletter_title';
  public static $section_newsletter_tags       = 'gios_newsletter_tags';
  public static $section_newsletter_category   = 'gios_newsletter_category';
  public static $section_newsletter_date_start = 'gios_newsletter_date_start';
  public static $section_newsletter_date_end   = 'gios_newsletter_date_end';
  public static $section_newsletter_template   = 'gios_newsletter_template';


  /**
   * All Admin subordinates must report to General Admin
   */
  public function __construct( &$general_admin, $version ) {
    parent::__construct( 'newsletter-options-admin', $version );

    add_option(
        self::$options_name,
        array(
          self::$section_newsletter_title => '',
          self::$section_newsletter_tags => '',
          self::$section_newsletter_category => '',
          self::$section_newsletter_date_start => '',
          self::$section_newsletter_date_end => '',
          self::$section_newsletter_template => '',
        )
    );

    $this->load_dependencies();
    $this->define_hooks();

    $general_admin->enqueue_panel(
        plugin_dir_path( __FILE__ ) . 'views/options-admin-manager.php'
    );
  }

  /**
   * @override
   */
  public function load_dependencies() {
    // Nothing for now
  }

  /**
   * @override
   */
  public function define_hooks() {
    $this->add_action( 'admin_enqueue_scripts', $this, 'admin_enqueue_scripts' );
    $this->add_action( 'admin_init', $this, 'admin_init' );
  }

  /**
   * Enqueue the stylesheets for the admin interface
   */
  public function admin_enqueue_scripts() {
    // Load in Bloodhound and typeahead
    wp_enqueue_script( 'twitter-typeahead', plugins_url( '/js/typeahead.bundle.min.js' , __FILE__ ), array(), '0.11.1', true );
    wp_enqueue_script( 'options-admin', plugins_url( '/js/options-admin.js' , __FILE__ ), array('twitter-typeahead'), '0.0.1', true );
    wp_enqueue_style( 'twitter-typeahead-scaffolding', plugins_url( '/css/scaffolding.css' , __FILE__ ), array(), '0.11.2' );
  }

  /**
   * Add our settings for the Newsletter to the Newsletter Page
   */
  public function admin_init() {
    // Register Settings
    register_setting(
        self::$options_group,
        self::$options_name,
        array( $this, 'form_submit' )
    );

    add_settings_section(
        self::$section_id,
        'Newsletter Settings',
        array(
          $this,
          'print_section_info',
        ),
        self::$section_name
    );

    // =====
    // Title
    // =====
    add_settings_field(
        self::$section_newsletter_title,
        'Title',
        array(
          $this,
          'newsletter_title_callback',
        ),
        self::$section_name,
        self::$section_id
    );

    // ====
    // Tags
    // ====
    add_settings_field(
        self::$section_newsletter_tags,
        'Tags',
        array(
          $this,
          'newsletter_tags_callback',
        ),
        self::$section_name,
        self::$section_id
    );

    // ========
    // Category
    // ========
    add_settings_field(
        self::$section_newsletter_category,
        'Category',
        array(
          $this,
          'newsletter_category_callback',
        ),
        self::$section_name,
        self::$section_id
    );

    // ==========
    // Start Date
    // ==========
    add_settings_field(
        self::$section_newsletter_date_start,
        'Start Date',
        array(
          $this,
          'newsletter_start_date_callback',
        ),
        self::$section_name,
        self::$section_id
    );

    // ========
    // End Date
    // ========
    add_settings_field(
        self::$section_newsletter_date_end,
        'End Date',
        array(
          $this,
          'newsletter_end_date_callback',
        ),
        self::$section_name,
        self::$section_id
    );

    // ========
    // Template
    // ========
    add_settings_field(
        self::$section_newsletter_template,
        'Template',
        array(
          $this,
          'newsletter_template_callback',
        ),
        self::$section_name,
        self::$section_id
    );
  }

  /**
   * Print the Section text
   */
  public function print_section_info() {
    print 'Enter your settings below:';
  }

  /**
   * Print the title section
   */
  public function newsletter_title_callback() {
    print '<input type="text" placeholder="Email Title" class="regular-text" name="title" id="title" />';
  }

  /**
   * Print the tag section
   */
  public function newsletter_tags_callback() {
    $tags = get_tags();
    $filtered_tags = '[';

    if ( is_array( $tags ) && count( $tags ) > 0 ) {
      for ( $i = 0; $i < count( $tags ); $i++ ) {
        if ( 0 != $i ) {
          $filtered_tags .= ',';
        }

        $filtered_tags .= '{';
        $filtered_tags .= '"term_id" : "' . $tags[ $i ]->term_id . '",';
        $filtered_tags .= '"name" : "' . $tags[ $i ]->name . '"';       
        $filtered_tags .= '}';
      }
    }

    $filtered_tags .= ']';

    $this->print_typeahead( $filtered_tags, 'tags' );
  }

  /**
   * Print the category section
   */
  public function newsletter_category_callback() {
    $categories = get_categories();
    $filtered_categories = '[';

    if ( is_array( $categories ) && count( $categories ) > 0 ) {
      $first = true;
      foreach ( $categories as $category ) {
        if ( ! $first ) {
          $filtered_categories .= ',';
        }
        $first = false;
        
        $filtered_categories .= '{';
        $filtered_categories .= '"term_id" : "' . $category->term_id . '",';
        $filtered_categories .= '"name" : "' . $category->name . '"';       
        $filtered_categories .= '}';
      }
    }

    $filtered_categories .= ']';

    $this->print_typeahead( $filtered_categories, 'categories' );
  }
  
  public function newsletter_start_date_callback() {
    // TODO datepicker
  }

  public function newsletter_end_date_callback() {
    // TODO datepicker
  }

  public function newsletter_template_callback() {
    // TODO 
  }

  public function form_submit( $input ) {
    // TODO filter and make sure the newsletter categories are valid
    return $input;
  }

  protected function print_typeahead( $data, $name ) {
    echo '<input class="regular-text" type="text" name="' . $name . '" id="' . $name . '-id" placeholder="Add ' . $name . '"/>';
    echo '<button class="button" id="' . $name . '-button-id">+</button>';
    echo '<div id="' . $name . '-pills-id" class="pills"></div>';

    $js = <<<JAVASCRIPT
      +function () {
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

          var data = $data;

          $('#{$name}-id').typeahead({
            hint: true,
            highlight: true,
            minLength: 1
          }, {
            name: '$name',
            source : substringMatcher( data ),
            display: 'name'
          }).keypress( function(e) {
            if ( e.keyCode === 13 /* enter key */ ) {
              e.preventDefault();
              $('#{$name}-button-id').click();
              return;
            }
          });

          $('#{$name}-button-id').click(function(e) {
            e.preventDefault();

            var term = $('#{$name}-id').val();
            var term_id = -1;
            var found = false;

            if ( term === '' ) {
              return;
            }

            for (var i = 0; i < data.length; i++) {
              if ( term === data[i]['name'] ) {
                term_id = data[i]['term_id'];
                found = true;
              }
            }

            if ( ! found ) {
              return;
            }

            var close = '<span class="close">X</span>';

            $('#{$name}-pills-id').append(
              '<div class="pill" data-id="' + term_id + '" data-name="' + term + '">' + term + close + '</div>'
            );

            // Clear typeahead
            $('#{$name}-id').val('');
          });

          $('#{$name}-pills-id').on('click', '.pill .close', function ( e ) {
            $(this).parent().remove();
          });
        });
      }();
JAVASCRIPT;
    echo '<script>' . $js . '</script>';
  }
}