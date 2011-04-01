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
		// get shareable modules
		$this->modules = BackendSettingsModel::getShareableModules();

		// create form
		$this->frm = new BackendForm('share');

		// add multicheckbox for share services
		$this->frm->addMultiCheckbox('services', BackendSettingsModel::getShareServices(), BackendModel::getModuleSetting('share', 'services'));

		// add checkbox for shorten option
		$this->frm->addCheckbox('shorten', BackendModel::getModuleSetting('share', 'shorten_urls'));

		// get shorteners from settings
		$aShorteners = BackendModel::getModuleSetting('share', 'shorteners');

		// redefine the shorteners so it can be used for a radiobutton field
		$shorteners = array();

		// loop shorteners and add to the redefined array
		foreach($aShorteners as $shortener) $shorteners[] = array('value' => $shortener['id'], 'label' => $shortener['name']);

		// add radiobutton for shortener services
		$this->frm->addRadiobutton('shortener', $shorteners, BackendModel::getModuleSetting('share', 'shortener'));
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
				//  get the checked services
				$checkedServices = $this->frm->getField('services')->getChecked();

				// save it in the settings
				BackendModel::setModuleSetting('share', 'services', $checkedServices);

				// should the urls be shortened?
				$shortenURLs = $this->frm->getField('shorten')->getValue();

				// save as setting
				BackendModel::setModuleSetting('share', 'shorten_urls', $shortenURLs);

				// if urls should be shortened, which shortener should be used?
				if($shortenURLs)
				{
					// get the selected shortener
					$shortener = $this->frm->getField('shortener')->getValue();

					// save as setting
					BackendModel::setModuleSetting('share', 'shortener', $shortener);
				}
			}
		}
	}
}

?>