<?php

/**
 * This is the add-action, it will display a form to create a new category
 *
 * @package		backend
 * @subpackage	feedmuncher
 *
 * @author		Lowie Benoot <lowiebenoot@netlash.com>
 * @since		2.1
 */
class BackendFeedmuncherAdd extends BackendBaseActionAdd
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

		// is the blog installed?
		$this->blogIsInstalled = BackendFeedmuncherModel::blogIsInstalled();

		// load the form
		$this->loadForm();

		// validate the form
		$this->validateForm();

		// parse the datagrid
		$this->parse();

		// display the page
		$this->display();
	}


	/**
	 * Load the form
	 *
	 * @return	void
	 */
	private function loadForm()
	{
		// create form
		$this->frm = new BackendForm('addFeed');

		// create elements
		$this->frm->addText('name', null, 255);
		$this->frm->addText('url');
		$this->frm->addText('website', null, 255);

		// get feedmuncher categories and add the 'add category' item
		$feedmuncherCategories = BackendFeedmuncherModel::getCategories();
		$feedmuncherCategories['new_category'] = ucfirst(BL::getLabel('AddCategory'));

		$this->frm->addDropdown('category', $feedmuncherCategories);
		if($this->blogIsInstalled)
		{
			// get blog categories and add the 'add category' item
			$blogCategories = BackendFeedmuncherModel::getCategoriesFromBlog();
			$blogCategories['new_category'] = ucfirst(BL::getLabel('AddCategory'));

			$this->frm->addRadiobutton('target', array(array('label' => BL::getLabel('PostInFeedmuncher'), 'value' => 'feedmuncher'), array('label' => BL::getLabel('PostInBlog'), 'value' => 'blog')), 'feedmuncher');
			$this->frm->addDropdown('category_blog', $blogCategories);
		}
		$this->frm->addDropdown('author', BackendUsersModel::getUsers(), BackendAuthentication::getUser()->getUserId());
		$this->frm->addCheckbox('auto_publish', BackendModel::getModuleSetting('feedmuncher', 'auto_publish'));
		$this->frm->addCheckbox('link_to_original');

		// assign whether blog is installed or not
		$this->tpl->assign('blogIsInstalled', $this->blogIsInstalled);
	}


	/**
	 * Validate the form
	 *
	 * @return	void
	 */
	private function validateForm()
	{
		// is the form submitted?
		if($this->frm->isSubmitted())
		{
			// cleanup the submitted fields, ignore fields that were added by hackers
			$this->frm->cleanupFields();

			// is the name filled in?
			$this->frm->getField('name')->isFilled(BL::err('NameIsRequired'));

			// is the url filled in?
			if($this->frm->getField('url')->isFilled(BL::err('UrlIsRequired')))
			{
				// is the url a valid urL?
				if($this->frm->getField('url')->isURL(BL::err('UrlIsInvalid')))
				{
					// is there already a feed with that url (and same language)
					if(BackendFeedmuncherModel::existsByURL($this->frm->getField('url')->getValue()))
					{
						// is it deleted before?
						if(BackendFeedmuncherModel::feedDeletedBefore($this->frm->getField('url')->getValue())) $this->frm->getField('url')->addError(sprintf(BL::err('FeedWasDeletedBefore'), BackendModel::createURLForAction('undo_delete', null, null, array('url' => $this->frm->getField('url')->getValue()))));

						// not deleted before
						else $this->frm->getField('url')->addError(BL::getError('FeedAlreadyExists'));
					}
				}
			}

			// is website filled in?
			if($this->frm->getField('website')->isFilled(BL::err('WebsiteIsRequired'))) $this->frm->getField('website')->isURL(BL::err('WebsiteIsInvalid'));

			// is the form correct?
			if($this->frm->isCorrect())
			{
				// build item
				$item['name'] = $this->frm->getField('name')->getValue();
				$item['url'] = $this->frm->getField('url')->getValue();
				$item['source'] = $this->frm->getField('website')->getValue();
				$item['author_user_id'] = (int) $this->frm->getField('author')->getValue();
				$item['target'] = ($this->blogIsInstalled) ? $this->frm->getField('target')->getValue() : 'feedmuncher';
				$item['category_id'] = $item['target'] == 'feedmuncher' ? (int) $this->frm->getField('category')->getValue() : (int) $this->frm->getField('category_blog')->getValue();
				$item['auto_publish'] = $this->frm->getField('auto_publish')->isChecked() == true ? 'Y' : 'N';
				$item['link_to_original'] = $this->frm->getField('link_to_original')->isChecked() == true ? 'Y' : 'N';
				$item['language'] = BL::getWorkingLanguage();

				// insert in DB
				$feedId = BackendFeedmuncherModel::insert($item);

				// return to the feeds overview
				$this->redirect(BackendModel::createURLForAction('index') . '&report=addedFeed&var=' . urlencode($item['name']) . '&highlight=row-' . $feedId);
			}
		}
	}
}

?>