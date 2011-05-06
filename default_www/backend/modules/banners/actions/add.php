<?php

/**
 * This is the add-action, it will display a form to create a new item
 *
 * @package		backend
 * @subpackage	banners
 *
 * @author		Lowie Benoot <lowiebenoot@netlash.com>
 * @since		2.2
 */
class BackendBannersAdd extends BackendBaseActionAdd
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
		$this->frm = new BackendForm('add');

		// create elements
		$this->frm->addText('name');
		$this->frm->addText('url');
		$this->frm->addDropdown('size', BackendBannersModel::getStandards());
		$this->frm->addImage('file');
		$this->frm->addDate('start_date');
		$this->frm->addTime('start_time', null, 'inputText time');
		$this->frm->addDate('end_date', strtotime('+1 month'));
		$this->frm->addTime('end_time', null, 'inputText time');
		$this->frm->addCheckbox('show_permanently');

		// parse tracker url
		$this->tpl->assign('trackerUrl', SITE_URL . BackendModel::getURLForBlock('banners', 'tracker') . '?url=');
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
			if($this->frm->getField('file')->isFilled(BL::err('FileIsRequired')))
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
				// get the extension of the file
				$extension = $this->frm->getField('file')->getExtension();

				// build item
				$item['name'] = $this->frm->getField('name')->getValue();
				$item['standard_id'] = (int) $this->frm->getField('size')->getValue();
				$item['url'] = $this->frm->getField('url')->getValue();
				$item['file'] = SpoonFilter::urlise($this->frm->getField('file')->getFilename(false)) . '.' . $extension;
				$item['date_from'] = $showPermanently ? null : BackendModel::getUTCDate(null, $date_from);
				$item['date_till'] = $showPermanently ? null : BackendModel::getUTCDate(null, $date_till);
				$item['language'] = BL::getWorkingLanguage();

				// insert in db
				$bannerId = BackendBannersModel::insertBanner($item);

				// get the banner standard
				$standard = BackendBannersModel::getStandard($item['standard_id']);

				// is the upload file an image?
				if($extension != 'swf')
				{
					// create resized image
					$this->frm->getField('file')->createThumbnail(FRONTEND_FILES_PATH . '/banners/resized/' . $bannerId . '_' . $item['file'], (int) $standard['width'], (int) $standard['height'], true, false, 100);
				}

				// save original file
				$this->frm->getField('file')->moveFile(FRONTEND_FILES_PATH . '/banners/original/' . $bannerId . '_' . $item['file']);

				// everything is saved, so redirect to the overview
				$this->redirect(BackendModel::createURLForAction('index') . '&report=addedBanner&var=' . urlencode($item['name']) . '&highlight=id-' . $bannerId);
			}
		}
	}
}

?>