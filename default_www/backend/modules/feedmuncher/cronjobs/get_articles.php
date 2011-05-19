<?php

/**
 * This cronjob will fetch the articles of the RSS feeds
 *
 * @package		backend
 * @subpackage	feedmuncher
 *
 * @author		Lowie Benoot <lowiebenoot@netlash.com>
 * @since		2.1
 */
class BackendFeedmuncherCronjobGetArticles extends BackendBaseCronjob
{
	/**
	 * Cleanup cache files
	 *
	 * @return	void
	 */
	private function cleanupCache()
	{
		// get cache files
		$files = SpoonFile::getList($this->cachePath);

		// loop items
		foreach($files as $file)
		{
			// get info
			$fileinfo = SpoonFile::getInfo($this->cachePath . '/' . $file);

			// file is more than one week old
			if($fileinfo['modification_date'] < strtotime('-1 week'))
			{
				// delete file
				SpoonFile::delete($this->cachePath . '/' . $file);
			}
		}
	}


	/**
	 * Execute the action
	 *
	 * @return	void
	 */
	public function execute()
	{
		// call parent, this will probably add some general CSS/JS or other required files
		parent::execute();

		// get parameters
		$identifier = trim(SpoonFilter::getGetValue('identifier', null, ''));

		// init vars
		$this->cachePath = BACKEND_CACHE_PATH . '/feedmuncher';
		$this->filename = null;

		// identifier given? curl called
		if($identifier != '')
		{
			// init vars
			$this->filename = $this->cachePath . '/' . $identifier . '.txt';

			// create temporary file to indicate we're getting data
			SpoonFile::setContent($this->filename, 'busy1');
		}

		// get data
		$this->getData();

		// cleanup cache
		$this->cleanupCache();
	}


	/**
	 * Get data
	 *
	 * @return	void
	 */
	private function getData()
	{
		try
		{
			// get the feeds
			$feeds = BackendFeedmuncherModel::getAllFeeds();

			// set booleans for cache invalidation
			$this->invalidateFeedmuncherFrontendCache = false;
			$this->invalidateBlogFrontendCache = false;

			// loop feeds
			foreach($feeds as $feed)
			{
				// is it an aggregating feed?
				if($feed['reoccurrence'] != null)
				{
					// unserialize the reoccurrence
					$reoccurrence = unserialize($feed['reoccurrence']);

					// posting weekly?
					if($reoccurrence['reoccurrence'] == 'weekly')
					{
						// skip this feed if it's not the correct day
						if($reoccurrence['day'] != date('N')) continue;
					}

					// get timedifference between now and the time that this feed should be posted
					$timeDifference = strtotime(date('H:i')) - strtotime($reoccurrence['time']);

					// is the feed already fetched this hour? (difference between now and date_fetched is less then 1 hour)
					$isAlreadyFetched = (strtotime(BackendModel::getUTCDate(null, strtotime('+1 hour'))) - strtotime($feed['date_fetched'])) < 3600;

					// skip this feed if the timedifference is greater then 1 hour but less than 0 OR if the feed is already fetched
					if($timeDifference > 3600 || $timeDifference < 0 || $isAlreadyFetched) continue;
				}

				// create the twitter feed url (if it's a twitter feed)
				if($feed['feed_type'] == 'twitter') $feed['url'] = 'http://api.twitter.com/1/statuses/user_timeline.rss?count=200&screen_name=' . $feed['source'];

				// create delicious feed url (if it's a delicious feed)
				if($feed['feed_type'] == 'delicious') $feed['url'] = 'http://feeds.delicious.com/v2/rss/' . $feed['source'] . '?count=100';

				// defining a boolean to check if the RSS is valid
				$feedIsValid = true;

				// try to read the RSS (RSS 2.0)
				try
				{
					// read the feed
					$rssFeed = SpoonFeedRSS::readFromFeed($feed['url'], 'url', true);

					// set the feed type to RSS 2.0
					$feed['rss_type'] = 'RSS2.0';
				}

				catch(Exception $e)
				{
					// try to read the RSS (ATOM RSS)
					try
					{
						// read the fead
						$rssFeed = SpoonFeedAtomRSS::readFromFeed($feed['url'], 'url', true);

						// set the feed type to Atom
						$feed['rss_type'] = 'ATOM';
					}

					// catch exceptions
					catch(Exception $e)
					{
						$feedIsValid = false;
					}
				}

				// is it a valid RSS feed?
				if($feedIsValid)
				{
					// get the feed items
					$items = $rssFeed->getItems();

					// is it a normal RSS feed?
					if($feed['feed_type'] == 'feed')
					{
						// process the feed aggregated if needed
						if($feed['reoccurrence']) $this->processFeedAggregated($feed, $items);

						// process the feed normal (not aggregated)
						else $this->processFeedNormal($feed, $items);
					}

					// is it a twitter feed?
					elseif($feed['feed_type'] == 'twitter')
					{
						// process the twitter feed
						$this->processTwitterFeed($feed, $items);
					}

					// is it a delicious feed?
					elseif($feed['feed_type'] == 'delicious')
					{
						// process the delicious feed
						$this->processDeliciousFeed($feed, $items);
					}

					// update date fetched
					BackendFeedmuncherModel::update($feed['id'], array('date_fetched' => BackendModel::getUTCDate()));
				}
			}

			// invalidate frontend cache if needed
			if($this->invalidateFeedmuncherFrontendCache) BackendModel::invalidateFrontendCache('feedmuncher');
			if($this->invalidateBlogFrontendCache) BackendModel::invalidateFrontendCache('blog');
		}

		catch(Exception $e)
		{
			// set file content to indicate something went wrong if needed
			if(isset($this->filename)) SpoonFile::setContent($this->filename, 'error');

			// or throw exception
			else throw new SpoonException('Something went wrong while getting data.');
		}

		// set file content 'done' if needed
		if(isset($this->filename)) SpoonFile::setContent($this->filename, 'done');
	}


	/**
	 * Insert the article
	 *
	 * @return	void
	 * @param	array $item		The item to insert.
	 */
	private function insertArticle($item)
	{
		// insert article in feedmuncher posts
		BackendFeedmuncherModel::insertArticle($item);

		// article not hidden?
		if($item['hidden'] == 'N')
		{
			// we should invalidate frontend feedmuncher cache
			$this->invalidateFeedmuncherFrontendCache = true;

			// posting in blog?
			if($item['target'] == 'blog')
			{
				// we should invalidate frontend feedmuncher cache
				$this->invalidateBlogFrontendCache = true;

				// require the blog engine
				require_once BACKEND_MODULES_PATH . '/blog/engine/model.php';

				// feedmuncher_post id
				$feedmuncherPostId = $item['id'];

				// alter the item for the blog table
				$item['id'] = BackendBlogModel::getMaximumId() + 1;
				$item['publish_on'] = $item['date'];
				$item['meta_id'] = BackendFeedmuncherModel::insertMeta($item['title'], BackendBlogModel::getURL($item['title']));

				// unset elements that we don't need
				unset($item['date']);
				unset($item['target']);
				unset($item['feed_id']);
				unset($item['original_url']);
				unset($item['link_to_original']);

				// get the meta object
				$meta = BackendFeedmuncherModel::getMetaByid($item['meta_id']);

				// insert in blog posts
				BackendBlogModel::insert($item);

				// add search index
				if(is_callable(array('BackendSearchModel', 'addIndex'))) BackendSearchModel::addIndex('blog', $item['id'], array('title' => $item['title'], 'text' => $item['text']));

				// ping
				if(BackendModel::getModuleSetting('blog', 'ping_services', false)) BackendModel::ping(SITE_URL . BackendModel::getURLForBlock('blog', 'detail') . '/' . $meta['url']);

				// save the blog post id in the feedmuncher post
				BackendFeedmuncherModel::setBlogPostsId($feedmuncherPostId, $item['id']);
			}

			// posting in feedmuncher?
			else
			{
				// get the meta object
				$meta = BackendFeedmuncherModel::getMetaByid($item['meta_id']);

				// add search index
				if(is_callable(array('BackendSearchModel', 'addIndex'))) BackendSearchModel::addIndex('feedmuncher', $item['id'], array('title' => $item['title'], 'text' => $item['text']));

				// ping
				if(BackendModel::getModuleSetting('feedmuncher', 'ping_services', false)) BackendModel::ping(SITE_URL . BackendModel::getURLForBlock('feedmuncher', 'detail') . '/' . $meta['url']);
			}
		}
	}


	/**
	 * Process a delicious feed
	 *
	 * @return	void
	 * @param	array $feed		The feed.
	 * @param	array $items	The items retrieved from the feed.
	 */
	private function processDeliciousFeed($feed, $items)
	{
		// build article content
		$content = '<ul class="aggregatedDeliciousBookmarks">';

		// loop items
		foreach($items as $feedItem)
		{
			// is it a new bookmark (not fetched yet)?
			if($feed['date_fetched'] < BackendModel::getUTCDate(null, $feedItem->getPublicationDate()))
			{
				// get the bookmark title
				$title = $feedItem->getTitle();

				// get the bookmark url
				$url = $feedItem->getLink();

				// get date and time
				$datetime = SpoonDate::getDate(BackendModel::getModuleSetting('core', 'date_format_long') . ' ' . BackendModel::getModuleSetting('core', 'time_format'), $feedItem->getPublicationDate(), $feed['language']);

				// add to article content
				$content .= '<li><a href="' . $url . '" title="' . $title . '">' . $title . '</a><em> - ' . $datetime . '</em></li>';
			}

			// old bookmark, we already have fetched this one, break the foreach.
			else break;
		}

		// at least one bookmark was added?
		if(isset($title))
		{
			// add ul closing tag
			$content .= '</ul>';

			// build item
			$item['id'] = (int) BackendFeedmuncherModel::getMaximumId() + 1;
			$item['category_id'] = (int) $feed['category_id'];
			$item['feed_id'] = (int) $feed['id'];
			$item['user_id'] = (int) $feed['author_user_id'];
			$item['title'] = $feed['name'] . ' - ' . ($reoccurrence['reoccurrence'] == 'daily' ? date('d/m') : BL::lbl('Week') . ' ' . date('W'));
			$item['meta_id'] = BackendFeedmuncherModel::insertMeta($item['title']);
			$item['language'] = $feed['language'];
			$item['text'] = $content;
			$item['introduction'] = null;
			$item['hidden'] = $feed['auto_publish'] == 'Y' ? 'N' : 'Y';
			$item['allow_comments'] = BackendModel::getModuleSetting('feedmuncher', 'allow_comments');
			$item['edited_on'] = BackendModel::getUTCDate();
			$item['created_on'] = BackendModel::getUTCDate();
			$item['date'] = BackendModel::getUTCDate('Y-m-d H:i') . ':00';
			$item['target'] = $feed['target'];
			$item['status'] = 'active';
			$item['original_url'] = null;

			// insert article
			$this->insertArticle($item);
		}
	}


	/**
	 * Process a regular feed and aggregate it into 1 article
	 *
	 * @return	void
	 * @param	array $feed		The feed.
	 * @param	array $items	The items retrieved from the feed.
	 */
	private function processFeedAggregated($feed, $items)
	{
		// create string for the article html
		$content = '<ul class="aggregatedFeed">';

		// unserialize recorrence
		$reoccurrence = unserialize($feed['reoccurrence']);

		// loop items
		foreach($items as $feedItem)
		{
			// is it a new item? (not published yet)
			if($feed['date_fetched'] < BackendModel::getUTCDate(null, $feedItem->getPublicationDate()))
			{
				// get the bookmark title
				$title = $feedItem->getTitle();

				// get the bookmark url
				$url = $feedItem->getLink();

				// get date and time
				$datetime = SpoonDate::getDate(BackendModel::getModuleSetting('core', 'date_format_long') . ' ' . BackendModel::getModuleSetting('core', 'time_format'), $feedItem->getPublicationDate(), $feed['language']);

				// add post to html
				$content .= '<li><a href="' . $url . '" title="' . $title . '" class="linkToOriginal">' . $title . '</a><em> - ' . $datetime . '</em></li>';
			}
		}

		// at least one article was added?
		if(isset($title))
		{
			$item['id'] = (int) BackendFeedmuncherModel::getMaximumId() + 1;
			$item['category_id'] = (int) $feed['category_id'];
			$item['feed_id'] = (int) $feed['id'];
			$item['user_id'] = (int) $feed['author_user_id'];
			$item['language'] = $feed['language'];
			$item['title'] = $feed['name'] . ' - ' . ($reoccurrence['reoccurrence'] == 'daily' ? date('d/m') : BL::lbl('Week') . ' ' . date('W'));
			$item['meta_id'] = BackendFeedmuncherModel::insertMeta((string) $item['title']);
			$item['text'] = $content;
			$item['hidden'] = $feed['auto_publish'] == 'Y' ? 'N' : 'Y';
			$item['allow_comments'] = BackendModel::getModuleSetting('feedmuncher', 'allow_comments');
			$item['edited_on'] = BackendModel::getUTCDate();
			$item['created_on'] = BackendModel::getUTCDate();
			$item['target'] = $feed['target'];
			$item['status'] = 'active';
			$item['original_url'] = null;
			$item['link_to_original'] = 'N';
			$item['date'] = BackendModel::getUTCDate('Y-m-d H:i') . ':00';

			// insert article
			$this->insertArticle($item);
		}
	}


	/**
	 * Process a regular feed (not aggregated)
	 *
	 * @return	void
	 * @param	array $feed		The feed.
	 * @param	array $items	The items retrieved from the feed.
	 */
	private function processFeedNormal($feed, $items)
	{
		// loop items
		foreach($items as $feedItem)
		{
			// get the article publication date
			$item['date'] = BackendModel::getUTCDate(null, $feedItem->getPublicationDate());

			// is it a new item? (not published yet)
			if($feed['date_fetched'] < $item['date'])
			{
				// build article
				$item['id'] = (int) BackendFeedmuncherModel::getMaximumId() + 1;
				$item['category_id'] = (int) $feed['category_id'];
				$item['feed_id'] = (int) $feed['id'];
				$item['user_id'] = (int) $feed['author_user_id'];
				$item['meta_id'] = BackendFeedmuncherModel::insertMeta((string) $feedItem->getTitle());
				$item['language'] = $feed['language'];
				$item['title'] = $feedItem->getTitle();
				$item['text'] = trim(($feed['rss_type'] == 'ATOM') ? $feedItem->getContent() : $feedItem->getDescription());
				$item['introduction'] = ($feed['rss_type'] == 'ATOM') ? $feedItem->getSummary() : '<p>' . substr(strip_tags($item['text']), 0, 500) . '...</p>';
				$item['hidden'] = $feed['auto_publish'] == 'Y' ? 'N' : 'Y';
				$item['allow_comments'] = BackendModel::getModuleSetting('feedmuncher', 'allow_comments');
				$item['edited_on'] = BackendModel::getUTCDate();
				$item['created_on'] = BackendModel::getUTCDate();
				$item['target'] = $feed['target'];
				$item['status'] = 'active';
				$item['original_url'] = $feedItem->getLink();
				$item['link_to_original'] = $feed['link_to_original'];

				// insert article
				$this->insertArticle($item);
			}
		}
	}


	/**
	 * Process a twitter feed
	 *
	 * @return	void
	 * @param	array $feed		The feed.
	 * @param	array $items	The items retrieved from the feed.
	 */
	private function processTwitterFeed($feed, $items)
	{
		// build article content
		$content = '<ul class="aggregatedTweets">';

		// loop items
		foreach($items as $feedItem)
		{
			// is it a new tweet (not fetched yet)?
			if($feed['date_fetched'] < BackendModel::getUTCDate('Y-m-d H:i:s', $feedItem->getPublicationDate()))
			{
				// get the tweet
				$tweet = $feedItem->getTitle();

				// delete the username from the tweet
				$tweet = substr($tweet, strpos($tweet, ' '));

				// replace urls
				$tweet = preg_replace('#(^|[\n ])([\w]+?://[\w]+[^ "\n\r\t< ]*)#', '\\1<a href="\\2">\\2</a>', $tweet);
				$tweet = preg_replace('#(^|[\n ])((www|ftp)\.[^ "\t\n\r< ]*)#', '\\1<a href="http://\\2">\\2</a>', $tweet);

				// replace twitter usernames
				$tweet = preg_replace('/(^|\s)@(\w+)/','\1<a href="http://www.twitter.com/\2">@\2</a>', $tweet);

				// replace twitter hashtags
				$tweet = preg_replace('/(^|\s)#(\w+)/', '\1<a href="http://twitter.com/#!/search/%23\2">#\2</a>', $tweet);

				// get date and time
				$datetime = SpoonDate::getDate(BackendModel::getModuleSetting('core', 'date_format_long') . ' ' . BackendModel::getModuleSetting('core', 'time_format'), $feedItem->getPublicationDate(), $feed['language']);

				// add to article content
				$content .= '<li>' . $tweet . '<em> - ' . $datetime . '</em></li>';
			}

			// old tweet, we already have fetched this one, break the foreach.
			else break;
		}

		// at least one tweet was added?
		if(isset($tweet))
		{
			// add ul closing tag
			$content .= '</ul>';

			// build item
			$item['id'] = (int) BackendFeedmuncherModel::getMaximumId() + 1;
			$item['category_id'] = (int) $feed['category_id'];
			$item['feed_id'] = (int) $feed['id'];
			$item['user_id'] = (int) $feed['author_user_id'];
			$item['title'] = $feed['name'] . ' - ' . ($reoccurrence['reoccurrence'] == 'daily' ? date('d/m') : BL::lbl('Week') . ' ' . date('W'));
			$item['meta_id'] = BackendFeedmuncherModel::insertMeta($item['title']);
			$item['language'] = $feed['language'];
			$item['text'] = $content;
			$item['introduction'] = null;
			$item['hidden'] = $feed['auto_publish'] == 'Y' ? 'N' : 'Y';
			$item['allow_comments'] = BackendModel::getModuleSetting('feedmuncher', 'allow_comments');
			$item['edited_on'] = BackendModel::getUTCDate();
			$item['created_on'] = BackendModel::getUTCDate();
			$item['date'] = BackendModel::getUTCDate('Y-m-d H:i') . ':00';
			$item['target'] = $feed['target'];
			$item['status'] = 'active';
			$item['original_url'] = null;

			// insert article
			$this->insertArticle($item);
		}
	}
}

?>