<?php

/**
 * This is the index-action (default), it will display the overview of the banners
 *
 * @package		backend
 * @subpackage	banners
 *
 * @author		Lowie Benoot <lowiebenoot@netlash.com>
 * @since		2.1
 */
class BackendBannersIndex extends BackendBaseActionIndex
{
	/**
	 * Datagrids
	 *
	 * @var	SpoonDataGrid
	 */
	private $dgBanners;


	/**
	 * Execute the action
	 *
	 * @return	void
	 */
	public function execute()
	{
		// call parent, this will probably add some general CSS/JS or other required files
		parent::execute();

		// load datagrid
		$this->loadDataGrids();

		// parse page
		$this->parse();

		// display the page
		$this->display();
	}


	/**
	 * Loads the datagrids for the blogposts
	 *
	 * @return	void
	 */
	private function loadDataGrids()
	{
		// create datagrid
		$this->dgBanners = new BackendDataGridDB(BackendBannersModel::QRY_DATAGRID_BROWSE_BANNERS, BL::getWorkingLanguage());

		// set sorting columns
		$this->dgBanners->setSortingColumns(array('name', 'date_from', 'date_till', 'num_clicks', 'num_views'), 'id');
		$this->dgBanners->setSortParameter('DESC');

		// hide columns
		$this->dgBanners->setColumnsHidden(array('standard_id'));

		// change date format
		$this->dgBanners->setColumnFunction(array('BackendDatagridFunctions', 'getLongDate'), array('[date_from]'), 'date_from', true);
		$this->dgBanners->setColumnFunction(array('BackendDatagridFunctions', 'getLongDate'), array('[date_till]'), 'date_till', true);

		// set colum URLs
		$this->dgBanners->setColumnURL('name', BackendModel::createURLForAction('edit') . '&amp;id=[id]');

		// add edit column
		$this->dgBanners->addColumn('edit', null, BL::lbl('Edit'), BackendModel::createURLForAction('edit') . '&amp;id=[id]', BL::lbl('Edit'));
	}


	/**
	 * Parse all datagrids
	 *
	 * @return	void
	 */
	private function parse()
	{
		// parse the datagrid for the drafts
		$this->tpl->assign('dgBanners', ($this->dgBanners->getNumResults() != 0) ? $this->dgBanners->getContent() : false);
	}
}

?>