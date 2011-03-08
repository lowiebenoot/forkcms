<?php

/**
 * This is the edit action, it will display a form to edit a feed
 *
 * @package		backend
 * @subpackage	feedmuncher
 *
 * @author		Lowie Benoot <lowiebenoot@netlash.com>
 * @since		2.1
 */
class BackendFeedmuncherEdit extends BackendBaseActionEdit
{
	/**
	 * Execute the action
	 *
	 * @return	void
	 */
	public function execute()
	{
		// get parameters
		$this->id = $this->getParameter('id', 'int');

		// does the item exists
		if($this->id !== null && BackendFeedmuncherModel::exists($this->id))
		{
			// call parent, this will probably add some general CSS/JS or other required files
			parent::execute();

			// is the blog installed?
			$this->blogIsInstalled = BackendFeedmuncherModel::blogIsInstalled();

			// get the data of the feed
			$this->getData();

			// load the form
			$this->loadForm();

			// validate the form
			$this->validateForm();

			// parse the datagrid
			$this->parse();

			// display the page
			$this->display();
		}

		// no item found, throw an exception, because somebody is fucking with our URL
		else $this->redirect(BackendModel::createURLForAction('index') .'&error=non-existing');
	}


	/**
	 * Get the data of the feed
	 *
	 * @return	void
	 */
	private function getData()
	{
		$this->record = BackendFeedmuncherModel::get($this->id);
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

		// get current category
		if($this->record['target'] == 'feedmuncher')
		{
			// get current category
			$feedmuncherCategory = $this->record['category_id'];

			// get default category for blog
			$blogCategory = BackendModel::getModuleSetting('blog', 'default_category_'. BL::getWorkingLanguage());
		}

		else
		{
			// get current category
			$blogCategory = $this->record['category_id'];

			// get default category for feedmuncher
			$feedmuncherCategory = BackendModel::getModuleSetting('feedmuncher', 'default_category_'. BL::getWorkingLanguage());
		}

		// get default category id
		$defaultCategoryId = BackendModel::getModuleSetting('feedmuncher', 'default_category_'. BL::getWorkingLanguage());

		// did the feed already post something?
		$feedHasArticles = BackendFeedmuncherModel::feedHasArticles($this->id);

		// create elements
		$this->frm->addText('name', $this->record['name'], 255);
		$this->frm->addText('url', $this->record['url']);
		$this->frm->addText('website', $this->record['source'], 255);
		if($this->blogIsInstalled)
		{
			$this->frm->addRadiobutton('target', array(array('label' => BL::getLabel('PostInFeedmuncher'), 'value' => 'feedmuncher', 'attributes' => $feedHasArticles ? array('disabled' => '') : null), array('label' => BL::getLabel('PostInBlog'), 'value' => 'blog', 'attributes' => $feedHasArticles ? array('disabled' => '') : null)), $this->record['target']);
			$this->frm->addDropdown('category_blog', BackendFeedmuncherModel::getCategoriesFromBlog(), $blogCategory);
		}
		$this->frm->addDropdown('category', BackendFeedmuncherModel::getCategories(), $feedmuncherCategory);
		$this->frm->addDropdown('author', BackendUsersModel::getUsers(), $this->record['author_user_id']);
		$this->frm->addCheckbox('auto_publish', ($this->record['auto_publish'] == 'Y' ? true : false));
	}


	/**
	 * Parse the form
	 *
	 * @return	void
	 */
	protected function parse()
	{
		// call parent
		parent::parse();

		// assign whether blog is installed or not
		$this->tpl->assign('blogIsInstalled', $this->blogIsInstalled);

		// pare the record
		$this->tpl->assign('item', $this->record);
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
					if(BackendFeedmuncherModel::existsByURL($this->frm->getField('url')->getValue()) && $this->frm->getField('url')->getValue() != $this->record['url']) $this->frm->getField('url')->addError(BL::getError('FeedAlreadyExists'));
				}
			}
			if($this->frm->getField('website')->isFilled(BL::err('WebsiteIsRequired'))) $this->frm->getField('website')->isURL(BL::err('WebsiteIsInvalid'));

			if($this->frm->isCorrect())
			{
				// build item
				$item['name'] = $this->frm->getField('name')->getValue();
				$item['url'] = $this->frm->getField('url')->getValue();
				$item['source'] = $this->frm->getField('website')->getValue();
				$item['author_user_id'] = (int) $this->frm->getField('author')->getValue();
				$item['auto_publish'] = $this->frm->getField('auto_publish')->isChecked() == true ? 'Y' : 'N';
				$item['language'] = BL::getWorkingLanguage();
				if(!BackendFeedmuncherModel::feedHasArticles($this->id))
				{
					$item['target'] = ($this->blogIsInstalled) ? $this->frm->getField('target')->getValue() : 'feedmuncher';
					$item['category_id'] = $item['target'] == 'feedmuncher' ? (int) $this->frm->getField('category')->getValue() : (int) $this->frm->getField('category_blog')->getValue();
				}

				// insert in DB
				BackendFeedmuncherModel::update($this->id, $item);

				// return to the feeds overview
				$this->redirect(BackendModel::createURLForAction('index') .'&report=edited&var='. urlencode($item['name']) .'&highlight=row-'. $this->id);
			}
		}
	}
}

?>