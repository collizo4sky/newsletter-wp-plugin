<?php
/**
 * Class SampleTest
 *
 * @package Newsletter_Wp_Plugin
 */

/**
 * Sample test case.
 */
class SampleTest extends WP_UnitTestCase {

	/**
	 * A single example test.
	 */
	function test_title() {
		// This code checks for the presense of title in the web page.

		$url = plugins_url().'/newsletter-wp-plugin/generators/email-templates.php?type=generate&title=NewsLetter%20September%202016&categories=board-letter&start_date=&end_date=&template=board-letter&limit=5';
		$page_content = file_get_contents($url);
		//$title = stristr($page, "NewsLetter September 2016");

		$url_parse = parse_url($url);
		parse_str($url_parse["query"], $params);

		$found = stripos($page_content, $params["title"]);
		$this->assertTrue($found);

	}

	function test_unsubscribe() {
		// This code checks for the presense of Unsubscribe link in the web page.

		$url = plugins_url().'/newsletter-wp-plugin/generators/email-templates.php?type=generate&title=NewsLetter%20September%202016&categories=board-letter&start_date=&end_date=&template=board-letter&limit=5';
		$page_content = file_get_contents($url);
		$found = stripos($page_content, "Unsubscribe");
		$this->assertTrue($found);

	}

	function test_template() {
		// This code checks for the template name in the web page.

		$url = plugins_url().'/newsletter-wp-plugin/generators/email-templates.php?type=generate&title=NewsLetter%20September%202016&categories=board-letter&start_date=&end_date=&template=board-letter&limit=5';
		$page_content = file_get_contents($url);
		//$title = stristr($page, "NewsLetter September 2016");

		$url_parse = parse_url($url);
		parse_str($url_parse["query"], $params);

		$found = stripos($page_content, $params["template"]);
		$this->assertTrue($found);

	}



}
?>
