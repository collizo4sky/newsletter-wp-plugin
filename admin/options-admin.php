<?php

namespace Newsletter_WP;

class Options_Admin extends Base_Registrar {
  public static $options_name                  = 'newsletter_options';
  public static $options_group                 = 'newsletter_options_group';
  public static $section_name                  = 'gios_newsletter_display_admin';
  public static $section_id                    = 'gios_newsletter_display_admin_id';
  public static $section_newsletter_tags       = 'gios_newsletter_tags';
  public static $section_newsletter_category   = 'gios_newsletter_category';
  public static $section_newsletter_date_start = 'gios_newsletter_date_start';
  public static $section_newsletter_date_end   = 'gios_newsletter_date_end';


  /**
   * All Admin subordinates must report to General Admin
   */
  public function __construct( &$general_admin, $version ) {
    parent::__construct( 'newsletter-options-admin', $version );

    add_option(
        self::$options_name,
        array(
          self::$section_newsletter_tags => '',
          self::$section_newsletter_category => '',
          self::$section_newsletter_date_start => '',
          self::$section_newsletter_date_end => '',
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
    // Nothing for now
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
  }

  /**
   * Print the Section text
   */
  public function print_section_info() {
    print 'Enter your settings below:';
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
          $filtered .= ',';
        }

        $filtered .= '{';
        // TODO
        var_dump( $tag );        
        $filtered .= '}';
      }
    }

    $filtered_tags .= ']';

    // TODO
  }

  /**
   * Print the category section
   */
  public function newsletter_category_callback() {
    // TODO
  }
  
  public function newsletter_start_date_callback() {
    // TODO
  }

  public function newsletter_end_date_callback() {
    // TODO
  }

  public function form_submit( $input ) {
    // TODO filter and make sure the newsletter categories are valid
    return $input;
  }
}