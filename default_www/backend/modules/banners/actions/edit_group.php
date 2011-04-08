<?php

/**
 * This is the edit-action, it will display a form to edit an existing item
 *
 * @package		backend
 * @subpackage	banners
 *
 * @author		Lowie Benoot <lowie@netlash.com>
 * @since		2.1
 */
class BackendBannersEditGroup extends BackendBaseActionEdit
{
	/**
	 * Execute the action
	 *
	 * @return	void
	 */
	public function execute()
	{
		// get parameters
		$this->id = $this->getParameter('id', 'int');

		// does the item exists
		if($this->id !== null && BackendBannersModel::existsGroup($this->id))
		{
			// call parent, this will probably add some general CSS/JS or other required files
			parent::execute();

			// get all data for the item we want to edit
			$this->getData();

			// load the form
			$this->loadForm();

			// validate the form
			$this->validateForm();

			// parse the datagrid
			$this->parse();

			// display the page
			$this->display();
		}

		// no item found, throw an exception, because somebody is fucking with our URL
		else $this->redirect(BackendModel::createURLForAction('groups') . '&error=non-existing');
	}


	/**
	 * Get the data
	 * If a revision-id was specified in the URL we load the revision and not the actual data.
	 *
	 * @return	void
	 */
	private function getData()
	{
		// get the record
		$this->record = (array) BackendBannersModel::getGroup($this->id);

		// no item found, throw an exceptions, because somebody is fucking with our URL
		if(empty($this->record)) $this->redirect(BackendModel::createURLForAction('groups') . '&error=non-existing');
	}


	/**
	 * Load the form
	 *
	 * @return	void
	 */
	private function loadForm()
	{
		// create form
		$this->frm = new BackendForm('edit');

		// create elements
		$this->frm->addText('name', $this->record['name']);

		// load datagrid
		$this->dgBanners = new BackendDataGridDB(BackendBannersModel::QRY_DATAGRID_BROWSE_BANNERS_BY_STANDARD, (int) $this->record['standard_id']);

		// hide column
		$this->dgBanners->setColumnsHidden(array('standard_id'));

		// change date format
		$this->dgBanners->setColumnFunction(array('BackendDatagridFunctions', 'getLongDate'), array('[date_from]'), 'date_from', true);
		$this->dgBanners->setColumnFunction(array('BackendDatagridFunctions', 'getLongDate'), array('[date_till]'), 'date_till', true);

		// add checkboxes
		$this->dgBanners->setMassActionCheckboxes('checkbox', '[id]', null, BackendBannersModel::getGroupMembers($this->id));

		// add standard_id to each column
		$this->dgBanners->setRowAttributes(array('data-standard' => '[standard_id]'));

		// disable paging
		$this->dgBanners->setPaging(false);
	}


	/**
	 * Parse the form
	 *
	 * @return	void
	 */
	protected function parse()
	{
		// call parent
		parent::parse();

		// parse the datagrid for the drafts
		$this->tpl->assign('dgBanners', ($this->dgBanners->getNumResults() != 0) ? $this->dgBanners->getContent() : false);

		// get the standard
		$standard = BackendBannersModel::getStandard($this->record['standard_id']);

		// parse the name of the standard
		$this->tpl->assign('standard', $standard);

		// parse item
		$this->tpl->assign('item', $this->record);
	}


	/**
	 * Validate the form
	 *
	 * @return	void
	 */
	private function validateForm()
	{
		// is the form submitted?
		if($this->frm->isSubmitted())
		{
			// get the selected banners.
			// this is done before cleaning up the fields, because the mass action checkboxes aren't added to the form
			$banners = SpoonFilter::getPostValue('id', null, null, 'array');

			// cleanup the submitted fields, ignore fields that were added by hackers
			$this->frm->cleanupFields();

			// validate fields
			$this->frm->getField('name')->isFilled(BL::err('TitleIsRequired'));

			// no banners selected?
			if(empty($banners))
			{
				// add form error
				$this->frm->addError('no banners selected');

				// assign form error in tpl
				$this->tpl->assign('formErrors', BL::err('SelectAtLeastOneBanner'));
			}

			// no errors?
			if($this->frm->isCorrect())
			{
				// build item
				$item['name'] = $this->frm->getField('name')->getValue();

				// update group in db
				BackendBannersModel::updateGroup($this->id, $item);

				// put the selected banners in the groups
				BackendBannersModel::setGroupMembers($this->id, $banners, $this->record['standard_id']);

				// everything is saved, so redirect to the overview
				$this->redirect(BackendModel::createURLForAction('groups') . '&report=editedGroup&var=' . urlencode($item['name']) . '&highlight=row-' . $this->id);
			}
		}
	}
}

?>