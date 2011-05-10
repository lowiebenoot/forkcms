<?php

/**
 * This is the tracker action.
 * It will increase the amount of clicks for the share settings, and then redirect to the correct url.
 *
 * @package		frontend
 * @subpackage	share
 *
 * @author	Lowie Benoot <lowie@netlash.com>
 * @since	2.1
 */
class FrontendShareAjaxTracker extends FrontendBaseAJAXAction
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

		// internal referrer?
		if(SpoonFilter::isInternalReferrer(array(SITE_DOMAIN)) && isset($_SERVER['HTTP_REFERER']))
		{
			// get parameters
			$id = SpoonFilter::getGetValue('id', null, null, 'int');
			$url = SpoonFilter::getGetValue('url', null, null, 'string');

			// url is given?
			if(!empty($url))
			{
				// the share setting exists?
				if(FrontendShareModel::settingExists($id))
				{
					// increase amount of clicks
					FrontendShareModel::increaseNumClicks($id);

					// redirect
					SpoonHTTP::redirect($url);
				}
			}
		}

		// url or id not given or banner share setting doesnt exist or not an internal referrer
		SpoonHTTP::redirect(FrontendNavigation::getURL(404));
	}
}

?>