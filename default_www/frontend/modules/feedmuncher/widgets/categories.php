<?php

/**
 * This is a widget with the feedmuncher-categories
 *
 * @package		frontend
 * @subpackage	feedmuncher
 *
 * @author		Tijs Verkoyen <tijs@netlash.com>
 * @author		Lowie Benoot <lowiebenoot@netlash.com>
 * @since		2.0
 */
class FrontendFeedmuncherWidgetCategories extends FrontendBaseWidget
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
		// get categories
		$categories = FrontendFeedmuncherModel::getAllCategories();

		// build link
		$link = FrontendNavigation::getURLForBlock('feedmuncher', 'category');

		// any categories?
		if(!empty($categories))
		{
			// loop and reset url
			foreach($categories as &$row) $row['url'] = $link .'/'. $row['url'];
		}

		// assign comments
		$this->tpl->assign('widgetFeedmuncherCategories', $categories);
	}
}

?>