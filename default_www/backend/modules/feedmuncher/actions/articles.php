<?php

/**
 * This is the index-action (default), it will display the overview of feedmuncher articles
 *
 * @package		backend
 * @subpackage	feedmuncher
 *
 * @author		Lowie Benoot <lowiebenoot@netlash.com>
 * @since		2.1
 */
class BackendFeedmuncherArticles extends BackendBaseActionIndex
{
	/**
	 * The blog category where is filtered on
	 *
	 * @var	array
	 */
	private $blogCategory;


	/**
	 * The id of the blog category where is filtered on
	 *
	 * @var	int
	 */
	private $blogCategoryId;


	/**
	 * Datagrids
	 *
	 * @var	SpoonDataGrid
	 */
	private $dgDrafts, $dgFeedmuncherPosts, $dgBlogPosts, $dgNotPublishedBlog, $dgNotPublished;


	/**
	 * The feedmuncher category where is filtered on
	 *
	 * @var	array
	 */
	private $feedmuncherCategory;


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
		// call parent, this will probably add some general CSS/JS or other required files
		parent::execute();

		// is the blog installed?
		$this->blogIsInstalled = BackendFeedmuncherModel::blogIsInstalled();

		// set filter
		$this->setFilter();

		// load datagrid
		$this->loadDataGrids();

		// parse page
		$this->parse();

		// display the page
		$this->display();
	}


	/**
	 * Loads the datagrid with all the blog posts
	 *
	 * @return	void
	 */
	private function loadDatagridAllBlogPosts()
	{
		// check for deleted blog posts
		BackendFeedmuncherModel::checkForDeletedBlogPosts();

		// filter on category?
		if($this->blogCategoryId != null)
		{
			// create datagrid
			$this->dgBlogPosts = new BackendDataGridDB(BackendFeedmuncherModel::QRY_DATAGRID_BROWSE_ARTICLES_FOR_CATEGORY, array($this->blogCategoryId, 'active', BL::getWorkingLanguage(), 'N', 'blog', 'N'));

			// set the URL
			$this->dgBlogPosts->setURL('&amp;blogCategory=' . $this->blogCategoryId, true);
		}

		else
		{
			// create datagrid
			$this->dgBlogPosts = new BackendDataGridDB(BackendFeedmuncherModel::QRY_DATAGRID_BROWSE_ARTICLES, array('active', BL::getWorkingLanguage(), 'N', 'blog', 'N'));
		}

		// set headers
		$this->dgBlogPosts->setHeaderLabels(array('publish_on' => ucfirst(BL::lbl('PublishedOn'))));

		// hide columns
		$this->dgBlogPosts->setColumnsHidden(array('revision_id', 'feed_id', 'hidden', 'created_on', 'blog_post_id'));

		// sorting columns
		$this->dgBlogPosts->setSortingColumns(array('publish_on', 'title', 'author', 'comments'), 'created_on');
		$this->dgBlogPosts->setSortParameter('desc');

		// set colum URLs
		$this->dgBlogPosts->setColumnURL('title', BackendModel::createURLForAction('edit', 'blog') . '&amp;id=[blog_post_id]');
		$this->dgBlogPosts->setColumnURL('feed', BackendModel::createURLForAction('edit') . '&amp;id=[feed_id]');

		// set column functions
		$this->dgBlogPosts->setColumnFunction(array('BackendDatagridFunctions', 'getUser'), array('[author]'), 'author', true);
		$this->dgBlogPosts->setColumnFunction(array('BackendDatagridFunctions', 'getLongDate'), array('[publish_on]'), 'publish_on', true);

		// add edit column
		$this->dgBlogPosts->addColumn('edit', null, BL::lbl('Edit'), BackendModel::createURLForAction('edit', 'blog') . '&amp;id=[blog_post_id]', BL::lbl('Edit'));

		// our JS needs to know an id, so we can highlight it
		$this->dgBlogPosts->setRowAttributes(array('id' => 'row-[revision_id]'));

		// set active tab
		$this->dgBlogPosts->setActiveTab('tabBlog');
	}


	/**
	 * Loads the datagrid with all the posts
	 *
	 * @return	void
	 */
	private function loadDatagridAllFeedmuncherPosts()
	{
		// filter on category?
		if($this->feedmuncherCategoryId != null)
		{
			// create datagrid
			$this->dgFeedmuncherPosts = new BackendDataGridDB(BackendFeedmuncherModel::QRY_DATAGRID_BROWSE_ARTICLES_FOR_CATEGORY, array($this->feedmuncherCategoryId, 'active', BL::getWorkingLanguage(), 'N', 'feedmuncher', 'N'));

			// set the URL
			$this->dgFeedmuncherPosts->setURL('&amp;feedmuncherategory=' . $this->feedmuncherCategoryId, true);
		}

		else
		{
			// create datagrid
			$this->dgFeedmuncherPosts = new BackendDataGridDB(BackendFeedmuncherModel::QRY_DATAGRID_BROWSE_ARTICLES, array('active', BL::getWorkingLanguage(), 'N', 'feedmuncher', 'N'));
		}

		// set headers
		$this->dgFeedmuncherPosts->setHeaderLabels(array('publish_on' => ucfirst(BL::lbl('PublishedOn'))));

		// hide columns
		$this->dgFeedmuncherPosts->setColumnsHidden(array('revision_id', 'feed_id', 'hidden', 'created_on', 'blog_post_id'));

		// sorting columns
		$this->dgFeedmuncherPosts->setSortingColumns(array('publish_on', 'title', 'author', 'comments'), 'created_on');
		$this->dgFeedmuncherPosts->setSortParameter('desc');

		// set colum URLs
		$this->dgFeedmuncherPosts->setColumnURL('title', BackendModel::createURLForAction('edit_article') . '&amp;id=[id]&feedmuncherCategory=' . $this->feedmuncherCategoryId . ($this->blogIsInstalled ? '&blogCategory=' . $this->blogCategoryId : ''));
		$this->dgFeedmuncherPosts->setColumnURL('feed', BackendModel::createURLForAction('edit') . '&amp;id=[feed_id]');

		// set column functions
		$this->dgFeedmuncherPosts->setColumnFunction(array('BackendDatagridFunctions', 'getUser'), array('[author]'), 'author', true);
		$this->dgFeedmuncherPosts->setColumnFunction(array('BackendDatagridFunctions', 'getLongDate'), array('[publish_on]'), 'publish_on', true);

		// add edit column
		$this->dgFeedmuncherPosts->addColumn('edit', null, BL::lbl('Edit'), BackendModel::createURLForAction('edit_article') . '&amp;id=[id]&feedmuncherCategory=' . $this->feedmuncherCategoryId . ($this->blogIsInstalled ? '&blogCategory=' . $this->blogCategoryId : ''), BL::lbl('Edit'));

		// our JS needs to know an id, so we can highlight it
		$this->dgFeedmuncherPosts->setRowAttributes(array('id' => 'row-[revision_id]'));

		// set active tab
		$this->dgFeedmuncherPosts->setActiveTab('tabFeedmuncher');
	}


	/**
	 * Loads the datagrid with all the drafts
	 *
	 * @return	void
	 */
	private function loadDatagridDrafts()
	{
		// create datagrid
		$this->dgDrafts = new BackendDataGridDB(BackendFeedmuncherModel::QRY_DATAGRID_BROWSE_DRAFTS, array('draft', BackendAuthentication::getUser()->getUserId(), BL::getWorkingLanguage(), 'N'));

		// hide columns
		$this->dgDrafts->setColumnsHidden(array('revision_id', 'feed_id', 'hidden', 'created_on'));

		// sorting columns
		$this->dgDrafts->setSortingColumns(array('edited_on', 'title', 'author', 'comments'), 'created_on');
		$this->dgDrafts->setSortParameter('desc');

		// set colum URLs
		$this->dgDrafts->setColumnURL('title', BackendModel::createURLForAction('edit_article') . '&amp;id=[id]&amp;draft=[revision_id]');

		// set column functions
		$this->dgDrafts->setColumnFunction(array('BackendDatagridFunctions', 'getUser'), array('[author]'), 'author', true);
		$this->dgDrafts->setColumnFunction(array('BackendDatagridFunctions', 'getLongDate'), array('[edited_on]'), 'edited_on', true);

		// add edit column
		$this->dgDrafts->addColumn('edit', null, BL::lbl('Edit'), BackendModel::createURLForAction('edit_article') . '&amp;id=[id]&amp;draft=[revision_id]', BL::lbl('Edit'));

		// our JS needs to know an id, so we can highlight it
		$this->dgDrafts->setRowAttributes(array('id' => 'row-[revision_id]'));

		// set active tab
		$this->dgDrafts->setActiveTab('tabDrafts');
	}


	/**
	 * Loads the datagrid with not published articles
	 *
	 * @return	void
	 */
	private function loadDatagridNotPublished()
	{
		// create datagrid
		$this->dgNotPublished = new BackendDataGridDB(BackendFeedmuncherModel::QRY_DATAGRID_BROWSE_ARTICLES_NOT_PUBLISHED, array('active', BL::getWorkingLanguage(), 'N', 'Y'));

		// set headers
		$this->dgNotPublished->setHeaderLabels(array('publish_on' => ucfirst(BL::lbl('PublishedOn'))));

		// hide columns
		$this->dgNotPublished->setColumnsHidden(array('revision_id', 'feed_id', 'hidden', 'created_on', 'publish_on'));

		// sorting columns
		$this->dgNotPublished->setSortingColumns(array('publish_on', 'title', 'author'), 'publish_on');
		$this->dgNotPublished->setSortParameter('desc');

		// set colum URLs
		$this->dgNotPublished->setColumnURL('title', BackendModel::createURLForAction('edit_article') . '&amp;id=[id]&feedmuncherCategory=' . $this->feedmuncherCategoryId . ($this->blogIsInstalled ? '&blogCategory=' . $this->blogCategoryId : ''));
		$this->dgNotPublished->setColumnURL('feed', BackendModel::createURLForAction('edit') . '&amp;id=[feed_id]');

		// set column functions
		$this->dgNotPublished->setColumnFunction(array('BackendDatagridFunctions', 'getUser'), array('[author]'), 'author', true);
		$this->dgNotPublished->setColumnFunction(array('BackendDatagridFunctions', 'getLongDate'), array('[publish_on]'), 'publish_on', true);

		// add edit column
		$this->dgNotPublished->addColumn('edit', null, BL::lbl('Edit'), BackendModel::createURLForAction('edit_article') . '&amp;id=[id]&feedmuncherCategory=' . $this->feedmuncherCategoryId . ($this->blogIsInstalled ? '&blogCategory=' . $this->blogCategoryId : ''), BL::lbl('Edit'));

		// add the multicheckbox column
		$this->dgNotPublished->setMassActionCheckboxes('checkbox', '[id]');

		// add mass action dropdown
		$ddmMassAction = new SpoonFormDropdown('action', array('publish' => BL::lbl('Publish'), 'delete' => BL::lbl('Delete')), 'publish');
		$this->dgNotPublished->setMassAction($ddmMassAction);

		// our JS needs to know an id, so we can highlight it
		$this->dgNotPublished->setRowAttributes(array('id' => 'row-[revision_id]'));

		// set active tab
		$this->dgNotPublished->setActiveTab('tabNotPublished');
	}


	/**
	 * Loads the datagrids for the feedmuncher posts
	 *
	 * @return	void
	 */
	private function loadDataGrids()
	{
		// all feedmuncher posts
		$this->loadDatagridAllFeedmuncherPosts();

		// feedmuncher drafts
		$this->loadDatagridDrafts();

		// load blogposts if blog is installed
		if($this->blogIsInstalled) $this->loadDatagridAllBlogPosts();

		// load not published articles
		$this->loadDatagridNotPublished();
	}


	/**
	 * Parse all datagrids
	 *
	 * @return	void
	 */
	private function parse()
	{
		// parse the datagrid for the feedmuncher drafts
		$this->tpl->assign('dgDrafts', ($this->dgDrafts->getNumResults() != 0) ? $this->dgDrafts->getContent() : false);

		// parse the datagrid for all feedmuncher posts
		$this->tpl->assign('dgFeedmuncherPosts', ($this->dgFeedmuncherPosts->getNumResults() != 0) ? $this->dgFeedmuncherPosts->getContent() : false);

		// parse the datagrid for all blog posts
		if($this->blogIsInstalled) $this->tpl->assign('dgBlogPosts', ($this->dgBlogPosts->getNumResults() != 0) ? $this->dgBlogPosts->getContent() : false);

		// parse the datagrids for the not published articles
		$this->tpl->assign('dgNotPublished', ($this->dgNotPublished->getNumResults() != 0) ? $this->dgNotPublished->getContent() : false);

		// assign whether blog is installed or not
		$this->tpl->assign('blogIsInstalled', $this->blogIsInstalled);

		// assign the number of items of each datagrid in the tabs
		$this->tpl->assign('numNotPublished', $this->dgNotPublished->getNumResults());
		$this->tpl->assign('numPublishedInFeedmuncher', $this->dgFeedmuncherPosts->getNumResults());
		if($this->blogIsInstalled) $this->tpl->assign('numPublishedInBlog', $this->dgBlogPosts->getNumResults());
		$this->tpl->assign('numDrafts', $this->dgDrafts->getNumResults());

		// get categories
		$feedmuncherCategories = BackendFeedmuncherModel::getCategories(true);
		if($this->blogIsInstalled) $blogCategories = BackendFeedmuncherModel::getCategoriesFromBlog(true);

		// create a filter form
		$frm = new BackendForm('filter', null, 'get', false);

		// multiple feedmuncher categories?
		if(count($feedmuncherCategories) > 1)
		{
			// create element
			$frm->addDropdown('feedmuncherCategory', $feedmuncherCategories, $this->feedmuncherCategoryId);
			$frm->getField('feedmuncherCategory')->setDefaultElement('');
		}

		if($this->blogIsInstalled)
		{
			// multiple blog categories?
			if(count($blogCategories) > 1)
			{
				// create element
				$frm->addDropdown('blogCategory', $blogCategories, $this->blogCategoryId);
				$frm->getField('blogCategory')->setDefaultElement('');
			}
		}

		// parse form
		$frm->parse($this->tpl);
	}

	/**
	 * Set category filters
	 *
	 * @return	void
	 */
	private function setFilter()
	{
		// set feedmuncher category id
		$this->feedmuncherCategoryId = SpoonFilter::getGetValue('feedmuncherCategory', null, null, 'int');
		if($this->feedmuncherCategoryId == 0) $this->feedmuncherCategoryId = null;
		else
		{
			// get category
			$this->feedmuncherCategory = BackendFeedmuncherModel::getCategory($this->feedmuncherCategoryId);

			// reset
			if(empty($this->feedmuncherCategory))
			{
				// reset GET to trick Spoon
				$_GET['feedmuncherCategory'] = null;

				// reset
				$this->feedmuncherCategory = null;
			}
		}

		// is blog installed?
		if($this->blogIsInstalled)
		{
			// set blog category id
			$this->blogCategoryId = SpoonFilter::getGetValue('blogCategory', null, null, 'int');
			if($this->blogCategoryId == 0) $this->blogCategoryId = null;
			else
			{
				// get category
				$this->blogCategory = BackendFeedmuncherModel::getCategoriesFromBlog($this->blogCategoryId);

				// reset
				if(empty($this->blogCategory))
				{
					// reset GET to trick Spoon
					$_GET['blogCategory'] = null;

					// reset
					$this->blogCategory = null;
				}
			}
		}
	}
}

?>