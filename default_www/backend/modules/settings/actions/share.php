<?php

/**
 * This is the share-action, it will display a form to set share settings
 *
 * @package		backend
 * @subpackage	share
 *
 * @author		Tijs Verkoyen <tijs@netlash.com>
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
		// get the services
		$services = BackendSettingsModel::getShareServices();

		// get the services that should be checked (from settings)
		$checked = BackendModel::getModuleSetting('share', 'services');

		// get shareable modules
		$this->modules = BackendSettingsModel::getShareableModules();

		// does the url's need to be shortened?
		$shortenURLs = BackendModel::getModuleSetting('share', 'shorten_urls');

		// get url shorteners
		$shorteners = BackendModel::getModuleSetting('share', 'shorteners');

		// create form
		$this->frm = new BackendForm('share');

		// add checkbox for shorten iption
		$this->frm->addCheckbox('shorten', $shortenURLs);

		// add radiobutton for shortener services
		$this->frm->addRadiobutton('shortener', array(array('value' => 1, 'label' => 'bit.ly'), array('value' => 2, 'label' => 'tinyURL'), array('value' => 3, 'label' => 't.co')), 1);
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

		// assign iteration
		$this->tpl->assign(array('modules' => $this->modules));
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

			}
		}
	}
}

?>