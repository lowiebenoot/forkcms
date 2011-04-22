<?php

/**
 * This is the categories-action, it will display the overview of feedmuncher categories
 *
 * @package		backend
 * @subpackage	feedmuncher
 *
 * @author		Lowie Benoot <lowiebenoot@netlash.com>
 * @since		2.1
 */
class BackendFeedmuncherCategories extends BackendBaseActionIndex
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

		// load datagrids
		$this->loadDataGrid();

		// parse page
		$this->parse();

		// display the page
		$this->display();
	}


	/**
	 * Loads the datagrids
	 *
	 * @return	void
	 */
	private function loadDataGrid()
	{
		// create datagrid
		$this->datagrid = new BackendDataGridDB(BackendFeedmuncherModel::QRY_DATAGRID_BROWSE_CATEGORIES, array('active', 'N','N', 'feedmuncher', BL::getWorkingLanguage()));

		// set headers
		$this->datagrid->setHeaderLabels(array('num_items' => ucfirst(BL::lbl('Amount'))));

		// sorting columns
		$this->datagrid->setSortingColumns(array('title', 'num_items'), 'title');

		// set column URLs
		$this->datagrid->setColumnURL('title', BackendModel::createURLForAction('edit_category') . '&amp;id=[id]');

		// add column
		$this->datagrid->addColumn('edit', null, BL::lbl('Edit'), BackendModel::createURLForAction('edit_category') . '&amp;id=[id]', BL::lbl('Edit'));

		// convert the count into a readable and clickable one
		$this->datagrid->setColumnFunction(array(__CLASS__, 'setClickableCount'), array('[num_items]', BackendModel::createURLForAction('articles') . '&amp;feedmuncherCategory=[id]'), 'num_items', true);

		// disable paging
		$this->datagrid->setPaging(false);

		// add attributes, so the inline editing has all the needed data
		$this->datagrid->setColumnAttributes('title', array('data-id' => '{id:[id]}'));
	}


	/**
	 * Parse & display the page
	 *
	 * @return	void
	 */
	private function parse()
	{
		$this->tpl->assign('datagrid', ($this->datagrid->getNumResults() != 0) ? $this->datagrid->getContent() : false);
	}


	/**
	 * Convert the count in a human readable one.
	 *
	 * @return	string
	 * @param	int $count		The count.
	 * @param	string $link	The link for the count.
	 */
	public static function setClickableCount($count, $link)
	{
		// redefine
		$count = (int) $count;
		$link = (string) $link;
		$return = '';

		if($count > 1) $return = '<a href="' . $link . '">' . $count . ' ' . BL::getLabel('Articles') . '</a>';
		elseif($count == 1) $return = '<a href="' . $link . '">' . $count . ' ' . BL::getLabel('Article') . '</a>';

		return $return;
	}
}

?>