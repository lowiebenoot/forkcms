<?php

/**
 * This is the loading-action, it will display a spinner while data is collected
 *
 * @package		backend
 * @subpackage	feedmuncher
 *
 * @author		Lowie Benoot <lowie@netlash.com>
 * @since		2.1
 */
class BackendFeedmuncherLoading extends BackendBaseAction
{
	/**
	 * The redirect action and identifier to give along with the curl call
	 *
	 * @var	string
	 */
	private $identifier;


	/**
	 * Execute the action
	 *
	 * @return	void
	 */
	public function execute()
	{
		// call parent, this will probably add some general CSS/JS or other required files
		parent::execute();

		// call to get_data script
		$this->getData();

		// parse
		$this->parse();

		// display the page
		$this->display();
	}


	/**
	 * Call to the get_data script
	 *
	 * @return	void
	 */
	private function getData()
	{
		// init vars
		$this->identifier = time() . rand(0, 999);

		// build url
		$URL = SITE_URL . '/backend/cronjob.php?module=feedmuncher&action=get_articles';
		$URL .= '&identifier=' . $this->identifier;

		// set options
		$options = array();
		$options[CURLOPT_URL] = $URL;
		if(ini_get('open_basedir') == '' && ini_get('safe_mode' == 'Off')) $options[CURLOPT_FOLLOWLOCATION] = true;
		$options[CURLOPT_RETURNTRANSFER] = true;
		$options[CURLOPT_TIMEOUT] = 1;

		// init
		$curl = curl_init();

		// set options
		curl_setopt_array($curl, $options);

		// execute
		curl_exec($curl);

		// close
		curl_close($curl);
	}


	/**
	 * Parse this page
	 *
	 * @return	void
	 */
	protected function parse()
	{
		$this->tpl->assign('redirect', BackendModel::createURLForAction('articles'));
		$this->tpl->assign('identifier', $this->identifier);
	}
}

?>