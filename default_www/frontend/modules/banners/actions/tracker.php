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

		// get parameters
		$this->id = SpoonFilter::getGetValue('id', null, 'int');
		$this->url = SpoonFilter::getGetValue('url', null, 'string');

		// id given?
		if($this->id != null)
		{
			if(FrontendBannersModel::exists($this->id))
			{
				// add a click
				FrontendBannersModel::increaseNumClicks($this->id);

				// build the google vars query
				$params = array();
				$params['utm_source'] = FrontendModel::getModuleSetting('core', 'site_title_' . FRONTEND_LANGUAGE);
				$params['utm_medium'] = 'banner';
				$params['utm_campaign'] = FrontendModel::getModuleSetting('core', 'site_title_' . FRONTEND_LANGUAGE);

				// redirect to url
				// TODO: wat als er al ? in staat?
				$this->redirect($this->url . '?utm_medium=banner&utm_source=');
			}
		}
	}
}

?>