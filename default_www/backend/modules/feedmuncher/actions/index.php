<?php

/**
 * This is the index-action (default), it will display the overview of the feeds
 *
 * @package		backend
 * @subpackage	feedmuncher
 *
 * @author		Lowie Benoot <lowiebenoot@netlash.com>
 * @since		2.1
 */
class BackendFeedmuncherIndex extends BackendBaseActionIndex
{
	/**
	 * The datagrids
	 *
	 * @var	array
	 */
	private $dgFeeds;


	/**
	 * Execute the action
	 *
	 * @return	void
	 */
	public function execute()
	{
		// call parent, this will probably add some general CSS/JS or other required files
		parent::execute();

		// load the datagrids
		$this->loadDatagrids();

		// parse the datagrids
		$this->parse();

		// display the page
		$this->display();
	}


	/**
	 * get the target category to use in the datagrid
	 * example: 'blog - default'
	 *
	 * @return	string
	 * @param	string $target		the target module.
	 * @param	int	$category_id	the category id.
	 */
	public static function getCategory($target, $category_id)
	{
		// get category name
		$category = ($target == 'feedmuncher') ? BackendFeedmuncherModel::getCategory((int) $category_id) : BackendFeedmuncherModel::getCategoryFromBlog((int) $category_id);

		// is the blog module installed?
		if(BackendFeedmuncherModel::blogIsInstalled())
		{
			// return a string: target - category url
			return $target . ' - <a href="' . BackendModel::createURLForAction('edit_category', $target) . '&amp;id=' . $category_id . '">' . $category['name'] . '</a>';
		}

		// blog module not installed, just return the category
		else return '<a href="' . BackendModel::createURLForAction('edit_category', $target) . '&amp;id=' . $category_id . '">' . $category['name'] . '</a>';
	}


	/**
	 * Load the datagrids
	 *
	 * @return	void
	 */
	private function loadDatagrids()
	{
		// create feeds datagrid
		$this->dgFeeds = new BackendDataGridDB(BackendFeedmuncherModel::QRY_DATAGRID_BROWSE_FEEDS, array(BL::getWorkingLanguage(), 'N'));

		// set hidden columns
		$this->dgFeeds->setColumnHidden('target');

		// set paging
		$this->dgFeeds->setPaging(false);

		// set colum URLs
		$this->dgFeeds->setColumnURL('name', BackendModel::createURLForAction('edit') .'&amp;id=[id]');
		$this->dgFeeds->setColumnURL('source', '[source]');

		// set column functions
		$this->dgFeeds->setColumnFunction(array('BackendDatagridFunctions', 'getUser'), array('[author]'), 'author', true);
		$this->dgFeeds->setColumnFunction(array('BackendFeedmuncherIndex', 'getCategory'), array('[target]', '[category]'), 'category', true);

		// add edit column
		$this->dgFeeds->addColumn('edit', null, BL::lbl('Edit'), BackendModel::createURLForAction('edit') .'&amp;id=[id]', BL::lbl('Edit'));

		// set sorting columns
		$this->dgFeeds->setSortingColumns(array('name', 'author', 'source'), 'id');
		$this->dgFeeds->setSortParameter('desc');

		// our JS needs to know an id, so we can highlight it
		$this->dgFeeds->setRowAttributes(array('id' => 'row-[revision_id]'));
	}


	/**
	 * Parse the datagrid
	 *
	 * @return	void
	 */
	private function parse()
	{
		// parse the feeds datagrid
		$this->tpl->assign('dgFeeds', ($this->dgFeeds->getNumResults() != 0) ? $this->dgFeeds->getContent() : false);
	}
}

?>