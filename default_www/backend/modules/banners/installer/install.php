<?php

/**
 * Installer for the banners module
 *
 * @package		installer
 * @subpackage	banners
 *
 * @author		Lowie Benoot <lowie@netlash.com>
 * @since		2.1
 */
class BannersInstall extends ModuleInstaller
{
	/**
	 * Install the module
	 *
	 * @return	void
	 */
	protected function execute()
	{
		// load install.sql
		$this->importSQL(dirname(__FILE__) . '/data/install.sql');

		// add 'blog' as a module
		$this->addModule('banners', 'The banners module.');

		// general settings

		// module rights
		$this->setModuleRights(1, 'banners');

		// action rights
		$this->setActionRights(1, 'banners', 'add');
		$this->setActionRights(1, 'banners', 'add_group');
		$this->setActionRights(1, 'banners', 'delete');
		$this->setActionRights(1, 'banners', 'delete_group');
		$this->setActionRights(1, 'banners', 'edit');
		$this->setActionRights(1, 'banners', 'edit_group');
		$this->setActionRights(1, 'banners', 'groups');
		$this->setActionRights(1, 'banners', 'index');

		// insert extra for the tracker page
		$extraId = $this->insertExtra('banners', 'block', 'tracker', 'tracker', null, true, 9000);

		// loop languages
		foreach($this->getLanguages() as $language)
		{
			// insert page
			$this->insertPage(array('title' => 'BannerTracker',
									'language' => $language),
									null,
									array('extra_id' => $extraId));
		}

		// create directory for the original files
		if(!SpoonDirectory::exists(FRONTEND_FILES_PATH . '/banners/original/')) SpoonDirectory::create(FRONTEND_FILES_PATH . '/banners/original/');

		// create folder for resized images
		if(!SpoonDirectory::exists(FRONTEND_FILES_PATH . '/banners/resized/')) SpoonDirectory::create(FRONTEND_FILES_PATH . '/banners/resized/');

		// insert locale (nl)
		$this->insertLocale('nl', 'backend', 'banners', 'err', 'EndDateIsInvalid', 'Ongeldige einddatum.');
		$this->insertLocale('nl', 'backend', 'banners', 'err', 'EndDateIsRequired', 'Kies een einddatum.');
		$this->insertLocale('nl', 'backend', 'banners', 'err', 'EndDateMustBeAfterBeginDate', 'De einddatum moet na de begindatum liggen.');
		$this->insertLocale('nl', 'backend', 'banners', 'err', 'EndTimeIsInvalid', 'Ongeldig eindtijdstip.');
		$this->insertLocale('nl', 'backend', 'banners', 'err', 'EndTimeIsRequired', 'Kies een eindtijdstip.');
		$this->insertLocale('nl', 'backend', 'banners', 'err', 'FileIsRequired', 'Kies een bestand.');
		$this->insertLocale('nl', 'backend', 'banners', 'err', 'JPGGIFPNGAndSWFOnly', 'Enkel JPG, GIF, PNG en SWF zijn toegestaan.');
		$this->insertLocale('nl', 'backend', 'banners', 'err', 'SelectAtLeastOneBanner', 'Selecteer minstens 1 banner.');
		$this->insertLocale('nl', 'backend', 'banners', 'err', 'StartDateIsInvalid', 'Ongeldige startdatum.');
		$this->insertLocale('nl', 'backend', 'banners', 'err', 'StartDateIsRequired', 'Kies een startdatum.');
		$this->insertLocale('nl', 'backend', 'banners', 'err', 'StartTimeIsInvalid', 'Ongeldig starttijdstip.');
		$this->insertLocale('nl', 'backend', 'banners', 'err', 'StartTimeIsRequired', 'Kies een starttijdstip.');
		$this->insertLocale('nl', 'backend', 'banners', 'err', 'UrlIsRequired', 'Geef een URL in.');
		$this->insertLocale('nl', 'backend', 'banners', 'lbl', 'AddBanner', 'banner toevoegen');
		$this->insertLocale('nl', 'backend', 'banners', 'lbl', 'AddGroup', 'groep toevoegen');
		$this->insertLocale('nl', 'backend', 'banners', 'lbl', 'CurrentFile', 'huidig bestand');
		$this->insertLocale('nl', 'backend', 'banners', 'lbl', 'DateFrom', 'begindatum');
		$this->insertLocale('nl', 'backend', 'banners', 'lbl', 'DateTill', 'einddatum');
		$this->insertLocale('nl', 'backend', 'banners', 'lbl', 'EditBanner', 'wijzig banner');
		$this->insertLocale('nl', 'backend', 'banners', 'lbl', 'EditGroup', 'wijzig groep');
		$this->insertLocale('nl', 'backend', 'banners', 'lbl', 'Groups', 'groepen');
		$this->insertLocale('nl', 'backend', 'banners', 'lbl', 'IsMemberOf', 'deze banner zit in de volgende groepen');
		$this->insertLocale('nl', 'backend', 'banners', 'lbl', 'NumClicks', 'kliks');
		$this->insertLocale('nl', 'backend', 'banners', 'lbl', 'NumViews', 'bekeken');
		$this->insertLocale('nl', 'backend', 'banners', 'lbl', 'Size', 'grootte');
		$this->insertLocale('nl', 'backend', 'banners', 'msg', 'AddedBanner', 'De banner "%1$s" werd toegevoegd.');
		$this->insertLocale('nl', 'backend', 'banners', 'msg', 'AddedGroup', 'De groep "%1$s" werd toegevoegd.');
		$this->insertLocale('nl', 'backend', 'banners', 'msg', 'ConfirmDeleteBanner', 'Ben je zeker dat je de banner "%1$s" wil verwijderen?');
		$this->insertLocale('nl', 'backend', 'banners', 'msg', 'ConfirmDeleteGroup', 'Ben je zeker dat je de groep "%1$s" wil verwijderen?');
		$this->insertLocale('nl', 'backend', 'banners', 'msg', 'DeletedBanner', 'De banner "%1$s" werd verwijderd.');
		$this->insertLocale('nl', 'backend', 'banners', 'msg', 'DeletedGroup', 'De groep "%1$s" werd verwijderd.');
		$this->insertLocale('nl', 'backend', 'banners', 'msg', 'EditedBanner', 'De banner "%1$s" werd opgeslagen.');
		$this->insertLocale('nl', 'backend', 'banners', 'msg', 'EditedGroup', 'De groep "%1$s" werd opgeslagen.');
		$this->insertLocale('nl', 'backend', 'banners', 'msg', 'IsOnlyMemberOfAGroup', 'Deze banner zit als enige in een groep, daardoor kan hij niet verwijderd worden.');
		$this->insertLocale('nl', 'backend', 'banners', 'msg', 'NoBanners', 'Er zijn nog geen banners. <a href="%1$s">Voeg een banner toe</a>.');
		$this->insertLocale('nl', 'backend', 'banners', 'msg', 'NoGroups', 'Er zijn nog geen groepen. <a href="%1$s">Voeg een groep toe</a>.');
		$this->insertLocale('nl', 'backend', 'core', 'lbl', 'Banners', 'banners');

		// insert locale (en)
		$this->insertLocale('en', 'backend', 'banners', 'err', 'EndDateIsInvalid', 'Invalid end date.');
		$this->insertLocale('en', 'backend', 'banners', 'err', 'EndDateIsRequired', 'Provide an end date.');
		$this->insertLocale('en', 'backend', 'banners', 'err', 'EndDateMustBeAfterBeginDate', 'The end date should be after the begin date.');
		$this->insertLocale('en', 'backend', 'banners', 'err', 'EndTimeIsInvalid', 'Invalid end time.');
		$this->insertLocale('en', 'backend', 'banners', 'err', 'EndTimeIsRequired', 'Provade an end time.');
		$this->insertLocale('en', 'backend', 'banners', 'err', 'FileIsRequired', 'Provide a file.');
		$this->insertLocale('en', 'backend', 'banners', 'err', 'JPGGIFPNGAndSWFOnly', 'Only JPG, GIF, PNG and SWF are allowed.');
		$this->insertLocale('en', 'backend', 'banners', 'err', 'SelectAtLeastOneBanner', 'Select at least 1 banner.');
		$this->insertLocale('en', 'backend', 'banners', 'err', 'StartDateIsInvalid', 'Invalid start date.');
		$this->insertLocale('en', 'backend', 'banners', 'err', 'StartDateIsRequired', 'Provide a start date.');
		$this->insertLocale('en', 'backend', 'banners', 'err', 'StartTimeIsInvalid', 'Invalid start time.');
		$this->insertLocale('en', 'backend', 'banners', 'err', 'StartTimeIsRequired', 'Provide a start time.');
		$this->insertLocale('en', 'backend', 'banners', 'err', 'UrlIsRequired', 'Provide an URL.');
		$this->insertLocale('en', 'backend', 'banners', 'lbl', 'AddBanner', 'add banner');
		$this->insertLocale('en', 'backend', 'banners', 'lbl', 'AddGroup', 'add group');
		$this->insertLocale('en', 'backend', 'banners', 'lbl', 'CurrentFile', 'current file');
		$this->insertLocale('en', 'backend', 'banners', 'lbl', 'DateFrom', 'start date');
		$this->insertLocale('en', 'backend', 'banners', 'lbl', 'DateTill', 'end date');
		$this->insertLocale('en', 'backend', 'banners', 'lbl', 'EditBanner', 'edit banner');
		$this->insertLocale('en', 'backend', 'banners', 'lbl', 'EditGroup', 'edit group');
		$this->insertLocale('en', 'backend', 'banners', 'lbl', 'Groups', 'groups');
		$this->insertLocale('en', 'backend', 'banners', 'lbl', 'IsMemberOf', 'this banner is member of the following groups');
		$this->insertLocale('en', 'backend', 'banners', 'lbl', 'NumClicks', 'clicks');
		$this->insertLocale('en', 'backend', 'banners', 'lbl', 'NumViews', 'views');
		$this->insertLocale('en', 'backend', 'banners', 'lbl', 'Size', 'size');
		$this->insertLocale('en', 'backend', 'banners', 'msg', 'AddedBanner', 'The banner "%1$s" was added.');
		$this->insertLocale('en', 'backend', 'banners', 'msg', 'AddedGroup', 'The group "%1$s" was added.');
		$this->insertLocale('en', 'backend', 'banners', 'msg', 'ConfirmDeleteBanner', 'Are you sure you want to delete the banner "%1$s"?');
		$this->insertLocale('en', 'backend', 'banners', 'msg', 'ConfirmDeleteGroup', 'Are you sure you want to delete the group "%1$s"?');
		$this->insertLocale('en', 'backend', 'banners', 'msg', 'DeletedBanner', 'The banner "%1$s" was deleted.');
		$this->insertLocale('en', 'backend', 'banners', 'msg', 'DeletedGroup', 'The group "%1$s" was deleted.');
		$this->insertLocale('en', 'backend', 'banners', 'msg', 'EditedBanner', 'The banner "%1$s" was saved.');
		$this->insertLocale('en', 'backend', 'banners', 'msg', 'EditedGroup', 'The group "%1$s" was saved.');
		$this->insertLocale('en', 'backend', 'banners', 'msg', 'IsOnlyMemberOfAGroup', 'This banner is the last member of a group, so it can\'t be deleted.');
		$this->insertLocale('en', 'backend', 'banners', 'msg', 'NoBanners', 'There are no banners yet. <a href="%1$s">Add a banner</a>.');
		$this->insertLocale('en', 'backend', 'banners', 'msg', 'NoGroups', 'There are no groups yet. <a href="%1$s">Add a group</a>.');
		$this->insertLocale('en', 'backend', 'core', 'lbl', 'Banners', 'banners');
	}

}

?>
