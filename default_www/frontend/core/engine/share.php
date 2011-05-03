<?php

/**
 * This class will handle the share options
 *
 * @package		frontend
 * @subpackage	core
 *
 * @author		Lowie Benoot <lowie@netlash.com>
 * @since		2.1
 */
class FrontendShare
{
	/**
	 * Add the share options to the given items
	 *
	 * @return	void
	 * @param	string $string		The module.
	 * @param	array $item			The item to add the share options to.
	 * @param	array $option
	 */
	public static function getShareOptionsForItem($module, &$item, $options = null)
	{
		// get the share settings for this module
		if($options == null) $options = self::getShareOptionsForModule($module);

		// create the url to share
		$url = SITE_URL . $item['full_url'];

		if(FrontendModel::getModuleSettings('share', 'shorten_urls'))
		{
			// get the url shortener
			$shortener = (int) FrontendModel::getModuleSetting('share', 'shortener');

			// if shortener  == 1, tinyURL should be user
			if($shortener == 1)
			{
				// require tinyURL class
				require_once PATH_LIBRARY . '/external/tinyurl.php';

				// create a TinyURL iinstance
				$tiny = new TinyUrl();

				// get short url
				$url = $tiny->create($url);
			}
		}

		// create a new template
		$tpl = new FrontendTemplate();

		// loop options for this item
		if(isset($options[$item['id']]))
		{
			foreach($options[$item['id']] as $option)
			{
				// @TODO: switch instead of case
				// the setting is for facebook?
				if($option['service_name'] == 'facebook')
				{
					// assign facebook option
					$tpl->assign('facebook', true);

					// assign url in the like button
					$tpl->assign('facebook_share_url', $url);
				}

				// the setting is for twitter?
				elseif($option['service_name'] == 'twitter')
				{
					// assign twitter option
					$tpl->assign('twitter', true);

					// assign url in the like button
					$tpl->assign('twitter_share_url', str_replace('#', '%23', sprintf($option['message'], $url)));
				}

				// the setting is for delicious?
				elseif($option['service_name'] == 'delicious')
				{
					// assign facebook option
					$tpl->assign('delicious', true);

					// assign url in the like button
					$tpl->assign('delicious_share_url', $url);
				}
			}
		}

		// get share template
		$shareHTML = $tpl->getContent(FRONTEND_CORE_PATH . '/layout/templates/share.tpl');

		// add share options to item
		$item['share'] = $shareHTML;
	}


	/**
	 * Add the share options to the given array
	 *
	 * @return	void
	 * @param	string $string		The module.
	 * @param	array $items		The items to add the share options to.
	 */
	public static function getShareOptionsForItems($module, &$items)
	{
		// get the share settings for this module
		$options = self::getShareOptionsForModule($module);

		// loop items
		foreach($items as &$item)
		{
			// add share options for each item
			self::getShareOptionsForItem($module, $item, $options);
		}
	}


	/**
	 * Get the share options for the given module
	 *
	 * @return	array
	 * @param	string $string		The module.
	 */
	public static function getShareOptionsForModule($module)
	{
		// get the share options for this module
		$options = (array) FrontendModel::getDB()->getRecords('SELECT i.*, s.name AS service_name
														FROM share_settings AS i
														INNER JOIN share_services AS s ON i.service_id = s.id
														WHERE i.module = ?',
														$module);

		// return null if empty
		if(empty($options)) return null;

		// redefine the array so we have the other id as key
		$redefined = array();

		// loop options and add to the redefined array
		foreach($options as $option) $redefined[$option['other_id']][] = $option;

		// return the redefined array
		return $redefined;
	}
}