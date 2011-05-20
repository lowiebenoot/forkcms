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
		else $this->redirect(BackendModel::createURLForAction('index') . '&error=non-existing');
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
		$this->frm = new BackendForm('editFeed');

		// publishing in feedmuncher?
		if($this->record['target'] == 'feedmuncher')
		{
			// get current category
			$feedmuncherCategory = $this->record['category_id'];

			// get default category for blog
			$blogCategory = BackendModel::getModuleSetting('blog', 'default_category_' . BL::getWorkingLanguage());
		}

		// publishing in blog
		else
		{
			// get current category
			$blogCategory = $this->record['category_id'];

			// get default category for feedmuncher
			$feedmuncherCategory = BackendModel::getModuleSetting('feedmuncher', 'default_category_' . BL::getWorkingLanguage());
		}

		// get default category id
		$defaultCategoryId = BackendModel::getModuleSetting('feedmuncher', 'default_category_' . BL::getWorkingLanguage());

		// did the feed already post something?
		$feedHasArticles = BackendFeedmuncherModel::feedHasArticles($this->id);

		// aggregating feed?
		$aggregating = $this->record['reoccurrence'] != null;

		// create elements
		$this->frm->addText('name', $this->record['name'], 255);
		$this->frm->addDropdown('author', BackendUsersModel::getUsers(), $this->record['author_user_id']);
		$this->frm->addCheckbox('auto_publish', ($this->record['auto_publish'] == 'Y'));

		// type is feed?
		if($this->record['feed_type'] == 'feed')
		{
			$this->frm->addText('url', $this->record['url']);
			$this->frm->addText('website', $this->record['source'], 255);
			$this->frm->addCheckbox('aggregate_feed', $this->record['reoccurrence'] != null);
			$this->frm->addCheckbox('link_to_original', ($this->record['link_to_original'] == 'Y'));
		}

			// try to unserialize the reoccurrence.
			$reoccurrence = unserialize($this->record['reoccurrence']);

			// add fields
			if($this->record['feed_type'] != 'feed') $this->frm->addText('username', $this->record['source']);

			$this->frm->addDropdown('reoccurrence', array('daily' => BL::lbl('Daily'), 'weekly' => BL::lbl('Weekly')), ($reoccurrence ? $reoccurrence['reoccurrence'] : null));
			$this->frm->addDropdown('day', array(1 => BL::lbl('Monday'), 2 => BL::lbl('Tuesday'), 3 => BL::lbl('Wednesday'), 4 => BL::lbl('Thursday'), 5 => BL::lbl('Friday'), 6 => BL::lbl('Saturday'), 7 => BL::lbl('Sunday')), ($reoccurrence ? $reoccurrence['day'] : null));
			$this->frm->addTime('time', ($reoccurrence ? $reoccurrence['time'] : null), 'inputText inputTime noFloat');

		// blog is installed?
		if($this->blogIsInstalled)
		{
			// get blog categories and add the 'add category' item
			$blogCategories = BackendFeedmuncherModel::getCategoriesFromBlog();
			$blogCategories['new_category'] = ucfirst(BL::getLabel('AddCategory'));

			// add radiobutton for target
			$this->frm->addRadiobutton('target', array(array('label' => BL::getLabel('PostInFeedmuncher'), 'value' => 'feedmuncher', 'attributes' => $feedHasArticles ? array('disabled' => '') : null), array('label' => BL::getLabel('PostInBlog'), 'value' => 'blog', 'attributes' => $feedHasArticles ? array('disabled' => '') : null)), $this->record['target']);

			// add dropdown for blog categories
			$this->frm->addDropdown('category_blog', $blogCategories, $blogCategory);
		}

		// get feedmuncher categories and add the 'add category' item
		$feedmuncherCategories = BackendFeedmuncherModel::getCategories();
		$feedmuncherCategories['new_category'] = ucfirst(BL::getLabel('AddCategory'));

		// add dropdown for feedmuncher categories
		$this->frm->addDropdown('category', $feedmuncherCategories, $feedmuncherCategory);
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

		// is the feed deleted?
		if($this->record['deleted'] == 'Y')
		{
			// assign option
			$this->tpl->assign('isDeleted', true);

			// assign restore message
			$this->tpl->assign('restoreURL', BackendModel::createURLForAction('undo_delete') . '&amp;url=' . $this->record['url']);
		}

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

			// type is feed?
			if($this->record['feed_type'] == 'feed')
			{
				// is the url filled in?
				if($this->frm->getField('url')->isFilled(BL::err('UrlIsRequired')))
				{
					// is the url a valid urL?
					if($this->frm->getField('url')->isURL(BL::err('UrlIsInvalid')))
					{
						// did the url change? otherwise there is no reason to check for existence
						if($this->record['url'] != $this->frm->getField('url')->getValue())
						{
							// is there already a feed with that url (and same language)
							if(BackendFeedmuncherModel::existsByURL($this->frm->getField('url')->getValue()))
							{
								// search for deleted feed
								$deletedFeedId = BackendFeedmuncherModel::searchForDeletedFeed($this->frm->getField('url')->getValue());

								// is it deleted before?
								if($deletedFeedId != 0) $this->frm->getField('url')->addError(sprintf(BL::err('FeedWasDeletedBefore'), BackendModel::createURLForAction('undo_delete', null, null, array('id' => $deletedFeedId))));

								// not deleted before
								else $this->frm->getField('url')->addError(BL::getError('FeedAlreadyExists'));
							}
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
					if($this->record['feed_type'] == 'twitter')
					{
						// is it a possible username?
						if(!(bool) preg_match('/^[a-z0-9_]+$/i', $username->getValue())) $username->addError(BL::err('UsernameTwitterIsInvalid'));
					}

					// type is delicious
					if($this->record['feed_type'] == 'delicious')
					{
						// is it a possible username?
						if(!(bool) preg_match('/^[a-z0-9_-]+$/i', $username->getValue())) $username->addError(BL::err('UsernameDeliciousIsInvalid'));
					}

					// no errors on the username field yet?
					if($username->getErrors() == null)
					{
						// did the username change? Otherwise there is no reason to check for existence
						if($this->record['source'] != $username->getValue())
						{
							// already exists?
							if(BackendFeedmuncherModel::existsByUsernameAndType($username->getValue(), $this->record['feed_type']))
							{
								// search for a deleted feed with this username and type
								$deletedFeedId = BackendFeedmuncherModel::searchForDeletedFeed(null, $username->getValue(), $this->record['feed_type']);

								// is it deleted before?
								if($deletedFeedId != 0) $username->addError(sprintf(BL::err('FeedWasDeletedBeforeUsername'), BackendModel::createURLForAction('undo_delete', null, null, array('id' => $deletedFeedId))));

								// not deleted before
								else $username->addError(BL::getError('UsernameAlreadyExists'));
							}
						}
					}
				}
			}

			// are we aggregating?
			if($this->record['feed_type'] != 'feed' || ($this->record['feed_type'] == 'feed' && $this->frm->getField('aggregate_feed')->isChecked()))
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
				$item['url'] = $this->record['feed_type'] == 'feed' ? $this->frm->getField('url')->getValue() : null;
				$item['source'] = $this->record['feed_type'] == 'feed' ? $this->frm->getField('website')->getValue() : $this->frm->getField('username')->getValue();
				$item['author_user_id'] = (int) $this->frm->getField('author')->getValue();
				$item['auto_publish'] = $this->frm->getField('auto_publish')->isChecked() == true ? 'Y' : 'N';
				$item['link_to_original'] = $this->record['feed_type'] == 'feed' ? ($this->frm->getField('link_to_original')->isChecked() == true ? 'Y' : 'N') : 'N';
				$item['language'] = BL::getWorkingLanguage();
				$item['category_id'] = $this->record['target'] == 'feedmuncher' ? (int) $this->frm->getField('category')->getValue() : (int) $this->frm->getField('category_blog')->getValue();
				if(!BackendFeedmuncherModel::feedHasArticles($this->id)) $item['target'] = ($this->blogIsInstalled) ? $this->frm->getField('target')->getValue() : 'feedmuncher';

				// type is not feed, so we need a reoccurrence
				if($aggregating)
				{
					// create array for the reoccurence
					$reoccurrence['reoccurrence'] = $this->frm->getField('reoccurrence')->getValue();
					$reoccurrence['day'] = $reoccurrence['reoccurrence'] == 'weekly' ? $this->frm->getField('day')->getValue() : null;
					$reoccurrence['time'] = $this->frm->getField('time')->getValue();

					// add the reocurrence to the item as serialized data
					$item['reoccurrence'] = serialize($reoccurrence);
				}

				// set reoccurrence to null if not aggregating
				else $item['reoccurrence'] = null;

				// insert in DB
				BackendFeedmuncherModel::update($this->id, $item);

				// return to the feeds overview
				$this->redirect(BackendModel::createURLForAction('index') . '&report=editedFeed&var=' . urlencode($item['name']) . '&highlight=row-' . $this->id);
			}
		}
	}
}

?>