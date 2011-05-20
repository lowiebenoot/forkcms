<?php

/**
 * This action will delete a post
 *
 * @package		backend
 * @subpackage	feedmuncher
 *
 * @author		Lowie Benoot <lowiebenoot@netlash.com>
 * @since		2.1
 */
class BackendFeedmuncherDeleteArticle extends BackendBaseActionDelete
{
	/**
	 * The id of the blog category where is filtered on
	 *
	 * @var	int
	 */
	private $blogCategoryId;


	/**
	 * Datagrid for the drafts
	 *
	 * @var	BackendDatagrid
	 */
	private $dgDrafts;


	/**
	 * The id of the feedmuncher category where is filtered on
	 *
	 * @var	int
	 */
	private $feedmuncherCategoryId;


	/**
	 * Execute the action
	 *
	 * @return	void
	 */
	public function execute()
	{
		// get parameters
		$this->id = $this->getParameter('id', 'int');

		// does the item exist
		if($this->id !== null && BackendFeedmuncherModel::existsArticle($this->id))
		{
			// call parent, this will probably add some general CSS/JS or other required files
			parent::execute();

			// set filter category ids
			$this->blogCategoryId = SpoonFilter::getGetValue('blogCategory', null, null, 'int');
			if($this->blogCategoryId == 0) $this->categoryId = null;
			$this->feedmuncherCategoryId = SpoonFilter::getGetValue('feedmuncherCategory', null, null, 'int');
			if($this->feedmuncherCategoryId == 0) $this->feedmuncherCategoryId = null;

			// get data
			$this->record = (array) BackendFeedmuncherModel::getArticle($this->id);

			// delete item
			BackendFeedmuncherModel::deleteArticle($this->id);

			// build redirect URL
			$redirectUrl = BackendModel::createURLForAction('articles') . '&report=deleted&var=' . urlencode($this->record['title']);

			// append to redirect URL
			if($this->feedmuncherCategoryId != null) $redirectUrl .= '&feedmuncherCategory=' . $this->feedmuncherCategoryId;
			if($this->blogCategoryId != null) $redirectUrl .= '&blogCategory=' . $this->blogCategoryId;

			// item was deleted, so redirect
			$this->redirect($redirectUrl);
		}

		// something went wrong
		else $this->redirect(BackendModel::createURLForAction('articles') . '&error=non-existing');
	}
}

?>