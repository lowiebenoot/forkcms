<?php

/**
 * Installer for the blog module
 *
 * @package		installer
 * @subpackage	blog
 *
 * @author		Davy Hellemans <davy@netlash.com>
 * @author		Tijs Verkoyen <tijs@netlash.com>
 * @author		Matthias Mullie <matthias@netlash.com>
 * @since		2.0
 */
class BlogInstall extends ModuleInstaller
{
	/**
	 * Add the default category for a language
	 *
	 * @return	int
	 * @param	string $language	The language to use.
	 * @param	string $name		The name of the category.
	 * @param	string $url			The URL for the category.
	 */
	private function addCategory($language, $name, $url)
	{
		return (int) $this->getDB()->insert('blog_categories', array('language' => (string) $language, 'name' => (string) $name, 'url' => (string) $url));
	}


	/**
	 * Install the module
	 *
	 * @return	void
	 */
	protected function execute()
	{
		// load install.sql
		$this->importSQL(dirname(__FILE__) .'/data/install.sql');

		// add 'blog' as a module
		$this->addModule('banners', 'The banners module.');

		// general settings

		// make module searchable
		$this->makeSearchable('blog');

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

		// add extra's

		// insert locale (nl)
	}

}

?>
