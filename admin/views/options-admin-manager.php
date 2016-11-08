<?php

/**
 * Newsletter Admin Manager
 */

require_once( plugin_dir_path( __FILE__ ) . '/../options-admin.php' );
?>

<div class="wrap" id="newsletter-display-data">
  <form method="post" action="options.php">
    <div id="section-container" name="section-container">
      <div id="section" name="section">
        <?php
        // This prints out all hidden settings fields
        settings_fields( \Newsletter_WP\Options_Admin::$options_group );
        do_settings_sections( \Newsletter_WP\Options_Admin::$section_name );
        ?>
      </div>
    </div>
    <input type="hidden" name="sectionnumber" id="sectionnumber" value="1">
    <a type="submit" name="download" class="button button-primary" style="float: right; margin: 3px;">Download</a>  <a type="submit" name="generate" class="button button-primary" style="float: right; margin: 3px;">Generate</a>

  </form>

  <a type="button" name="add_section" class="button button-primary" onclick="addSection()">Add Section</a>
  <a type="button" name="delete_section" class="button button-primary" onclick="deleteSection()">Delete Section</a>

  <script>
    window.url = '<?php echo plugins_url( '../../generators/email-templates.php', __FILE__ ) ?>';
  </script>
</div>
