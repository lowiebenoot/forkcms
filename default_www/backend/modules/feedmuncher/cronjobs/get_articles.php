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
	 * Execute the action
	 *
	 * @return	void
	 */
	public function execute()
	{
		// call parent, this will probably add some general CSS/JS or other required files
		parent::execute();

		// get data
		$this->getData();
	}


	/**
	 * Get data
	 *
	 * @return	void
	 */
	private function getData()
	{
		// get the feeds
		$feeds = BackendFeedmuncherModel::getAllFeeds();

		// loop feeds
		foreach($feeds as $feed)
		{
			// defining a boolean to check if the RSS is valid
			$feedIsValid = true;

			// try to read the RSS (RSS 2.0)
			try
			{
				// read the feed
				$rssFeed = SpoonFeedRSS::readFromFeed($feed['url']);

				// set the feed type to RSS 2.0
				$feed['type'] = 'RSS';
			}

			catch(Exception $e)
			{
				// try to read the RSS (ATOM RSS)
				try
				{
					// read the fead
					$rssFeed = SpoonFeedAtomRSS::readFromFeed($feed['url']);

					// set the feed type to Atom
					$feed['type'] = 'ATOM';
				}

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

				// get the dates of the posts allready inserted for this feed
				$publishedDates = BackendFeedmuncherModel::getPublishedDates((int) $feed['id']);

				foreach($items as $feedItem)
				{
					// define item array (so it's an empty array)
					$item = array();

					// get the article publication date
					$item['date'] = BackendModel::getUTCDate('Y-m-d H:i:s', $feedItem->getPublicationDate());

					// is it a new item? (not published yet)
					if(!in_array($item['date'], $publishedDates))
					{
						// build article
						$item['id'] = (int) BackendFeedmuncherModel::getMaximumId() + 1;
						$item['category_id'] = (int) $feed['category_id'];
						$item['feed_id'] = (int) $feed['id'];
						$item['user_id'] = (int) $feed['author_user_id'];
						$item['meta_id'] = BackendFeedmuncherModel::insertMeta((string) $feedItem->getTitle());
						$item['language'] = $feed['language'];
						$item['title'] = $feedItem->getTitle();
						$item['text'] = ($feed['type'] == 'ATOM') ? $feedItem->getContent() : $feedItem->getDescription();
						$item['introduction'] = ($feed['type'] == 'ATOM') ? $feedItem->getSummary() : '<p>' . substr(strip_tags($item['text']), 0, 500) . '...</p>';
						$item['hidden'] = $feed['auto_publish'] == 'Y' ? 'N' : 'Y';
						$item['allow_comments'] = BackendModel::getModuleSetting('feedmuncher', 'allow_comments');
						$item['edited_on'] = BackendModel::getUTCDate();
						$item['created_on'] = BackendModel::getUTCDate();
						$item['target'] = $feed['target'];
						$item['status'] = 'active';
						$item['original_url'] = $feedItem->getLink();

						// insert article in feedmuncher posts
						BackendFeedmuncherModel::insertArticle($item);

						// article not hidden?
						if($item['hidden'] == 'N')
						{
							// posting in blog?
							if($feed['target'] == 'blog')
							{
								// require the blog engine
								require_once BACKEND_MODULES_PATH . '/blog/engine/model.php';

								// alter the item for the blog table
								$item['publish_on'] = $item['date'];
								$item['meta_id'] = BackendFeedmuncherModel::insertMeta($item['title'], BackendBlogModel::getURL($item['title']));
								unset($item['date']);
								unset($item['target']);
								unset($item['feed_id']);
								unset($item['original_url']);

								// get the meta object
								$meta = BackendFeedmuncherModel::getMetaByid($item['meta_id']);

								// insert in blog posts
								BackendBlogModel::insert($item);

								// get the blogpost id (not revision id!)
								$blogPostId = BackendBlogModel::getMaximumId();

								// add search index
								if(is_callable(array('BackendSearchModel', 'addIndex'))) BackendSearchModel::addIndex('blog', $blogPostId, array('title' => $item['title'], 'text' => $item['text']));

								// ping
								if(BackendModel::getModuleSetting('blog', 'ping_services', false)) BackendModel::ping(SITE_URL . BackendModel::getURLForBlock('blog', 'detail') . '/' . $meta['url']);

								// save the blog post id in the feedmuncher post
								BackendFeedmuncherModel::setBlogPostsId($item['id'], $blogPostId);
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
				}
			}
		}
	}
}

?>