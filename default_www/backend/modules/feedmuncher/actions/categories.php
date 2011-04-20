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
		$this->datagrid = new BackendDataGridDB(BackendFeedmuncherModel::QRY_DATAGRID_BROWSE_CATEGORIES, BL::getWorkingLanguage());

		// sorting columns
		$this->datagrid->setSortingColumns(array('title'), 'title');

		// add column
		$this->datagrid->addColumn('edit', null, BL::lbl('Edit'), BackendModel::createURLForAction('edit_category') . '&amp;id=[id]', BL::lbl('Edit'));

		// row function
		$this->datagrid->setRowFunction(array('BackendFeedmuncherCategories', 'setDefault'), array('[id]'));

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
	 * Set class on row with the default class
	 *
	 * @return	array
	 * @param	int $id					The id of the category.
	 * @param	array $rowAttributes	The current row attributes.
	 */
	public static function setDefault($id, $rowAttributes)
	{
		// is this the default category?
		if(BackendModel::getModuleSetting('feedmuncher', 'default_category_' . BL::getWorkingLanguage(), null) == $id)
		{
			// class already defined?
			if(isset($rowAttributes['class'])) $rowAttributes['class'] .= ' isDefault';

			// set class
			else $rowAttributes['class'] = 'isDefault';

			// return
			return $rowAttributes;
		}
	}
}

?>