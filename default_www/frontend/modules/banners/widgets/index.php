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
		// get a random banner for the group
		if($this->data['source'] == 'banner') $banner = FrontendBannersModel::getBanner((int) $this->data['id']);
		else $banner = FrontendBannersModel::getRandomBannerForGroup((int) $this->data['id']);

		// create utm parameters
		$utmParams = array(
				'utm_source' => FrontendModel::getModuleSetting('core', 'site_title_' . FRONTEND_LANGUAGE),
				'utm_medium' => 'banner',
				'utm_campaign' => FrontendModel::getModuleSetting('core', 'site_title_' . FRONTEND_LANGUAGE));

		// add utm parameters
		$banner['url'] = $banner['url'] . (strstr($banner['url'], '?') ? '&' : '?') . http_build_query($utmParams);

		$this->tpl->assign('itemId', $banner['id']);

		// assign item
		$this->tpl->assign('item', (array) $banner);

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
}

?>