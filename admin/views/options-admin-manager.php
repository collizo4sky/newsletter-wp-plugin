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

    <a type="submit" name="download" class="button button-primary">Download</a>
    <a type="submit" name="generate" class="button button-primary">Generate</a>
  </form>

  <script>
    window.url = '<?php echo plugins_url( '../../generators/email-templates.php', __FILE__ ) ?>';
  </script>
</div>