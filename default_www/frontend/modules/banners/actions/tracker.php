<?php

/**
 * This is the tracker-action
 *
 * @package		frontend
 * @subpackage	banners
 *
 * @author		Lowie Benoot <lowie@netlash.com>
 * @since		2.2
 */
class FrontendBannersTracker extends FrontendBaseBlock
{
	/**
	 * Execute the extra
	 *
	 * @return	void
	 */
	public function execute()
	{
		// call the parent
		parent::execute();

		// internal referrer?
		if(SpoonFilter::isInternalReferrer(array(SITE_DOMAIN)) && isset($_SERVER['HTTP_REFERER']))
		{
			// get parameters
			$id = SpoonFilter::getGetValue('id', null, null, 'int');
			$url = urldecode(SpoonFilter::getGetValue('url', null, 'string'));
			$utmParams = array(
				'utm_campaign' => urldecode(SpoonFilter::getGetValue('utm_campaign', null, null, 'string')),
				'utm_medium' => urldecode(SpoonFilter::getGetValue('utm_medium', null, null, 'string')),
				'utm_source' => urldecode(SpoonFilter::getGetValue('utm_source', null, null, 'string')));

			// id and url given?
			if($id != 0 && $url != '')
			{
				// banner exists?
				if(FrontendBannersModel::exists($id))
				{
					// add a click
					FrontendBannersModel::increaseNumClicks($id);

					// redirect to url
					$this->redirect($url . (strstr($url, '?') ? '&' : '?') . http_build_query($utmParams));
				}
			}
		}

		// url or id not given or banner doesn't exist or not an internal referrer
		$this->redirect(FrontendNavigation::getURL(404));
	}
}

?>