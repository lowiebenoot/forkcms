<?php

/**
 * This is the tracker action.
 * It will increase the amount of clicks for a banner, and then redirect the user to the correct url.
 *
 * @package		frontend
 * @subpackage	banners
 *
 * @author		Lowie Benoot <lowie@netlash.com>
 * @since		2.1
 */
class FrontendBannersAjaxTracker extends FrontendBaseAJAXAction
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
			$url = urldecode(SpoonFilter::getGetValue('url', null, 'string'));

			// define utm parameters array
			$utmParams = array();

			// add utm parameters
			$utmParams['utm_source'] = urldecode(SpoonFilter::getGetValue('utm_source', null, null, 'string'));
			$utmParams['utm_medium'] = urldecode(SpoonFilter::getGetValue('utm_medium', null, null, 'string'));
			$utmParams['utm_campaign'] = urldecode(SpoonFilter::getGetValue('utm_campaign', null, null, 'string'));

			// id and url given?
			if(!empty($url))
			{
				// banner exists?
				if(FrontendBannersModel::exists($id))
				{
					// add a click
					FrontendBannersModel::increaseNumClicks($id);

					// redirect to url
					SpoonHTTP::redirect($url . (strstr($url, '?') ? '&' : '?') . http_build_query($utmParams));
				}
			}
		}

		// url or id not given or banner doesn't exist or not an internal referrer
		SpoonHTTP::redirect(FrontendNavigation::getURL(404));
	}
}

?>