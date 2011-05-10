<?php

/**
 * This is the facebook tracker action.
 * It will increase the amount of clicks for the facebook share settings.
 *
 * @package		frontend
 * @subpackage	share
 *
 * @author	Lowie Benoot <lowie@netlash.com>
 * @since	2.1
 */
class FrontendShareAjaxFacebookTracker extends FrontendBaseAJAXAction
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

		// get parameters
		$id = SpoonFilter::getGetValue('id', null, null, 'int');

		// the share setting exists?
		if(FrontendShareModel::settingExists($id))
		{
			// increase amount of clicks
			FrontendShareModel::increaseNumClicks($id);
		}

		// output OK
		$this->output(self::OK);
	}
}

?>