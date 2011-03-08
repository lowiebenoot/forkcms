<?php

/**
 * This edit-action will publish an article using Ajax
 *
 * @package		backend
 * @subpackage	feedmuncher
 *
 * @author		Lowie Benoot <lowiebenoot@netlash.com>
 * @since		2.0
 */
class BackendFeedmuncherAjaxPublishArticle extends BackendBaseAJAXAction
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

		// get parameters
		$id = SpoonFilter::getPostValue('articleId', null, 0, 'int');

		// validate
		if($id === 0) $this->output(self::BAD_REQUEST, null, 'no id provided');

		// set published (not hidden)
		if(BackendFeedmuncherModel::publishArticle($id) == 0)
		{
			// get the article
			$record = BackendFeedmuncherModel::getArticle($id);

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

			// output
			$this->output(self::OK, null, sprintf(BL::getMessage('PublishedArticle', 'feedmuncher'), $record['title']));
		}

		else $this->output(self::ERROR, null, BL::getError('SomethingWentWrong'));
	}
}

?>