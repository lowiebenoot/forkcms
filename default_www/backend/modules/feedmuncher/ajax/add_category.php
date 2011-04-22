<?php

/**
 * This add-action will create a new category using Ajax
 *
 * @package		backend
 * @subpackage	feedmuncher
 *
 * @author		Lowie Benoot <lowiebenoot@netlash.com>
 * @since		2.1
 */
class BackendFeedmuncherAjaxAddCategory extends BackendBaseAJAXAction
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

		// get parameters
		$categoryTitle = trim(SpoonFilter::getPostValue('value', null, '', 'string'));
		$categoryTarget = SpoonFilter::getPostValue('target', array('blog', 'feedmuncher'), '', 'string');

		// validate
		if($categoryTitle === '') $this->output(self::BAD_REQUEST, null, BL::err('TitleIsRequired'));
		if($categoryTarget === '') $this->output(self::BAD_REQUEST, null, BL::err('Error'));

		// get the data
		// build array
		$item['title'] = SpoonFilter::htmlspecialchars($categoryTitle);
		$item['language'] = BL::getWorkingLanguage();

		$meta['keywords'] = $item['title'];
		$meta['keywords_overwrite'] = 'N';
		$meta['description'] = $item['title'];
		$meta['description_overwrite'] = 'N';
		$meta['title'] = $item['title'];
		$meta['title_overwrite'] = 'N';
		$meta['url'] = BackendFeedmuncherModel::getURLForCategory($item['title']);

		// new category for feedmuncher?
		if($categoryTarget == 'feedmuncher')
		{
			// insert category
			$item['id'] = BackendFeedmuncherModel::insertCategory($item, $meta);
		}

		// new category for blog?
		else
		{
			// require blogmodel
			require_once BACKEND_MODULES_PATH . '/blog/engine/model.php';

			// insert category
			$item['id'] = BackendBlogModel::insertCategory($item, $meta);
		}

		// output
		$this->output(self::OK, $item, vsprintf(BL::msg('AddedCategory'), array($item['title'])));
	}
}

?>