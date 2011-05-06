<?php

/**
 * This is the edit-action, it will display a form to edit an existing item
 *
 * @package		backend
 * @subpackage	banners
 *
 * @author		Lowie Benoot <lowie@netlash.com>
 * @since		2.1
 */
class BackendBannersEdit extends BackendBaseActionEdit
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
		if($this->id !== null && BackendBannersModel::exists($this->id))
		{
			// call parent, this will probably add some general CSS/JS or other required files
			parent::execute();

			// get all data for the item we want to edit
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
	 * Get the data
	 * If a revision-id was specified in the URL we load the revision and not the actual data.
	 *
	 * @return	void
	 */
	private function getData()
	{
		// get the record
		$this->record = (array) BackendBannersModel::getBanner($this->id);

		// no item found, throw an exceptions, because somebody is fucking with our URL
		if(empty($this->record)) $this->redirect(BackendModel::createURLForAction('index') . '&error=non-existing');
	}


	/**
	 * Load the form
	 *
	 * @return	void
	 */
	private function loadForm()
	{
		// create form
		$this->frm = new BackendForm('edit');

		// show permanently?
		if($this->record['date_from'] == null || $this->record['date_till'] == null) $showPermanently = true;
		else $showPermanently = false;

		// create elements
		$this->frm->addText('name', $this->record['name']);
		$this->frm->addText('url', $this->record['url']);
		$this->frm->addImage('file');
		$this->frm->addDate('start_date', $showPermanently ? null : $this->record['date_from']);
		$this->frm->addTime('start_time', $showPermanently ? null : date('H:i', $this->record['date_from']), 'inputText time');
		$this->frm->addDate('end_date', $showPermanently ? strtotime('+1 month') : $this->record['date_till']);
		$this->frm->addTime('end_time', $showPermanently ? null : date('H:i', $this->record['date_till']), 'inputText time');
		$this->frm->addCheckbox('show_permanently', $showPermanently);
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

		// get the standard
		$standard = BackendBannersModel::getStandard($this->record['standard_id']);

		// parse the name of the standard
		$this->tpl->assign('standard', $standard);

		// parse record
		$this->tpl->assign('item', $this->record);

		// is the file an swf?
		$this->tpl->assign('isSWF', SpoonFile::getExtension($this->record['file']) == 'swf');

		// is the banner the last member of a group?
		$this->tpl->assign('isOnlyMemberOfAGroup', BackendBannersModel::isOnlyMemberOfAGroup($this->id));

		// parse the groups where the banner is member of
		$this->tpl->assign('groups', BackendBannersModel::getGroupsByBanner($this->id));

		// parse tracker url
		$this->tpl->assign('trackerUrl', SITE_URL . BackendModel::getURLForBlock('banners', 'tracker') . '?url=');
		$this->tpl->assign('url', urlencode($this->record['url']));
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

			// show permanently?
			$showPermanently = $this->frm->getField('show_permanently')->isChecked();

			// validate fields
			$this->frm->getField('name')->isFilled(BL::err('TitleIsRequired'));
			if($this->frm->getField('url')->isFilled(BL::err('UrlIsRequired'))) $this->frm->getField('url')->isURL(BL::err('InvalidURL'));

			// an array that is used to check if everything is ok with the dates
			$datesOK = array();

			// check dates if the banner isn't shown permanently
			if(!$showPermanently)
			{
				// validate the dates and times
				$datesOK[] = $this->frm->getField('start_date')->isFilled() ? $this->frm->getField('start_date')->isValid(BL::err('StartDateIsInvalid')) : $this->frm->getField('start_date')->isFilled(BL::err('StartDateIsRequired'));
				$datesOK[] = $this->frm->getField('end_date')->isFilled() ? $this->frm->getField('end_date')->isValid(BL::err('EndDateIsInvalid')) : $this->frm->getField('end_date')->isFilled(BL::err('EndDateIsRequired'));
				$datesOK[] = $this->frm->getField('start_time')->isFilled() ? $this->frm->getField('start_time')->isValid(BL::err('StartTimeIsInvalid')) : $this->frm->getField('start_time')->isFilled(BL::err('StartTimeIsRequired'));
				$datesOK[] = $this->frm->getField('end_time')->isFilled() ? $this->frm->getField('end_time')->isValid(BL::err('EndTimeIsInvalid')) : $this->frm->getField('end_time')->isFilled(BL::err('EndTimeIsRequired'));

				// all dates and times filled in and valid?
				if(!in_array(false, $datesOK))
				{
					// start date before end date?
					$date_from = BackendModel::getUTCTimestamp($this->frm->getField('start_date'), $this->frm->getField('start_time'));
					$date_till = BackendModel::getUTCTimestamp($this->frm->getField('end_date'), $this->frm->getField('end_time'));
					if($date_from > $date_till) $this->frm->getField('end_time')->addError(BL::err('EndDateMustBeAfterBeginDate'));
				}
			}

			// is the file filled in?
			if($this->frm->getField('file')->isFilled())
			{
				// correct extension?
				if($this->frm->getField('file')->isAllowedExtension(array('jpg', 'jpeg', 'gif', 'png', 'swf'), BL::getError('JPGGIFPNGAndSWFOnly')))
				{
					// correct mimetype?
					$this->frm->getField('file')->isAllowedMimeType(array('image/gif', 'image/jpg', 'image/jpeg', 'image/png', 'application/x-shockwave-flash'), BL::getError('JPGGIFPNGAndSWFOnly'));
				}
			}

			// no errors?
			if($this->frm->isCorrect())
			{
				// build item
				$item['name'] = $this->frm->getField('name')->getValue();
				$item['url'] = $this->frm->getField('url')->getValue();
				if($this->frm->getField('file')->isFilled()) $item['file'] = SpoonFilter::urlise($this->frm->getField('file')->getFilename(false)) . '.' . $this->frm->getField('file')->getExtension();
				$item['date_from'] = $showPermanently ? null : BackendModel::getUTCDate(null, $date_from);
				$item['date_till'] = $showPermanently ? null : BackendModel::getUTCDate(null, $date_till);

				// update in db
				BackendBannersModel::updateBanner($this->id, $item);

				// get the banner standard
				$standard = BackendBannersModel::getStandard($this->record['standard_id']);

				// file is filled?
				if($this->frm->getField('file')->isFilled())
				{
					// delete the old files
					if(SpoonFile::exists(FRONTEND_FILES_PATH . '/banners/resized/' . $this->id . '_' . $this->record['file'])) SpoonFile::delete(FRONTEND_FILES_PATH . '/banners/resized/' . $this->id . '_' . $this->record['file']);
					SpoonFile::delete(FRONTEND_FILES_PATH . '/banners/original/' . $this->id . '_' . $this->record['file']);

					// is the upload file an image?
					if($this->frm->getField('file')->getExtension() != 'swf')
					{
						// create resized image
						$this->frm->getField('file')->createThumbnail(FRONTEND_FILES_PATH . '/banners/resized/' . $this->id . '_' . $item['file'], (int) $standard['width'], (int) $standard['height'], true, false, 100);
					}

					// save original file
					$this->frm->getField('file')->moveFile(FRONTEND_FILES_PATH . '/banners/original/' . $this->id . '_' . $item['file']);
				}

				// everything is saved, so redirect to the overview
				$this->redirect(BackendModel::createURLForAction('index') . '&report=editedBanner&var=' . urlencode($item['name']) . '&highlight=id-' . $this->id);
			}
		}
	}
}

?>