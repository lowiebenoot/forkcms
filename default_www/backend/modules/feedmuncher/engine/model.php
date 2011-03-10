<?php

/**
 * In this file we store all generic functions that we will be using in the feedmuncher module
 *
 * @package		backend
 * @subpackage	feedmuncher
 *
 * @author		Lowie Benoot <lowiebenoot@netlash.com>
 * @since		2.0
 */
class BackendFeedmuncherModel
{
	const QRY_DATAGRID_BROWSE_ARTICLES = 'SELECT i.id, i.revision_id, i.title, UNIX_TIMESTAMP(i.date) AS publish_on, UNIX_TIMESTAMP(i.created_on) AS created_on, f.id AS feed_id, f.name AS feed, i.user_id, i.num_comments AS comments, i.hidden, blog_post_id
									FROM feedmuncher_posts AS i
									INNER JOIN feedmuncher_feeds as f ON f.id = i.feed_id
									WHERE i.status = ? AND i.language = ? AND i.deleted = ? AND i.target = ? AND hidden = ?';
	const QRY_DATAGRID_BROWSE_ARTICLES_NOT_PUBLISHED = 'SELECT i.id, i.revision_id, i.title, UNIX_TIMESTAMP(i.date) AS publish_on, UNIX_TIMESTAMP(i.created_on) AS created_on, f.id AS feed_id, f.name AS feed, i.user_id, i.hidden
									FROM feedmuncher_posts AS i
									INNER JOIN feedmuncher_feeds as f ON f.id = i.feed_id
									WHERE i.status = ? AND i.language = ? AND i.deleted = ? AND hidden = ?';
	const QRY_DATAGRID_BROWSE_CATEGORIES = 'SELECT i.id, i.name
											FROM feedmuncher_categories AS i
											WHERE i.language = ?';
	const QRY_DATAGRID_BROWSE_COMMENTS = 'SELECT i.id, UNIX_TIMESTAMP(i.created_on) AS created_on, i.author, i.text,
											p.id AS post_id, p.title AS post_title, m.url AS post_url
											FROM feedmuncher_comments AS i
											INNER JOIN feedmuncher_posts AS p ON i.post_id = p.id AND i.language = p.language
											INNER JOIN meta AS m ON p.meta_id = m.id
											WHERE i.status = ? AND i.language = ?
											GROUP BY i.id';
	const QRY_DATAGRID_BROWSE_DRAFTS = 'SELECT i.id, i.user_id, i.revision_id, i.title, UNIX_TIMESTAMP(i.edited_on) AS edited_on, UNIX_TIMESTAMP(i.created_on) AS created_on, f.id AS feed_id, f.name AS feed, i.num_comments AS comments, i.hidden
										FROM feedmuncher_posts AS i
										INNER JOIN
										(
											SELECT MAX(i.revision_id) AS revision_id
											FROM feedmuncher_posts AS i
											WHERE i.status = ? AND i.user_id = ? AND i.language = ?
											GROUP BY i.id
										) AS p
										INNER JOIN feedmuncher_feeds as f ON f.id = i.feed_id
										WHERE i.revision_id = p.revision_id  AND i.deleted = ? AND i.target = ?';
	const QRY_DATAGRID_BROWSE_FEEDS = 'SELECT i.id, i.name, i.source, i.author_user_id, i.category_id, i.target
										FROM feedmuncher_feeds AS i
										WHERE i.language = ? AND deleted = ?';
	const QRY_DATAGRID_BROWSE_RECENT = 'SELECT i.id, i.revision_id, i.title, UNIX_TIMESTAMP(i.edited_on) AS edited_on, UNIX_TIMESTAMP(i.created_on) AS created_on, f.id AS feed_id, f.name AS feed, i.user_id, i.num_comments AS comments, i.hidden
										FROM feedmuncher_posts AS i
										INNER JOIN feedmuncher_feeds as f ON f.id = i.feed_id
										WHERE i.status = ? AND i.language = ? AND i.deleted = ? AND i.target = ?
										ORDER BY i.date DESC
										LIMIT ?';
	const QRY_DATAGRID_BROWSE_REVISIONS = 'SELECT i.id, i.revision_id, i.title, UNIX_TIMESTAMP(i.edited_on) AS edited_on, UNIX_TIMESTAMP(i.created_on) AS created_on, i.user_id, i.hidden
											FROM feedmuncher_posts AS i
											WHERE i.status = ? AND i.id = ? AND i.language = ? AND i.target = ?
											ORDER BY i.date DESC';
	const QRY_DATAGRID_BROWSE_SPECIFIC_DRAFTS = 'SELECT i.id, i.revision_id, i.title, UNIX_TIMESTAMP(i.edited_on) AS edited_on, UNIX_TIMESTAMP(i.created_on) AS created_on, i.user_id
													FROM feedmuncher_posts AS i
													WHERE i.status = ? AND i.id = ? AND i.language = ? AND i.deleted = ? AND i.target = ?
													ORDER BY i.date DESC';

	/**
	 * checks if the blog module is installed
	 *
	 * @return bool
 	 */
	public static function blogIsInstalled()
	{
		// exists?
		return (bool) ((int) BackendModel::getDB()->getVar('SELECT COUNT(i.name)
															FROM modules AS i
															WHERE i.name = ?;',
															'blog') > 0);
	}


	/**
	 * checks if there are blogposts that are deleted, if so, mark them as deleted in feedmuncher_posts
	 *
 	 */
	public static function checkForDeletedBlogPosts()
	{
		// exists?
		$deletedArticles = (array) BackendModel::getDB()->getColumn('SELECT i.id
															FROM feedmuncher_posts AS i
															LEFT JOIN blog_posts AS b on b.id = i.blog_post_id
															WHERE i.target = ? AND i.hidden = ? AND b.id IS NULL;',
															array('blog', 'N'));

		// delete the articles
		self::deleteArticle((int) $deletedArticles);
	}


	/**
	 * Deletes one or more feeds
	 *
	 * @param 	mixed $ids		The ids to delete.
	 */
	public static function delete($ids)
	{
		// make sure $ids is an array
		$ids = (array) $ids;

		// delete feed in db (mark as deleted)
		$db = BackendModel::getDB(true)->update('feedmuncher_feeds', array('deleted' => 'Y') , 'id IN ('. implode(',', $ids) .') AND language = ?', array(BL::getWorkingLanguage()));
	}


	/**
	 * Deletes a category
	 *
	 * @return	void
	 * @param	int $id		The id of the category to delete.
	 */
	public static function deleteCategory($id)
	{
		// redefine
		$id = (int) $id;

		// get db
		$db = BackendModel::getDB(true);

		// delete category
		$db->delete('feedmuncher_categories', 'id = ?', array($id));

		// default category
		$defaultCategoryId = BackendModel::getModuleSetting('feedmuncher', 'default_category_'. BL::getWorkingLanguage(), null);

		// update category for the posts that might be in this category
		$db->update('feedmuncher_posts', array('category_id' => $defaultCategoryId), 'category_id = ?', array($id));

		// invalidate the cache for feedmuncher
		BackendModel::invalidateFrontendCache('feedmuncher', BL::getWorkingLanguage());
	}


	/**
	 * Deletes one or more articles
	 *
	 * @param 	mixed $ids		The ids to delete.
	 */
	public static function deleteArticle($ids)
	{
		// make sure $ids is an array
		$ids = (array) $ids;

		// delete feed in db (mark as deleted)
		BackendModel::getDB(true)->update('feedmuncher_posts', array('deleted' => 'Y') , 'id IN ('. implode(',', $ids) .') AND language = ?', array(BL::getWorkingLanguage()));

		// invalidate the cache for feedmuncher
		BackendModel::invalidateFrontendCache('feedmuncher', BL::getWorkingLanguage());
	}


	/**
	 * Deletes one or more comments
	 *
	 * @return	void
	 * @param	array $ids						The id(s) of the comment(s) to delete.
	 */
	public static function deleteComments($ids)
	{
		// make sure $ids is an array
		$ids = (array) $ids;

		// get db
		$db = BackendModel::getDB(true);

		// get feedmuncherpost ids
		$postIds = (array) $db->getColumn('SELECT i.post_id
											FROM feedmuncher_comments AS i
											WHERE i.id IN ('. implode(',', $ids) .') AND i.language = ?', array(BL::getWorkingLanguage()));

		// update record
		$db->delete('feedmuncher_comments', 'id IN ('. implode(',', $ids) .') AND language = ?', array(BL::getWorkingLanguage()));

		// recalculate the comment count
		if(!empty($postIds)) self::reCalculateCommentCount($postIds);

		// invalidate the cache for feedmuncher
		BackendModel::invalidateFrontendCache('feedmuncher', BL::getWorkingLanguage());
	}


	/**
	 * Delete all spam
	 *
	 * @return	void
	 */
	public static function deleteSpamComments()
	{
		// get db
		$db = BackendModel::getDB(true);

		// get feedmuncherpost ids
		$postIds = (array) $db->getColumn('SELECT i.post_id
											FROM feedmuncher_comments AS i
											WHERE status = ? AND i.language = ?', array('spam', BL::getWorkingLanguage()));

		// update record
		$db->delete('feedmuncher_comments', 'status = ? AND language = ?', array('spam', BL::getWorkingLanguage()));

		// recalculate the comment count
		if(!empty($postIds)) self::reCalculateCommentCount($postIds);

		// invalidate the cache for feedmuncher
		BackendModel::invalidateFrontendCache('feedmuncher', BL::getWorkingLanguage());
	}


	/**
	 * Checks if a feed exists by URL
	 *
	 * @return	bool
	 * @param	int $id		The id of the feed
	 */
	public static function exists($id)
	{
		// exists?
		return (bool) ((int) BackendModel::getDB()->getVar('SELECT COUNT(i.id)
															FROM feedmuncher_feeds AS i
															WHERE i.id = ? AND i.language = ?;',
															array((int) $id, BL::getWorkingLanguage())) > 0);
	}


	/**
	 * Checks if a feed article exists
	 *
	 * @return	bool
	 * @param	int $id		The id of the article to check for existence.
	 */
	public static function existsArticle($id)
	{
		return (bool) BackendModel::getDB()->getVar('SELECT i.id
														FROM feedmuncher_posts AS i
														WHERE i.id = ? AND i.language = ?',
														array((int) $id, BL::getWorkingLanguage()));
	}


	/**
	 * Checks if a feed exists by URL
	 *
	 * @return	bool
	 * @param	string $URL		the url to check for existing
	 */
	public static function existsByURL($URL)
	{
		// exists?
		return (bool) ((int) BackendModel::getDB()->getVar('SELECT COUNT(i.id)
															FROM feedmuncher_feeds AS i
															WHERE i.url = ? AND i.language = ?;',
															array($URL, BL::getWorkingLanguage())) > 0);
	}


	/**
	 * Checks if a category exists
	 *
	 * @return	bool
	 * @param	int $id		The id of the category to check for existence.
	 */
	public static function existsCategory($id)
	{
		return (bool) BackendModel::getDB()->getVar('SELECT COUNT(id)
													FROM feedmuncher_categories AS i
													WHERE i.id = ? AND i.language = ?',
													array((int) $id, BL::getWorkingLanguage()));
	}


	/**
	 * Checks if a comment exists
	 *
	 * @return	bool
	 * @param	int $id		The id of the comment to check for existence.
	 */
	public static function existsComment($id)
	{
		return (bool) BackendModel::getDB()->getVar('SELECT COUNT(id)
														FROM feedmuncher_comments AS i
														WHERE i.id = ? AND i.language = ?',
														array((int) $id, BL::getWorkingLanguage()));
	}


	/**
	 * Checks if a the feed already has articles posted
	 *
	 * @return	bool
	 * @param	int $id		The id of the feed.
	 */
	public static function feedHasArticles($id)
	{
		return (bool) BackendModel::getDB()->getVar('SELECT COUNT(id)
														FROM feedmuncher_posts AS i
														WHERE i.feed_id = ?',
														(int) $id);
	}


	/**
	 * Get a feed
	 *
	 * @return	array
	 * @param	int $id		the id of the feed to get
	 */
	public static function get($id)
	{
		return (array) BackendModel::getDB()->getRecord('SELECT i.*
														FROM feedmuncher_feeds as i
														WHERE i.id = ?;',
														(int) $id);
	}


	/**
	 * Get all feeds
	 *
	 * @return	array
	 * @param	int $id		the id of the feed to get
	 */
	public static function getAllFeeds()
	{
		return (array) BackendModel::getDB()->getRecords('SELECT i.*
														FROM feedmuncher_feeds as i
														WHERE deleted = ?;',
														'N');
	}


	/**
	 * Get all data for a given id
	 *
	 * @return	array
	 * @param	int $id		The Id of the article to fetch?
	 */
	public static function getArticle($id)
	{
		return (array) BackendModel::getDB()->getRecord('SELECT i.*, UNIX_TIMESTAMP(i.date) AS publish_on, UNIX_TIMESTAMP(i.date) AS created_on, UNIX_TIMESTAMP(i.edited_on) AS edited_on,
															m.url
															FROM feedmuncher_posts AS i
															INNER JOIN meta AS m ON m.id = i.meta_id
															WHERE i.id = ? AND i.status = ? AND i.language = ?
															LIMIT 1',
															array((int) $id, 'active', BL::getWorkingLanguage()));
	}


	/**
	 * Get all data for a given revision
	 *
	 * @return	array
	 * @param	int $id							The id of the article.
	 * @param	int $revisionId					The revision to get.
	 */
	public static function getArticleRevision($id, $revisionId)
	{
		return (array) BackendModel::getDB()->getRecord('SELECT i.*, UNIX_TIMESTAMP(i.date) AS publish_on, UNIX_TIMESTAMP(i.date) AS created_on, UNIX_TIMESTAMP(i.edited_on) AS edited_on, m.url
															FROM feedmuncher_posts AS i
															INNER JOIN meta AS m ON m.id = i.meta_id
															WHERE i.id = ? AND i.revision_id = ?',
															array((int) $id, (int) $revisionId));
	}


	/**
	 * Get all categories
	 *
	 * @return	array
	 */
	public static function getCategories()
	{
		// get records and return them
		$categories = (array) BackendModel::getDB()->getPairs('SELECT i.id, i.name
																FROM feedmuncher_categories AS i
																WHERE i.language = ?', array(BL::getWorkingLanguage()));

		// no categories?
		if(empty($categories))
		{
			// build array
			$category['language'] = BL::getWorkingLanguage();
			$category['name'] = 'default';
			$category['url'] = 'default';

			// insert category
			$id = self::insertCategory($category);

			// store in settings
			BackendModel::setModuleSetting('feedmuncher', 'default_category_'. BL::getWorkingLanguage(), $id);

			// recall
			return self::getCategories();
		}

		// return the categories
		return $categories;
	}


	/**
	 * Get all categories from the blog
	 *
	 * @return	array
	 */
	public static function getCategoriesFromBlog()
	{
		// get records and return them
		return (array) BackendModel::getDB()->getPairs('SELECT i.id, i.name
																FROM blog_categories AS i
																WHERE i.language = ?', array(BL::getWorkingLanguage()));
	}


	/**
	 * Get all data for a given id
	 *
	 * @return	array
	 * @param	int $id							The Id of the comment to fetch?
	 */
	public static function getComment($id)
	{
		return (array) BackendModel::getDB()->getRecord('SELECT i.*, UNIX_TIMESTAMP(i.created_on) AS created_on,
															p.id AS post_id, p.title AS post_title, m.url AS post_url
															FROM feedmuncher_comments AS i
															INNER JOIN feedmuncher_posts AS p ON i.post_id = p.id AND i.language = p.language
															INNER JOIN meta AS m ON p.meta_id = m.id
															WHERE i.id = ?
															LIMIT 1',
															array((int) $id));
	}


	/**
	 * Get all data for a given id
	 *
	 * @return	array
	 * @param	int $id							The id of the category to fetch.
	 */
	public static function getCategory($id)
	{
		return (array) BackendModel::getDB()->getRecord('SELECT i.*
														FROM feedmuncher_categories AS i
														WHERE i.id = ? AND i.language = ?',
														array((int) $id, BL::getWorkingLanguage()));
	}


	/**
	 * Get all data for a given id
	 *
	 * @return	array
	 * @param	int $id							The id of the category to fetch.
	 */
	public static function getCategoryFromBlog($id)
	{
		return (array) BackendModel::getDB()->getRecord('SELECT i.*
														FROM blog_categories AS i
														WHERE i.id = ? AND i.language = ?',
														array((int) $id, BL::getWorkingLanguage()));
	}


	/**
	 * Get multiple comments at once
	 *
	 * @return	array
	 * @param	array $ids						The id(s) of the comment(s).
	 */
	public static function getComments(array $ids)
	{
		return (array) BackendModel::getDB()->getRecords('SELECT *
															FROM feedmuncher_comments AS i
															WHERE i.id IN ('. implode(',', $ids) .')');
	}


	/**
	 * Get a count per comment
	 *
	 * @return	array
	 */
	public static function getCommentStatusCount()
	{
		return (array) BackendModel::getDB()->getPairs('SELECT i.status, COUNT(i.id)
															FROM feedmuncher_comments AS i
															WHERE i.language = ?
															GROUP BY i.status',
															array(BL::getWorkingLanguage()));
	}


	/**
	 * Get the latest comments for a given type
	 *
	 * @return	array
	 * @param	string $status					The status for the comments to retrieve.
	 * @param	int[optional] $limit			The maximum number of items to retrieve.
	 */
	public static function getLatestComments($status, $limit = 10)
	{
		// get the comments (order by id, this is faster then on date, the higher the id, the more recent
		$comments = (array) BackendModel::getDB()->getRecords('SELECT i.id, i.author, i.text, UNIX_TIMESTAMP(i.created_on) AS created_in,
																	p.title, p.language, m.url
																FROM feedmuncher_comments AS i
																INNER JOIN feedmuncher_posts AS p ON i.post_id = p.id AND i.language = p.language
																INNER JOIN meta AS m ON p.meta_id = m.id
																WHERE i.status = ? AND p.status = ? AND i.language = ?
																ORDER BY i.id DESC
																LIMIT ?',
																array((string) $status, 'active', BL::getWorkingLanguage(), (int) $limit));

		// loop entries
		foreach($comments as $key => &$row)
		{
			// add full url
			$row['full_url'] = BackendModel::getURLForBlock('feedmuncher', 'detail', $row['language']) .'/'. $row['url'];
		}

		// return
		return $comments;
	}


	/**
	 * Get all data for a given revision
	 *
	 * @return	array
	 * @param	int $id							The id of the feedmuncherpost.
	 * @param	int $revisionId					The revision to get.
	 */
	public static function getRevision($id, $revisionId)
	{
		return (array) BackendModel::getDB()->getRecord('SELECT i.*, UNIX_TIMESTAMP(i.date) AS publish_on, m.url
															FROM feedmuncher_posts AS i
															INNER JOIN meta AS m ON m.id = i.meta_id
															WHERE i.id = ? AND i.revision_id = ?',
															array((int) $id, (int) $revisionId));
	}


	/**
	 * Get all the dates from the publicated articles from a feed
	 *
	 * @return	array
	 * @param	int $feedID		the id of the feed
	 */
	public static function getPublishedDates($feedID)
	{
		return (array) BackendModel::getDB()->getColumn('SELECT date FROM feedmuncher_posts WHERE feed_id = ?', (int) $feedID);
	}


	/**
	 * Get the maximum id
	 *
	 * @return	int
	 */
	public static function getMaximumId()
	{
		return (int) BackendModel::getDB()->getVar('SELECT MAX(id) FROM feedmuncher_posts LIMIT 1');
	}


	/**
	 * Retrieve the unique URL for an item
	 *
	 * @return	string
	 * @param	string $URL					The URL to base on.
	 * @param	int[optional] $itemId		The id of the articepost to ignore.
	 */
	public static function getURL($URL, $itemId = null)
	{
		// redefine URL
		$URL = SpoonFilter::urlise((string) $URL);

		// get db
		$db = BackendModel::getDB();

		// new item
		if($itemId === null)
		{
			// get number of categories with this URL
			$number = (int) $db->getVar('SELECT COUNT(i.id)
											FROM feedmuncher_posts AS i
											INNER JOIN meta AS m ON i.meta_id = m.id
											WHERE i.language = ? AND m.url = ?',
											array(BL::getWorkingLanguage(), $URL));

			// already exists
			if($number != 0)
			{
				// add number
				$URL = BackendModel::addNumber($URL);

				// try again
				return self::getURL($URL);
			}
		}

		// current category should be excluded
		else
		{
			// get number of items with this URL
			$number = (int) $db->getVar('SELECT COUNT(i.id)
											FROM feedmuncher_posts AS i
											INNER JOIN meta AS m ON i.meta_id = m.id
											WHERE i.language = ? AND m.url = ? AND i.id != ?',
											array(BL::getWorkingLanguage(), $URL, $itemId));

			// already exists
			if($number != 0)
			{
				// add number
				$URL = BackendModel::addNumber($URL);

				// try again
				return self::getURL($URL, $itemId);
			}
		}

		// return the unique URL!
		return $URL;
	}


	/**
	 * Retrieve the unique URL for a category
	 *
	 * @return	string
	 * @param	string $URL						The string wheron the URL will be based.
	 * @param	int[optional] $categoryId		The id of the category to ignore.
	 */
	public static function getURLForCategory($URL, $categoryId = null)
	{
		// redefine URL
		$URL = SpoonFilter::urlise((string) $URL);

		// get db
		$db = BackendModel::getDB();

		// new category
		if($categoryId === null)
		{
			// get number of categories with this URL
			$number = (int) $db->getVar('SELECT COUNT(i.id)
											FROM feedmuncher_categories AS i
											WHERE i.language = ? AND i.url = ?',
											array(BL::getWorkingLanguage(), $URL));

			// already exists
			if($number != 0)
			{
				// add number
				$URL = BackendModel::addNumber($URL);

				// try again
				return self::getURLForCategory($URL);
			}
		}

		// current category should be excluded
		else
		{
			// get number of items with this URL
			$number = (int) $db->getVar('SELECT COUNT(i.id)
											FROM feedmuncher_categories AS i
											WHERE i.language = ? AND i.url = ? AND i.id != ?',
											array(BL::getWorkingLanguage(), $URL, $categoryId));

			// already exists
			if($number != 0)
			{
				// add number
				$URL = BackendModel::addNumber($URL);

				// try again
				return self::getURLForCategory($URL, $categoryId);
			}
		}

		// return the unique URL!
		return $URL;
	}


	/**
	 * Inserts a feed into the database
	 *
	 * @return	int
	 * @param	array $item		The data to insert.
	 */
	public static function insert(array $item)
	{
		// insert and return the  id
		return BackendModel::getDB(true)->insert('feedmuncher_feeds', $item);
	}


	/**
	 * Inserts a feed article into the database
	 *
	 * @return	int
	 * @param	array $item		The data to insert.
	 */
	public static function insertArticle(array $item)
	{
		// insert and return the new revision id
		return BackendModel::getDB(true)->insert('feedmuncher_posts', $item);
	}


	/**
	 * Inserts a meta
	 *
	 * @return	int
	 * @param	string $title	the title for the meta
	 */
	public static function insertMeta($title)
	{
		// build the meta item
		$item['keywords'] = $title;
		$item['description'] = $title;
		$item['title'] = $title;
		$item['url'] = self::getURL(SpoonFilter::urlise($title));

		// insert in db and return id
		return (int) BackendModel::getDB(true)->insert('meta', $item);
	}




	/**
	 * Inserts a meta for a blogpost
	 *
	 * @return	int
	 * @param	string $title	the title for the meta
	 * @param	string $url		the url for the meta
	 */
	public static function insertMetaForBlog($title, $url)
	{
		// build the meta item
		$item['keywords'] = $title;
		$item['description'] = $title;
		$item['title'] = $title;
		$item['url'] = $url;

		// insert in db and return id
		return (int) BackendModel::getDB(true)->insert('meta', $item);
	}


	/**
	 * Inserts a new category into the database
	 *
	 * @return	int
	 * @param	array $item						The data for the category to insert.
	 */
	public static function insertCategory(array $item)
	{
		// create category
		$item['id'] = BackendModel::getDB(true)->insert('feedmuncher_categories', $item);

		// invalidate the cache for feedmuncher
		BackendModel::invalidateFrontendCache('feedmuncher', BL::getWorkingLanguage());

		// return the id
		return $item['id'];
	}


	/**
	 * sets the article published
	 *
	 * @return	int					number of affected rows
	 * @param	int $id				the id of of the article to publish
	 */
	public static function publishArticle($id)
	{
		return BackendModel::getDB(true)->update('feedmuncher_posts', array('hidden' => 'N'), 'id = ?', (int) $id);
	}


	public static function publishArticles($ids)
	{
		foreach($ids as $id)
		{
			// set published (not hidden)
			if(self::publishArticle($id) != 0)
			{
				// get the article
				$record = self::getArticle($id);

				// should the article be posted in the blog module?
				if($record['target'] == 'blog')
				{
					// require the blog model
					require_once PATH_WWW . '/backend/modules/blog/engine/model.php';

					// create item to insert in the blog posts
					$item = $record;
					$item['id'] = BackendBlogModel::getMaximumId() + 1;
					$item['hidden'] = 'N';
					$item['publish_on'] = $record['date'];
					$item['meta_id'] = BackendFeedmuncherModel::insertMetaForBlog($record['title'], BackendBlogModel::getURL($record['title']));

					// unset the keys that don't exist for blog
					unset($item['date'], $item['target'], $item['feed_id'], $item['deleted'], $item['target'], $item['blog_post_id'], $item['url']);

					// insert in db
					BackendBlogModel::insert($item);

					// save the blog post id in the feedmuncher post
					BackendFeedmuncherModel::setBlogPostsId($id, $item['id']);
				}
			}
		}
	}


	/**
	 * Recalculate the commentcount
	 *
	 * @return	bool
	 * @param	array $ids		The id(s) of the post wherefor the commentcount should be recalculated.
	 */
	public static function reCalculateCommentCount($ids)
	{
		// make sure $ids is an array
		$ids = (array) $ids;

		// validate
		if(!$ids) return false;

		// make unique ids
		$ids = array_unique($ids);

		// get db
		$db = BackendModel::getDB(true);

		// get counts
		$commentCounts = (array) $db->getPairs('SELECT i.post_id, COUNT(i.id) AS comment_count
												FROM feedmuncher_comments AS i
												INNER JOIN feedmuncher_posts AS p ON i.post_id = p.id AND i.language = p.language
												WHERE i.status = ? AND i.post_id IN ('. implode(',', $ids) .') AND i.language = ? AND p.status = ?
												GROUP BY i.post_id',
												array('published', BL::getWorkingLanguage(), 'active'));

		// loop posts
		foreach($ids as $id)
		{
			// get count
			$count = (isset($commentCounts[$id])) ? (int) $commentCounts[$id] : 0;

			// update
			$db->update('feedmuncher_posts', array('num_comments' => $count), 'id = ? AND language = ?', array($id, BL::getWorkingLanguage()));
		}

		return true;
	}


	/**
	 * sets the blog post id for a feedmuncher posts (that is posted in the blog)
	 *
	 * @return	int					number of affected rows
	 * @param	int $id				the id of the feedmuncher posts to update
	 * @param	int $blog_posts_id	the blog post id
	 */
	public static function setBlogPostsId($id, $blogPostId)
	{
		return BackendModel::getDB(true)->update('feedmuncher_posts', array('blog_post_id' => $blogPostId), 'id = ?', (int) $id);
	}


	/**
	 * updates a feed
	 *
	 * @return	int				number of affected rows
	 * @param	int $id			the id of the feed to update
	 * @param	array $item		The data to insert.
	 */
	public static function update($id, array $item)
	{
		return BackendModel::getDB(true)->update('feedmuncher_feeds', $item, 'id = ?', (int) $id);
	}


	/**
	 * Update an existing article
	 *
	 * @return	int
	 * @param	array $item		The new data.
	 */
	public static function updateArticle(array $item)
	{
		// check if new version is active
		if($item['status'] == 'active')
		{
			// archive all older active versions
			BackendModel::getDB(true)->update('feedmuncher_posts', array('status' => 'archived'), 'id = ? AND status = ?', array($item['id'], $item['status']));

			// get the record of the exact item we're editing
			$revision = self::getRevision($item['id'], $item['revision_id']);

			// if it used to be a draft that we're now publishing, remove drafts
			if($revision['status'] == 'draft') BackendModel::getDB(true)->delete('feedmuncher_posts', 'id = ? AND status = ?', array($item['id'], $revision['status']));
		}

		// don't want revision id
		unset($item['revision_id']);

		// how many revisions should we keep
		$rowsToKeep = (int) BackendModel::getModuleSetting('feedmuncher', 'max_num_revisions', 20);

		// set type of archive
		$archiveType = ($item['status'] == 'active' ? 'archived' : $item['status']);

		// get revision-ids for items to keep
		$revisionIdsToKeep = (array) BackendModel::getDB()->getColumn('SELECT i.revision_id
																		 FROM feedmuncher_posts AS i
																		 WHERE i.id = ? AND i.status = ? AND i.language = ?
																		 ORDER BY i.date DESC
																		 LIMIT ?',
																		 array($item['id'], $archiveType, BL::getWorkingLanguage(), $rowsToKeep));

		// delete other revisions
		if(!empty($revisionIdsToKeep)) BackendModel::getDB(true)->delete('feedmuncher_posts', 'id = ? AND status = ? AND revision_id NOT IN ('. implode(', ', $revisionIdsToKeep) .')', array($item['id'], $archiveType));

		// insert new version
		$item['revision_id'] = BackendModel::getDB(true)->insert('feedmuncher_posts', $item);

		// invalidate the cache for feedmuncher
		BackendModel::invalidateFrontendCache('feedmuncher', BL::getWorkingLanguage());

		// return the new revision id
		return $item['revision_id'];
	}


	/**
	 * Update an existing category
	 *
	 * @return	int
	 * @param	array $item		The new data.
	 */
	public static function updateCategory(array $item)
	{
		// update category
		$updated = BackendModel::getDB(true)->update('feedmuncher_categories', $item, 'id = ?', array((int) $item['id']));

		// invalidate the cache for feedmuncher
		BackendModel::invalidateFrontendCache('feedmuncher', BL::getWorkingLanguage());

		// return
		return $updated;
	}


	/**
	 * Update an existing comment
	 *
	 * @return	int
	 * @param	array $item			The new data.
	 */
	public static function updateComment(array $item)
	{
		// update category
		return BackendModel::getDB(true)->update('feedmuncher_comments', $item, 'id = ?', array((int) $item['id']));
	}


	/**
	 * Updates one or more comments' status
	 *
	 * @return	void
	 * @param	array $ids						The id(s) of the comment(s) to change the status for.
	 * @param	string $status					The new status.
	 */
	public static function updateCommentStatuses($ids, $status)
	{
		// make sure $ids is an array
		$ids = (array) $ids;

		// get feedmuncherpost ids
		$postIds = (array) BackendModel::getDB()->getColumn('SELECT i.post_id
																FROM feedmuncher_comments AS i
																WHERE i.id IN ('. implode(',', $ids) .')');

		// update record
		BackendModel::getDB(true)->execute('UPDATE feedmuncher_comments
											SET status = ?
											WHERE id IN ('. implode(',', $ids) .')',
											array((string) $status));

		// recalculate the comment count
		if(!empty($postIds)) self::reCalculateCommentCount($postIds);

		// invalidate the cache for feedmuncher
		BackendModel::invalidateFrontendCache('feedmuncher', BL::getWorkingLanguage());
	}

}