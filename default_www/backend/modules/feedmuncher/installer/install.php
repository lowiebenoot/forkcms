<?php

/**
 * Installer for the feedmuncher module
 *
 * @package		installer
 * @subpackage	feedmuncher
 *
 * @author		Lowie Benoot <lowiebenoot@netlash.com>
 * @since		2.1
 */
class FeedmuncherInstall extends ModuleInstaller
{
	/**
	 * Add the default category for a language
	 *
	 * @return	int
	 * @param	string $language	The language to use.
	 * @param	string $title		The title of the category.
	 * @param	string $url			The URL for the category.
	 */
	private function addCategory($language, $title, $url)
	{
		// build array
		$item['meta_id'] = $this->insertMeta($title, $title, $title, $url);
		$item['language'] = (string) $language;
		$item['title'] = (string) $title;

		return (int) $this->getDB()->insert('feedmuncher_categories', $item);
	}


	/**
	 * Install the module
	 *
	 * @return	void
	 */
	protected function execute()
	{
		// load install.sql
		$this->importSQL(dirname(__FILE__) . '/data/install.sql');

		// add 'feedmuncher' as a module
		$this->addModule('feedmuncher', 'The feedmuncher module.');

		// general settings
		$this->setSetting('feedmuncher', 'allow_comments', true);
		$this->setSetting('feedmuncher', 'requires_akismet', true);
		$this->setSetting('feedmuncher', 'spamfilter', false);
		$this->setSetting('feedmuncher', 'moderation', true);
		$this->setSetting('feedmuncher', 'ping_services', true);
		$this->setSetting('feedmuncher', 'overview_num_items', 10);
		$this->setSetting('feedmuncher', 'recent_articles_full_num_items', 3);
		$this->setSetting('feedmuncher', 'recent_articles_list_num_items', 5);
		$this->setSetting('feedmuncher', 'max_num_revisions', 20);
		$this->setSetting('feedmuncher', 'auto_publish', true);

		// make module searchable
		$this->makeSearchable('feedmuncher');

		// module rights
		$this->setModuleRights(1, 'feedmuncher');

		// action rights
		$this->setActionRights(1, 'feedmuncher', 'add_category');
		$this->setActionRights(1, 'feedmuncher', 'add');
		$this->setActionRights(1, 'feedmuncher', 'articles');
		$this->setActionRights(1, 'feedmuncher', 'categories');
		$this->setActionRights(1, 'feedmuncher', 'comments');
		$this->setActionRights(1, 'feedmuncher', 'delete_article');
		$this->setActionRights(1, 'feedmuncher', 'delete_category');
		$this->setActionRights(1, 'feedmuncher', 'delete');
		$this->setActionRights(1, 'feedmuncher', 'edit_article');
		$this->setActionRights(1, 'feedmuncher', 'edit_category');
		$this->setActionRights(1, 'feedmuncher', 'edit_comment');
		$this->setActionRights(1, 'feedmuncher', 'edit');
		$this->setActionRights(1, 'feedmuncher', 'index');
		$this->setActionRights(1, 'feedmuncher', 'mass_comment_action');
		$this->setActionRights(1, 'feedmuncher', 'settings');
		$this->setActionRights(1, 'feedmuncher', 'get_articles');
		$this->setActionRights(1, 'feedmuncher', 'undo_delete');

		// add extra's
		$extraId = $this->insertExtra('feedmuncher', 'block', 'Feedmuncher', null, null, 'N', 8000);
		$this->insertExtra('feedmuncher', 'widget', 'RecentComments', 'recent_comments', null, 'N', 8001);
		$this->insertExtra('feedmuncher', 'widget', 'Categories', 'categories', null, 'N', 8002);
		$this->insertExtra('feedmuncher', 'widget', 'Archive', 'archive', null, 'N', 8003);
		$this->insertExtra('feedmuncher', 'widget', 'RecentArticlesFull', 'recent_articles_full', null, 'N', 8004);
		$this->insertExtra('feedmuncher', 'widget', 'RecentArticlesList', 'recent_articles_list', null, 'N', 8005);

		// loop languages
		foreach($this->getLanguages() as $language)
		{
			// fetch current categoryId
			$this->defaultCategoryId = $this->getCategory($language);

			// no category exists
			if($this->defaultCategoryId == 0)
			{
				// add category
				$this->defaultCategoryId = $this->addCategory($language, 'Default', 'default');
			}

			// feedburner URL
			$this->setSetting('feedmuncher', 'feedburner_url_' . $language, '');

			// RSS settings
			$this->setSetting('feedmuncher', 'rss_meta_' . $language, true);
			$this->setSetting('feedmuncher', 'rss_title_' . $language, 'RSS');
			$this->setSetting('feedmuncher', 'rss_description_' . $language, '');
			$this->setSetting('feedmuncher', 'link_to_original' , false);


			// check if a page for feedmuncher already exists in this language
			if(!(bool) $this->getDB()->getVar('SELECT COUNT(p.id)
												FROM pages AS p
												INNER JOIN pages_blocks AS b ON b.revision_id = p.revision_id
												WHERE b.extra_id = ? AND p.language = ?',
												array($extraId, $language)))
			{
				// insert page
				$this->insertPage(array('title' => 'Feedmuncher',
										'language' => $language),
									null,
									array('extra_id' => $extraId));
			}

		}

		// import locale
		$this->importLocale(dirname(__FILE__) . '/data/locale.xml');
	}


	/**
	 * Does the category with this id exist within this language.
	 *
	 * @return	bool
	 * @param	string $language	The langauge to use.
	 * @param	int $id				The id to exclude.
	 */
	private function existsCategory($language, $id)
	{
		return (bool) $this->getDB()->getVar('SELECT COUNT(id) FROM feedmuncher_categories WHERE id = ? AND language = ?', array((int) $id, (string) $language));
	}


	/**
	 * Fetch the id of the first category in this language we come across
	 *
	 * @return	int
	 * @param	string $language	The language to use.
	 */
	private function getCategory($language)
	{
		return (int) $this->getDB()->getVar('SELECT id FROM feedmuncher_categories WHERE language = ?', array((string) $language));
	}
}

?>
