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
	 * @param	string $module				The module.
	 * @param	string $type				The type of the element that is shared (example: article/category/...).
	 * @param	array $item					The item to add the share options to.
	 * @param	string $titleKey			The array key that is used to get the title.
	 * @param	array $callback				The callback that is used to get the shareURL.
	 * @param	array[optional] $options	The share options.
	 * @param	array[optional] $urls		The urls for the items (with as keys the ids of the items).
	 */
	public static function getShareOptionsForItem($module, $type, &$item, $titleKey, $callback, $options = null, $urls = null)
	{
		// get the share settings for this module
		if($options == null) $options = self::getShareOptionsForModuleAndType($module, $type);

		// urls aren't available yet?
		if($urls == null)
		{
			// get share urls
			$urls = call_user_func_array($callback, array(array($item['id'])));
		}

		// get share url that belongs to this item
		$url = $urls[(int) $item['id']];

		// get title
		$title = $item[$titleKey];

		// create a new template
		$tpl = new FrontendTemplate(false);

		// are there any options for this item?
		if(isset($options[$item['id']]))
		{
			// assign shareOptions in template
			$tpl->assign('shareOptions', true);

			// loop options for this item
			foreach($options[$item['id']] as $option)
			{
				// which share service?
				switch(strtolower($option['service_name']))
				{
					// the setting is for facebook?
					case 'facebook':
						// assign url in the like button
						$tpl->assign('facebook_share_url', $url);

						// assign id
						$tpl->assign('facebook_share_id', $option['id']);
					break;

					// the setting is for twitter?
					case 'twitter':
						// should the url be shortened?
						$shortenURLs = FrontendModel::getModuleSetting('share', 'shorten_urls_' . FRONTEND_LANGUAGE);

						// shorten URL if needed
						if($shortenURLs) $shortenedURL = self::shortenURL($url);

						// create twitter message for the url
						$twitterMessage = urlencode($option['message'] . ($option['message'] == '' ? '' : ' - ') . ($shortenURLs ? $shortenedURL : $url));

						// replace #
						$twitterMessage = str_replace('#', '%23', $twitterMessage);

						// replace + (space)
						$twitterMessage = str_replace('+', '%20', $twitterMessage);

						// share url
						$shareURL= 'http://twitter.com?status=' . $twitterMessage;

						// assign url
						$tpl->assign('twitter_share_url', $shareURL);

						// assign id
						$tpl->assign('twitter_share_id', $option['id']);
					break;

					// the setting is for delicious?
					case 'delicious':
						// assign url
						$tpl->assign('delicious_share_url', urlencode('http://www.delicious.com/save?url=' . urlencode($url)));

						// assign id
						$tpl->assign('delicious_share_id', $option['id']);
					break;

					// the setting is for stumbleupon?
					case 'stumbleupon':
						// assign url
						$tpl->assign('stumbleupon_share_url', urlencode('http://www.stumbleupon.com/submit?url=' . $url));

						// assign id
						$tpl->assign('stumbleupon_share_id', $option['id']);
					break;

					// the setting is for linkedin?
					case 'linkedin':
						// assign url
						$tpl->assign('linkedin_share_url', urlencode('http://www.linkedin.com/shareArticle?mini=true&url=' . urlencode($url) . '&title=' . urlencode($title)));

						// assign id
						$tpl->assign('linkedin_share_id', $option['id']);
					break;

					// the setting is for reddit?
					case 'reddit':
						// assign url
						$tpl->assign('reddit_share_url', urlencode('http://www.reddit.com/submit?url=' . urlencode($url) . '&title=' . urlencode($title)));

						// assign id
						$tpl->assign('reddit_share_id', $option['id']);
					break;

					// the setting is for netlog?
					case 'netlog':
						// assign url
						$tpl->assign('netlog_share_url', urlencode('http://www.netlog.com/go/manage/links/view=save&origin=external&url=' . urlencode($url) . '&title=' . urlencode($title)));

						// assign id
						$tpl->assign('netlog_share_id', $option['id']);
					break;

					// the setting is for digg?
					case 'digg':
						// assign url
						$tpl->assign('digg_share_url', urlencode('http://digg.com/submit?url=' . urlencode($url) . '&title=' . urlencode($title)));

						// assign id
						$tpl->assign('digg_share_id', $option['id']);
					break;

					// the setting is for tumblr?
					case 'tumblr':
						// assign url
						$tpl->assign('tumblr_share_url', urlencode('http://www.tumblr.com/share?v=3&u=' . urlencode($url) . '&t=' . urlencode($title)));

						// assign id
						$tpl->assign('tumblr_share_id', $option['id']);
					break;

					// the setting is for google buzz?
					case 'google buzz':
						// assign url
						$tpl->assign('googlebuzz_share_url', urlencode('http://www.google.com/buzz/post?url=' . urlencode($url) . '&title=' . urlencode($title)));

						// assign id
						$tpl->assign('googlebuzz_share_id', $option['id']);
					break;

					// non existing share service
					default:
						throw new SpoonException('Invalid share service');
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
	 * @param	string $module		The module.
	 * @param	string $type		The type of the element that is shared (example: article/category/...).
	 * @param	array $items		The items to add the share options to.
	 * @param	string $titleKey	The array key that is used to get the title.
	 * @param	array $callback		The callback that is used to get the shareURL.
	 */
	public static function getShareOptionsForItems($module, $type, &$items, $titleKey, $callback)
	{
		// define ids array
		$ids = array();

		// loop items and at the id to the ids array
		foreach($items as $item) $ids[] = $item['id'];

		// get the share settings for this module
		$options = self::getShareOptionsForModuleAndType($module, $type);

		// get share urls
		$urls = call_user_func_array($callback, array($ids));

		// loop items
		foreach($items as &$item)
		{
			// add share options for each item
			self::getShareOptionsForItem($module, $type, $item, $titleKey, $callback, $options);
		}
	}


	/**
	 * Get the share options for the given module
	 *
	 * @return	array
	 * @param	string $module		The module.
	 * @param	string $type		The type of the element that is shared (example: article/category/...).
	 */
	public static function getShareOptionsForModuleAndType($module, $type)
	{
		// get the share options for this module
		$options = (array) FrontendModel::getDB()->getRecords('SELECT i.*, s.name AS service_name
														FROM share_settings AS i
														INNER JOIN share_services AS s ON i.service_id = s.id
														WHERE i.module = ? AND i.item_type = ? AND i.active = ?',
														array($module, $type, 'Y'));

		// return null if empty
		if(empty($options)) return null;

		// redefine the array so we have the other id as key
		$redefined = array();

		// loop options and add to the redefined array
		foreach($options as $option) $redefined[$option['other_id']][] = $option;

		// return the redefined array
		return $redefined;
	}


	/**
	 * Shortens an URL by using the tinyURL API.
	 *
	 * @return	string
	 * @param	string $url		The URL to shorten.
	 */
	private static function shortenURL($url)
	{
		// require tinyURL class
		require_once PATH_LIBRARY . '/external/tinyurl.php';

		// create a TinyURL iinstance
		$tiny = new TinyUrl();

		// try to get short url
		try
		{
			// do api call
			$shortURL = $tiny->create($url);
		}

		// catch exceptions
		catch(Exception $e)
		{
			// return original
			return $url;
		}

		// return shortened URL
		return $shortURL;
	}
}