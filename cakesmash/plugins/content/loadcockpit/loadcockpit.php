<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Content.loadmodule
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Plug-in to enable loading modules into content (e.g. articles)
 * This uses the {loadmodule} syntax
 * @since  1.5
 */
class PlgContentLoadcockpit extends JPlugin {

	/**
	 * @param   string  $context  The context of the content being passed to the plugin.
	 * @param   object  &$article The article object.  Note $article->text is also available
	 * @param   mixed   &$params  The article params
	 * @param   integer $page     The 'page' number
	 * @return  mixed   true if there is an error. Void otherwise.
	 */
	public function onContentPrepare ($context, &$article, &$params, $page = 0) {
		// Don't run this plugin when the content is being indexed
		if ($context == 'com_finder.indexer') {
			return true;
		}

		// Simple performance check to determine whether bot should process further
		if (strpos($article->text, 'loadcockpit') === false) {
			return true;
		}

		// Expression to search for (positions)
		$regex = '/{loadcockpit\s(.*?)}/i';

		// Find all instances of plugin and put in $matches for loadposition
		// $matches[0] is full pattern match, $matches[1] is the position
		preg_match_all($regex, $article->text, $matches, PREG_SET_ORDER);

		// No matches, skip this
		if ($matches) {
			foreach ($matches as $match) {
				$matchData = explode(' ', $match[1]);
				$module = trim($matchData[0]);
				$method = trim($matchData[1]);
				$args = explode(',', $matchData[2]);

				$output = $this->call($module, $method, $args);

				$article->text = preg_replace("|$match[0]|", addcslashes($output, '\\$'), $article->text, 1);
			}
		}
		return true;
	}

	/**
	 * Loads and renders the cockpit module
	 *
	 */
	protected function call ($module, $method, $args) {
		/**
		 * make cockpit api available
		 * @var LimeExtra\App $cockpit
		 */

		if (!$module || !$method || !file_exists(JPATH_ROOT . '/cockpit/bootstrap.php')) {
			return false;
		}
		require_once(JPATH_ROOT . '/cockpit/bootstrap.php');

		ob_start();

		echo call_user_func_array([$cockpit->module($module), $method], $args);

		return ob_get_clean();
	}

}
