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
	}


	/**
	 * Parse
	 *
	 * @return	void
	 */
	private function parse()
	{
		// get a random banner for the group
		$banner = FrontendBannersModel::getRandomBannerForGroup((int) $this->data['id']);

		// assign header image
		$this->tpl->assign('item', (array) $banner);

		// is the file an swf?
		$isSWF = SpoonFile::getExtension($banner['file']) == 'swf';

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