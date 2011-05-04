<?php

/**
 * This is the share-action, it will display a form to set share settings
 *
 * @package		backend
 * @subpackage	share
 *
 * @author		Lowie Benoot <lowie@netlash.com>
 * @since		2.1
 */
class BackendSettingsShare extends BackendBaseActionIndex
{
	/**
	 * The form instance
	 *
	 * @var	BackendForm
	 */
	private $frm;


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
		$this->frm = new BackendForm('share');

		// add multicheckbox for share services
		$this->frm->addMultiCheckbox('services', BackendSettingsModel::getShareServices(), BackendModel::getModuleSetting('share', 'services'));

		// add checkbox for shorten option
		$this->frm->addCheckbox('shorten', BackendModel::getModuleSetting('share', 'shorten_urls'));

		// create datagrid for shareable modules
		$this->datagrid = new BackendDataGridArray(BackendSettingsModel::getShareableModules());

		// add attributes for inline editing
		$this->datagrid->setColumnAttributes('message' , array('data-id' => '{id: [id]}'));

		// set widths for columns
		$this->datagrid->setColumnAttributes('module', array('width' => '20%'));
		$this->datagrid->setColumnAttributes('item_type', array('width' => '20%'));
		$this->datagrid->setColumnAttributes('message', array('width' => '60%'));
	}


	/**
	 * Parse the form
	 *
	 * @return	void
	 */
	private function parse()
	{
		// parse the form
		$this->frm->parse($this->tpl);

		// assign datagrid
		$this->tpl->assign('dgModules', $this->datagrid->getContent());
	}


	/**
	 * Validates the form
	 *
	 * @return	void
	 */
	private function validateForm()
	{
		// is the form submitted?
		if($this->frm->isSubmitted())
		{
			// no errors ?
			if($this->frm->isCorrect())
			{
				//  get the checked services
				$checkedServices = $this->frm->getField('services')->getChecked();

				// save it in the settings
				BackendModel::setModuleSetting('share', 'services', $checkedServices);

				// should the urls be shortened?
				$shortenURLs = $this->frm->getField('shorten')->isChecked();

				// save as setting
				BackendModel::setModuleSetting('share', 'shorten_urls', $shortenURLs);
			}

			// assign report
			$this->tpl->assign('report', true);
			$this->tpl->assign('reportMessage', BL::msg('Saved'));
		}
	}
}

?>