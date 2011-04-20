<?php

/**
 * This is a widget with recent comments on all feedmuncher-articles
 *
 * @package		frontend
 * @subpackage	feedmuncher
 *
 * @author		Lowie Benoot <lowiebenoot@netlash.com>
 * @since		2.1
 */
class FrontendFeedmuncherWidgetRecentComments extends FrontendBaseWidget
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
		// assign comments
		$this->tpl->assign('widgetFeedmuncherRecentComments', FrontendFeedmuncherModel::getRecentComments(5));
	}
}

?>