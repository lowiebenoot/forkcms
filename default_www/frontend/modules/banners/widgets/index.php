<?php

/**
 * FrontendBannersWidgetIndex
 * This is a widget for the header image
 *
 *
 * @package		frontend
 * @subpackage	banners
 *
 * @author		Lowie Benoot <lowie@netlash.com>
 * @since		2.1
 */
class FrontendBannersWidgetIndex extends FrontendBaseWidget
{
	/**
	 * Execute the extra
	 *
	 * @return	void
	 */
	public function execute()
	{
		// call parent
		parent::execute();

		// load template
		$this->loadTemplate();

		// parse
		$this->parse();

		// display
		return $this->tpl->getContent(FRONTEND_MODULES_PATH . '/' . $this->getModule() . '/layout/widgets/' . $this->getAction() . '.tpl');
	}


	/**
	 * Parse
	 *
	 * @return	void
	 */
	private function parse()
	{
		// get the banner if the widget is for a single banner
		if($this->data['source'] == 'banner') $banner = FrontendBannersModel::getBanner((int) $this->data['id']);

		// get random banner from group if the widget is for a banner group
		else $banner = FrontendBannersModel::getRandomBannerForGroup((int) $this->data['id']);

		if(!empty($banner))
		{
			// define utm parameters array
			$utmParams = array();

			// add utm parameters
			$utmParams['utm_source'] = FrontendModel::getModuleSetting('core', 'site_title_' . FRONTEND_LANGUAGE);
			$utmParams['utm_medium'] = 'banner';
			$utmParams['utm_campaign'] = FrontendModel::getModuleSetting('core', 'site_title_' . FRONTEND_LANGUAGE);

			// add the utm parameters to the url
			$banner['url'] = $banner['url'] . (strstr($banner['url'], '?') ? '&' : '?') . http_build_query($utmParams);

			// assign item
			$this->tpl->assign('item', (array) $banner);

			// assign the tracker url
			$this->tpl->assign('trackerURL', '/frontend/ajax.php?module=banners&action=tracker&amp;language=' . FRONTEND_LANGUAGE);

			// is the file an swf?
			$isSWF = SpoonFile::getExtension($banner['file']) == 'swf';

			// assign a part of the microtime if it is an swf.
			// Otherwise it isn't possible to add the same flashbanner more than once to a page because the id swfObject div would be the same.
			if($isSWF) $this->tpl->assign('microtime', substr(microtime(), 2, 8));

			// @TODO:
			// add the swfobject.js if it's an swf
			// currently the swfobject.js is loaded in the template because $this->header->addJavascript loads the js at the end
			// and it should be loaded before embedSWF() is called
			// so: somehow it should be loaded in the beginning instead of at the end
			//if($isSWF) $this->header->addJavascript('/frontend/modules/banners/js/swfobject.js');

			// is the file an swf?
			$this->tpl->assign('isSWF', $isSWF);
		}

		// assign false, otherwise it can be assigned by another banner
		else $this->tpl->assign('item', false);
	}
}

?>