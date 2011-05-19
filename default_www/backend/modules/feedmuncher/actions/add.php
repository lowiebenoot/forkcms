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
		$this->frm->addDropdown('type', array('feed' => 'feed', 'twitter' => 'twitter', 'delicious' => 'delicious'));
		$this->frm->addText('name', null, 255);
		$this->frm->addText('url');
		$this->frm->addText('website', null, 255);
		$this->frm->addDropdown('author', BackendUsersModel::getUsers(), BackendAuthentication::getUser()->getUserId());
		$this->frm->addCheckbox('auto_publish', BackendModel::getModuleSetting('feedmuncher', 'auto_publish'));
		$this->frm->addCheckbox('link_to_original');
		$this->frm->addCheckbox('aggregate_feed');
		$this->frm->addText('username');
		$this->frm->addDropdown('reoccurrence', array('daily' => BL::lbl('Daily'), 'weekly' => BL::lbl('Weekly')));
		$this->frm->addDropdown('day', array(1 => BL::lbl('Monday'), 2 => BL::lbl('Tuesday'), 3 => BL::lbl('Wednesday'), 4 => BL::lbl('Thursday'), 5 => BL::lbl('Friday'), 6 => BL::lbl('Saturday'), 7 => BL::lbl('Sunday')));
		$this->frm->addTime('time', null, 'inputText inputTime noFloat');

		// get feedmuncher categories and add the 'add category' item
		$feedmuncherCategories = BackendFeedmuncherModel::getCategories();
		$feedmuncherCategories['new_category'] = ucfirst(BL::getLabel('AddCategory'));

		// add dropdown for categories
		$this->frm->addDropdown('category', $feedmuncherCategories);

		// blog is installed?
		if($this->blogIsInstalled)
		{
			// get blog categories and add the 'add category' item
			$blogCategories = BackendFeedmuncherModel::getCategoriesFromBlog();
			$blogCategories['new_category'] = ucfirst(BL::getLabel('AddCategory'));

			// add radiobutton for target
			$this->frm->addRadiobutton('target', array(array('label' => BL::getLabel('PostInFeedmuncher'), 'value' => 'feedmuncher'), array('label' => BL::getLabel('PostInBlog'), 'value' => 'blog')), 'feedmuncher');
			$this->frm->addDropdown('category_blog', $blogCategories);
		}

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

			// get the feed type
			$type = $this->frm->getField('type')->getValue();

			// is the name filled in?
			$this->frm->getField('name')->isFilled(BL::err('NameIsRequired'));

			// type is feed?
			if($type == 'feed')
			{
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
			}

			// type is twitter or delicious
			else
			{
				// get the username field
				$username = $this->frm->getField('username');

				// username filled in?
				if($username->isFilled(BL::err('UsernameIsRequired')))
				{
					// type is twitter?
					if($type == 'twitter')
					{
						// is it a possible username?
						if(!(bool) preg_match('/^[a-z0-9_]+$/i', $username->getValue())) $username->addError(BL::err('UsernameTwitterIsInvalid'));
					}

					// type is delicious
					if($type == 'delicious')
					{
						// is it a possible username?
						if(!(bool) preg_match('/^[a-z0-9_-]+$/i', $username->getValue())) $username->addError(BL::err('UsernameDeliciousIsInvalid'));
					}

					// no errors on the username field yet?
					if($username->getErrors() == null)
					{
						// already exists?
						if(BackendFeedmuncherModel::existsByUsernameAndType($username->getValue(), $type))
						{
							// is it deleted before?
							if(BackendFeedmuncherModel::feedDeletedBeforeByUsernameAndType($username->getValue(), $type)) $username->addError(sprintf(BL::err('FeedWasDeletedBefore'), BackendModel::createURLForAction('undo_delete', null, null, array('username' => $username->getValue(), 'type' => $type))));

							// not deleted before
							else $username->addError(BL::getError('UsernameAlreadyExists'));
						}
					}
				}
			}

			// are we aggregating?
			if($type != 'feed' || ($type == 'feed' && $this->frm->getField('aggregate_feed')->isChecked()))
			{
				// set aggregating to true
				$aggregating = true;

				// time filled in and correct?
				if($this->frm->getField('time')->isFilled(BL::err('TimeIsRequired'))) $this->frm->getField('time')->isValid(BL::err('TimeIsInvalid'));
			}

			// not aggregating
			else $aggregating = false;

			// is the form correct?
			if($this->frm->isCorrect())
			{
				// build item
				$item['name'] = $this->frm->getField('name')->getValue();
				$item['feed_type'] = $type;
				$item['url'] = $type == 'feed' ? $this->frm->getField('url')->getValue() : null;
				$item['source'] = $type == 'feed' ? $this->frm->getField('website')->getValue() : $this->frm->getField('username')->getValue();
				$item['author_user_id'] = (int) $this->frm->getField('author')->getValue();
				$item['target'] = ($this->blogIsInstalled) ? $this->frm->getField('target')->getValue() : 'feedmuncher';
				$item['category_id'] = $item['target'] == 'feedmuncher' ? (int) $this->frm->getField('category')->getValue() : (int) $this->frm->getField('category_blog')->getValue();
				$item['auto_publish'] = $this->frm->getField('auto_publish')->isChecked() == true ? 'Y' : 'N';
				$item['link_to_original'] = $this->frm->getField('link_to_original')->isChecked() == true && $type == 'feed' ? 'Y' : 'N';
				$item['language'] = BL::getWorkingLanguage();
				$item['date_fetched'] = null;

				// type is not feed, so we need a reoccurrence
				if($aggregating)
				{
					// create array for the reoccurrence
					$reoccurrence['reoccurrence'] = $this->frm->getField('reoccurrence')->getValue();
					$reoccurrence['day'] = $reoccurrence['reoccurrence'] == 'weekly' ? $this->frm->getField('day')->getValue() : null;
					$reoccurrence['time'] = $this->frm->getField('time')->getValue();

					// add the reocurrence to the item as serialized data
					$item['reoccurrence'] = serialize($reoccurrence);
				}

				// insert in DB
				$feedId = BackendFeedmuncherModel::insert($item);

				// return to the feeds overview
				$this->redirect(BackendModel::createURLForAction('index') . '&report=addedFeed&var=' . urlencode($item['name']) . '&highlight=row-' . $feedId);
			}
		}
	}
}

?>