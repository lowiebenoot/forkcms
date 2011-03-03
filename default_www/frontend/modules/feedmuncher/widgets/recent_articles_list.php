<?php

/**
 * This is a widget with recent feedmuncher-articles
 *
 * @package		frontend
 * @subpackage	feedmuncher
 *
 * @author		Tijs Verkoyen <tijs@netlash.com>
 * @author		Lowie Benoot <lowiebenoot@netlash.com>
 * @since		2.0
 */
class FrontendFeedmuncherWidgetRecentArticlesList extends FrontendBaseWidget
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
		// get RSS-link
		$rssLink = FrontendModel::getModuleSetting('feedmuncher', 'feedburner_url_'. FRONTEND_LANGUAGE);
		if($rssLink == '') $rssLink = FrontendNavigation::getURLForBlock('feedmuncher', 'rss');

		// add RSS-feed into the metaCustom
		$this->header->addMetaCustom('<link rel="alternate" type="application/rss+xml" title="'. FrontendModel::getModuleSetting('feedmuncher', 'rss_title_'. FRONTEND_LANGUAGE) .'" href="'. $rssLink .'" />');

		// assign comments
		$this->tpl->assign('widgetFeedmuncherRecentArticlesList', FrontendFeedmuncherModel::getAll(FrontendModel::getModuleSetting('feedmuncher', 'recent_articles_list_num_items', 5)));
	}
}

?>