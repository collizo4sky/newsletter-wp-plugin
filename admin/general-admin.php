<?php

namespace Newsletter_WP;

/**
 * General Administraion Class
 *
 * Creates and handles data and views for the admin panel
 * in Wordpress
 */
class General_Admin extends Base_Registrar {
  protected $panels = array();

  public function __construct( $version ) {
    parent::__construct( 'gios-newsletter-general-admin', $version );

    $this->load_dependencies();
    $this->define_hooks();
  }

  public function load_dependencies() {
    // None for now
  }

  /**
   * Add filters and action
   *
   * The majority of the work happend in the action 'wp'
   */
  public function define_hooks() {
    $this->add_action( 'admin_enqueue_scripts', $this, 'admin_enqueue_scripts' );
    $this->add_action( 'admin_menu', $this, 'admin_menu' );
    $this->add_action( 'admin_init', $this, 'admin_init' );
  }

  /**
   * Enqueue styles so that Wordpress caches them.
   */
  public function admin_enqueue_scripts() {
    // Nothing to enqueue for now
  }


  public function admin_menu() {
    $page_title = 'Newsletter Settings';
    $menu_title = 'Newsletter';
    $capability = 'publish_posts';
    $path = plugin_dir_url( __FILE__ );
    add_menu_page(
        $page_title,
        $menu_title,
        $capability,
        $this->plugin_slug,
        array( $this, 'render_admin_panel' ),
        $path . './images/icon.png'
    );
  }

  public function admin_init() {
    // Do nothing
  }

  /**
   * Files are queued by other admin php classes
   */
  public function render_admin_panel() {
    foreach ( $this->panels as $panel ) {
      include $panel;
    }
  }

  public function enqueue_panel( $path ) {
    $this->panels[] = $path;
  }
}
