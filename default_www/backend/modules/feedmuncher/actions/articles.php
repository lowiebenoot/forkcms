<?php

/**
 * This is the index-action (default), it will display the overview of feedmuncher articles
 *
 * @package		backend
 * @subpackage	feedmuncher
 *
 * @author		Davy Hellemans <davy@netlash.com>
 * @author		Dave Lens <dave@netlash.com>
 * @author		Tijs Verkoyen <tijs@sumocoders.com>
 * @author		Matthias Mullie <matthias@netlash.com>
 * @author		Lowie Benoot <lowiebenoot@netlash.com>
 * @since		2.0
 */
class BackendFeedmuncherArticles extends BackendBaseActionIndex
{
	/**
	 * Datagrids
	 *
	 * @var	SpoonDataGrid
	 */
	private $dgFeedmuncherDrafts, $dgFeedmuncherPosts, $dgBlogDrafts, $dgBlogPosts, $dgNotPublishedBlog, $dgNotPublished;


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

		// create datagrid
		$this->dgBlogPosts = new BackendDataGridDB(BackendFeedmuncherModel::QRY_DATAGRID_BROWSE_ARTICLES, array('active', BL::getWorkingLanguage(), 'N', 'blog', 'N'));

		// set headers
		$this->dgBlogPosts->setHeaderLabels(array('publish_on' => ucfirst(BL::lbl('PublishedOn'))));

		// hide columns
		$this->dgBlogPosts->setColumnsHidden(array('revision_id', 'feed_id', 'hidden', 'created_on', 'blog_post_id'));

		// sorting columns
		$this->dgBlogPosts->setSortingColumns(array('publish_on', 'title', 'author', 'comments'), 'created_on');
		$this->dgBlogPosts->setSortParameter('desc');

		// set colum URLs
		$this->dgBlogPosts->setColumnURL('title', BackendModel::createURLForAction('edit_article') .'&amp;id=[id]');
		$this->dgBlogPosts->setColumnURL('feed', BackendModel::createURLForAction('edit') .'&amp;id=[feed_id]');

		// set column functions
		$this->dgBlogPosts->setColumnFunction(array('BackendDatagridFunctions', 'getUser'), array('[author]'), 'author', true);
		$this->dgBlogPosts->setColumnFunction(array('BackendDatagridFunctions', 'getLongDate'), array('[publish_on]'), 'publish_on', true);

		// add edit column
		$this->dgBlogPosts->addColumn('edit', null, BL::lbl('Edit'), BackendModel::createURLForAction('edit', 'blog') .'&amp;id=[blog_post_id]', BL::lbl('Edit'));

		// our JS needs to know an id, so we can highlight it
		$this->dgBlogPosts->setRowAttributes(array('id' => 'row-[revision_id]'));
	}


	/**
	 * Loads the datagrid with all the posts
	 *
	 * @return	void
	 */
	private function loadDatagridAllFeedmuncherPosts()
	{
		// create datagrid
		$this->dgFeedmuncherPosts = new BackendDataGridDB(BackendFeedmuncherModel::QRY_DATAGRID_BROWSE_ARTICLES, array('active', BL::getWorkingLanguage(), 'N', 'feedmuncher', 'N'));

		// set headers
		$this->dgFeedmuncherPosts->setHeaderLabels(array('publish_on' => ucfirst(BL::lbl('PublishedOn'))));

		// hide columns
		$this->dgFeedmuncherPosts->setColumnsHidden(array('revision_id', 'feed_id', 'hidden', 'created_on', 'blog_post_id'));

		// sorting columns
		$this->dgFeedmuncherPosts->setSortingColumns(array('publish_on', 'title', 'author', 'comments'), 'created_on');
		$this->dgFeedmuncherPosts->setSortParameter('desc');

		// set colum URLs
		$this->dgFeedmuncherPosts->setColumnURL('title', BackendModel::createURLForAction('edit_article') .'&amp;id=[id]');
		$this->dgFeedmuncherPosts->setColumnURL('feed', BackendModel::createURLForAction('edit') .'&amp;id=[feed_id]');

		// set column functions
		$this->dgFeedmuncherPosts->setColumnFunction(array('BackendDatagridFunctions', 'getUser'), array('[author]'), 'author', true);
		$this->dgFeedmuncherPosts->setColumnFunction(array('BackendDatagridFunctions', 'getLongDate'), array('[publish_on]'), 'publish_on', true);

		// add edit column
		$this->dgFeedmuncherPosts->addColumn('edit', null, BL::lbl('Edit'), BackendModel::createURLForAction('edit_article') .'&amp;id=[id]', BL::lbl('Edit'));

		// our JS needs to know an id, so we can highlight it
		$this->dgFeedmuncherPosts->setRowAttributes(array('id' => 'row-[revision_id]'));
	}


	/**
	 * Loads the datagrid with all the drafts
	 *
	 * @return	void
	 */
	private function loadDatagridFeedmuncherDrafts()
	{
		// create datagrid
		$this->dgFeedmuncherDrafts = new BackendDataGridDB(BackendFeedmuncherModel::QRY_DATAGRID_BROWSE_DRAFTS, array('draft', BackendAuthentication::getUser()->getUserId(), BL::getWorkingLanguage(), 'N', 'feedmuncher'));

		// hide columns
		$this->dgFeedmuncherDrafts->setColumnsHidden(array('revision_id', 'feed_id', 'hidden', 'created_on', 'blog_post_id'));

		// sorting columns
		$this->dgFeedmuncherDrafts->setSortingColumns(array('edited_on', 'title', 'author', 'comments'), 'created_on');
		$this->dgFeedmuncherDrafts->setSortParameter('desc');

		// set colum URLs
		$this->dgFeedmuncherDrafts->setColumnURL('title', BackendModel::createURLForAction('edit_article') .'&amp;id=[id]&amp;draft=[revision_id]');

		// set column functions
		$this->dgFeedmuncherDrafts->setColumnFunction(array('BackendDatagridFunctions', 'getUser'), array('[author]'), 'author', true);
		$this->dgFeedmuncherDrafts->setColumnFunction(array('BackendDatagridFunctions', 'getLongDate'), array('[publish_on]'), 'publish_on', true);

		// add edit column
		$this->dgFeedmuncherDrafts->addColumn('edit', null, BL::lbl('Edit'), BackendModel::createURLForAction('edit_article') .'&amp;id=[id]&amp;draft=[revision_id]', BL::lbl('Edit'));

		// our JS needs to know an id, so we can highlight it
		$this->dgFeedmuncherDrafts->setRowAttributes(array('id' => 'row-[revision_id]'));
	}


	/**
	 * Loads the datagrid with not published articles for the blog
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
		$this->dgNotPublished->setSortingColumns(array('publish_on', 'title', 'author'), 'created_on');
		$this->dgNotPublished->setSortParameter('desc');

		// set colum URLs
		$this->dgNotPublished->setColumnURL('title', BackendModel::createURLForAction('edit_article') .'&amp;id=[id]');
		$this->dgNotPublished->setColumnURL('feed', BackendModel::createURLForAction('edit') .'&amp;id=[feed_id]');

		// set column functions
		$this->dgNotPublished->setColumnFunction(array('BackendDatagridFunctions', 'getUser'), array('[author]'), 'author', true);
		$this->dgNotPublished->setColumnFunction(array('BackendDatagridFunctions', 'getLongDate'), array('[publish_on]'), 'publish_on', true);

		// add edit column
		$this->dgNotPublished->addColumn('edit', null, BL::lbl('Edit'), BackendModel::createURLForAction('edit_article') .'&amp;id=[id]', BL::lbl('Edit'));

		// add the multicheckbox column
		$this->dgNotPublished->setMassActionCheckboxes('checkbox', '[id]');

		// add mass action dropdown
		$ddmMassAction = new SpoonFormDropdown('action', array('publish' => BL::lbl('Publish'), 'delete' => BL::lbl('Delete')), 'publish');
		$this->dgNotPublished->setMassAction($ddmMassAction);

		// our JS needs to know an id, so we can highlight it
		$this->dgNotPublished->setRowAttributes(array('id' => 'row-[revision_id]'));
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
		$this->loadDatagridFeedmuncherDrafts();

		// all blog posts
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
		$this->tpl->assign('dgFeedmuncherDrafts', ($this->dgFeedmuncherDrafts->getNumResults() != 0) ? $this->dgFeedmuncherDrafts->getContent() : false);

		// parse the datagrid for all feedmuncher posts
		$this->tpl->assign('dgFeedmuncherPosts', ($this->dgFeedmuncherPosts->getNumResults() != 0) ? $this->dgFeedmuncherPosts->getContent() : false);

		// parse the datagrid for all blog posts
		if($this->blogIsInstalled) $this->tpl->assign('dgBlogPosts', ($this->dgBlogPosts->getNumResults() != 0) ? $this->dgBlogPosts->getContent() : false);

		// parse the datagrids for the not published articles
		$this->tpl->assign('dgNotPublished', ($this->dgNotPublished->getNumResults() != 0) ? $this->dgNotPublished->getContent() : false);

		// assign whether blog is installed or not
		$this->tpl->assign('blogIsInstalled', $this->blogIsInstalled);
	}
}

?>