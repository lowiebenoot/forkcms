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
	 * Load the datagrids
	 *
	 * @return	void
	 */
	private function loadDatagrids()
	{
		// create feeds datagrid
		$this->dgFeeds = new BackendDataGridDB(BackendFeedmuncherModel::QRY_DATAGRID_BROWSE_FEEDS, array(BL::getWorkingLanguage(), 'N'));

		// set headers
		$this->dgFeeds->setHeaderLabels(array('author_user_id' => ucfirst(BL::lbl('Author')), 'category_id' => ucfirst(BL::lbl('Category'))));

		// set hidden columns
		$this->dgFeeds->setColumnHidden('target');

		// set paging
		$this->dgFeeds->setPaging(false);

		// set colum URLs
		$this->dgFeeds->setColumnURL('name', BackendModel::createURLForAction('edit') .'&amp;id=[id]');
		$this->dgFeeds->setColumnURL('source', '[source]');

		// set column functions
		$this->dgFeeds->setColumnFunction(array('BackendDatagridFunctions', 'getUser'), array('[author_user_id]'), 'author_user_id', true);
		$this->dgFeeds->setColumnFunction(array('BackendFeedmuncherIndex', 'getCategory'), array('[target]', '[category_id]'), 'category_id', true);

		// add edit column
		$this->dgFeeds->addColumn('edit', null, BL::lbl('Edit'), BackendModel::createURLForAction('edit') .'&amp;id=[id]', BL::lbl('Edit'));

		// set sorting columns
		$this->dgFeeds->setSortingColumns(array('name', 'author_user_id'), 'id');
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


	/**
	 * get the target category to use in the datagrid
	 * example: 'blog - default'
	 *
	 * @return	string
	 * @param	string $target		the target module
	 * @param	int	$category_id
	 */
	public static function getCategory($target, $category_id)
	{
		$category = ($target == 'feedmuncher') ? BackendFeedmuncherModel::getCategory((int) $category_id) : BackendFeedmuncherModel::getCategoryFromBlog((int) $category_id);


		return $target . ' - <a href="' . BackendModel::createURLForAction('edit_category', $target) . '&amp;id=' . $category_id . '">' . $category['name'] . '</a>';
	}
}

?>