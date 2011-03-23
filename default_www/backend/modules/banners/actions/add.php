<?php

/**
 * This is the add-action, it will display a form to create a new item
 *
 * @package		backend
 * @subpackage	banners
 *
 * @author		Lowie Benoot <lowiebenoot@netlash.com>
 * @since		2.2
 */
class BackendBannersAdd extends BackendBaseActionAdd
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

		// load the form
		$this->loadForm();

		// validate the form
		$this->validateForm();

		// parse the datagrid
		$this->parse();

		// display the page
		$this->display();
	}


	/**
	 * Load the form
	 *
	 * @return	void
	 */
	private function loadForm()
	{
		// create form
		$this->frm = new BackendForm('add');

		// create elements
		$this->frm->addText('name');
		$this->frm->addText('url');
		$this->frm->addDropdown('size', BackendBannersModel::getStandards());
		$this->frm->addFile('image');
		$this->frm->addDate('start_date');
		$this->frm->addTime('start_time', null, 'inputText time');
		$this->frm->addDate('end_date');
		$this->frm->addTime('end_time', null, 'inputText time');
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
			// cleanup the submitted fields, ignore fields that were added by hackers
			$this->frm->cleanupFields();

			// validate fields

			// no errors?
			if($this->frm->isCorrect())
			{
				// build item
			}
		}
	}
}

?>