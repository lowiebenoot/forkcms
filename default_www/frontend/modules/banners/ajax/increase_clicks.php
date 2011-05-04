<?php

/**
 * This is the increase-click action, it will increase the num_clicks of a banner
 *
 * @package		frontend
 * @subpackage	banners
 *
 * @author		Lowie Benoot <lowie@netlash.com>
 * @since		2.1
 */
class FrontendBannersAjaxIncreaseClicks extends FrontendBaseAJAXAction
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
		$id = SpoonFilter::getPostValue('id', null, null, 'int');

		// increase clicks
		FrontendBannersModel::increaseNumClicks($id);

		// output
		$this->output(self::OK);
	}
}

?>