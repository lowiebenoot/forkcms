<?php

/**
 * This is a widget with the link to the archive
 *
 * @package		frontend
 * @subpackage	feedmuncher
 *
 * @author		Lowie Benoot <lowiebenoot@netlash.com>
 * @since		2.1
 */
class FrontendFeedmuncherWidgetArchive extends FrontendBaseWidget
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
		// we will cache this widget for 15minutes
		$this->tpl->cache(FRONTEND_LANGUAGE . '_feedmuncherWidgetArchiveCache', (24 * 60 * 60));

		// if the widget isn't cached, assign the variables
		if(!$this->tpl->isCached(FRONTEND_LANGUAGE . '_feedmuncherWidgetArchiveCache'))
		{
			// get the numbers
			$this->tpl->assign('widgetFeedmuncherArchive', FrontendFeedmuncherModel::getArchiveNumbers());
		}
	}
}

?>