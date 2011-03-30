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
		// create form
		$this->frm = new BackendForm('share');

		// get the services
		$services = array();
		$services[] = array('label' => 'Facebook', 'value' => 1);
		$services[] = array('label' => 'Twitter', 'value' => 2);
		$services[] = array('label' => 'Facebook', 'value' => 3);
		$services[] = array('label' => 'Delicious', 'value' => 4);
		$services[] = array('label' => 'Mail', 'value' => 5);

		// get the services that should be checked (from settings)
		$checked = array(1,2);

		// add multi checkbox
		$this->frm->addMultiCheckbox('services', $services, $checked);

		// get modules (zie search settings)
		$this->modules[0] = array('module' => 'pages', 'id' => 'sharePages', 'label' => 'Pages', 'chk' => '<input type="checkbox" value="Y" id="sharePages" name="share_pages" class="inputCheckbox" checked="checked" />', 'message' => 'Look at this awesome page! - "%1s"');
		$this->modules[1] = array('module' => 'blog', 'id' => 'shareBlog', 'label' => 'Blog', 'chk' => '<input type="checkbox" value="Y" id="shareBlog" name="share_blog" class="inputCheckbox" checked="checked" />', 'message' => 'I read a cool article - "%1s"');
		$this->modules[2] = array('module' => 'events', 'id' => 'shareEvents', 'label' => 'Events', 'chk' => '<input type="checkbox" value="Y" id="shareEvents" name="share_events" class="inputCheckbox" />', 'message' => 'I am going to this event: "%1s"');

		$this->frm->addRadiobutton('shorten', array(array('value' => 'Y', 'label' => BL::lbl('Yes')), array('value' => 'N', 'label' => BL::lbl('no'))), 'Y');

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