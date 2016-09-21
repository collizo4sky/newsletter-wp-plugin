<?php
/**
 * Class SampleTest
 *
 * @package Newsletter_Wp_Plugin
 */

/**
 * Sample test case.
 */

class SampleTest extends \WP_UnitTestCase {

	/**
	 * A single example test.
	 */

	//  function test_email_template() {
   //
	// 	 //call email-templates.php and pass parameters to generate the output in buffer
   //
	// 	 ob_start();
   //
	// 	 // SET the parameters that are required by the email-templates.php file to generate the template.
	// 	 $_GET['download'] = false;
	// 	 $_GET['title'] = 'NewsLetter September 2016';
	// 	 $_GET['tags'] = false;
	// 	 $_GET['categories'] = 'board-letter';
	// 	 $_GET['start_date'] = false;
	// 	 $_GET['end_date'] = false;
	// 	 $_GET['template'] = 'board-letter';
	// 	 $_GET['limit'] = '5';
   //
	// 	 // Call email-templates.php
	// 	 include ((dirname(__FILE__).'../generators/email-templates.php');
   //
	// 	 // Capture the output from the buffer.
	// 	 $output = ob_get_contents();
   //
	// 	 // Sample test to check the output is captured or not.
	// 	 if($output){
	// 		 $this->assertTrue(True);
	// 	 }
   //
	// 	 ob_end_clean();
	//  }

	function test_title() {
		// This code checks for the presense of title in the web page.

		// $url = plugins_url();
    $url = 'http://localhost/wp-content/plugins';
    $url .= '/newsletter-wp-plugin/generators/email-templates.php?type=generate&title=NewsLetter%20September%202016&categories=board-letter&start_date=&end_date=&template=board-letter&limit=5';
		$page_content = file_get_contents($url);
		//$title = stristr($page, "NewsLetter September 2016");

		$url_parse = parse_url($url);
		parse_str($url_parse["query"], $params);

		$found = stripos($page_content, $params["title"]);
		$this->assertTrue($found);

	}
  //
	// function test_unsubscribe() {
	// 	// This code checks for the presense of Unsubscribe link in the web page.
  //
	// 	$url = plugins_url().'/newsletter-wp-plugin/generators/email-templates.php?type=generate&title=NewsLetter%20September%202016&categories=board-letter&start_date=&end_date=&template=board-letter&limit=5';
	// 	$page_content = file_get_contents($url);
	// 	$found = stripos($page_content, "Unsubscribe");
	// 	$this->assertTrue($found);
  //
	// }
  //
	// function test_template() {
	// 	// This code checks for the template name in the web page.
  //
	// 	$url = plugins_url().'/newsletter-wp-plugin/generators/email-templates.php?type=generate&title=NewsLetter%20September%202016&categories=board-letter&start_date=&end_date=&template=board-letter&limit=5';
	// 	$page_content = file_get_contents($url);
	// 	//$title = stristr($page, "NewsLetter September 2016");
  //
	// 	$url_parse = parse_url($url);
	// 	parse_str($url_parse["query"], $params);
  //
	// 	$found = stripos($page_content, $params["template"]);
	// 	$this->assertTrue($found);
  //
	// }



}
?>
