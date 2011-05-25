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

		// create directory for the original files
		if(!SpoonDirectory::exists(PATH_WWW . '/frontend/files/banners/')) SpoonDirectory::create(PATH_WWW . '/frontend/files/banners/');

		// create directory for the original files
		if(!SpoonDirectory::exists(PATH_WWW . '/frontend/files/banners/original/')) SpoonDirectory::create(PATH_WWW . '/frontend/files/banners/original/');

		// create folder for resized images
		if(!SpoonDirectory::exists(PATH_WWW . '/frontend/files/banners/resized/')) SpoonDirectory::create(PATH_WWW . '/frontend/files/banners/resized/');

		// import locale
		$this->importLocale(dirname(__FILE__) . '/data/locale.xml');
	}

}

?>
