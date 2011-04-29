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
									'language' => $language,
									'type' => 'root',
									'allow_delete' => 'N',
									'allow_edit' => 'N',
									'allow_move' => 'N',
									'allow_children' => 'N',
									'no_follow' => 'Y',
									'parent_id' => 0),
									null,
									array('extra_id' => $extraId));
		}

		// create directory for the original files
		if(!SpoonDirectory::exists(FRONTEND_FILES_PATH . '/banners/')) SpoonDirectory::create(FRONTEND_FILES_PATH . '/banners/');

		// create directory for the original files
		if(!SpoonDirectory::exists(FRONTEND_FILES_PATH . '/banners/original/')) SpoonDirectory::create(FRONTEND_FILES_PATH . '/banners/original/');

		// create folder for resized images
		if(!SpoonDirectory::exists(FRONTEND_FILES_PATH . '/banners/resized/')) SpoonDirectory::create(FRONTEND_FILES_PATH . '/banners/resized/');

		// import locale
		$this->importLocale(dirname(__FILE__) . '/data/locale.xml');
	}

}

?>
