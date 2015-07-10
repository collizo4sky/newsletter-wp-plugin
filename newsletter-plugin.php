<?php
/*
Plugin Name: WordPress Newsletter Plugin
Plugin URI: http://gios.asu.edu
Description: A Newsletter plugin for the GIOS faculty
Version: 1.0
Author: The Global Institute of Sustainability
License: Copyright 2015

GitHub Plugin URI: https://github.com/gios-asu/wordpress-newsletter-plugin
GitHub Branch: master
*/

// Only allow WordPress access
if ( ! function_exists( 'add_filter' ) ) {
  header( 'Status: 403 Forbidden' );
  header( 'HTTP/1.1 403 Forbidden' );
  exit();
}

define( 'GIOS_NEWSLETTER_WP_VERSION', '1.0.0' );

function require_once_directory( $directory ) {
  $files = glob( $directory  . '/*.php' );

  foreach ( $files as $file ) {
    require_once( $file );
  }
}

function setup_wordpress_newsletter_plugin() {
  // =================
  // Load Dependencies
  // =================
  $version = GIOS_NEWSLETTER_WP_VERSION;

  require_once plugin_dir_path( __FILE__ ) . 'includes/base-registrar.php';
  require_once_directory( plugin_dir_path( __FILE__ ) . 'admin' );

  // ===================
  // Plugin Registration
  // ===================
  // =====
  // Admin
  // =====
  $general_admin          = new \Newsletter_WP\General_Admin( $version );
  $options_admin          = new \Newsletter_WP\Options_Admin( $general_admin, $version );

  $general_admin->run();
  $options_admin->run();
}

setup_wordpress_newsletter_plugin();