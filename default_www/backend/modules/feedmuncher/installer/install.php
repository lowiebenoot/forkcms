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
	 * @param	string $name		The name of the category.
	 * @param	string $url			The URL for the category.
	 */
	private function addCategory($language, $name, $url)
	{
		return (int) $this->getDB()->insert('feedmuncher_categories', array('language' => (string) $language, 'name' => (string) $name, 'url' => (string) $url));
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
		$this->addModule('feedmuncher', 'The feemuncher module.');

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
		$this->setActionRights(1, 'feedmuncher', 'categories');
		$this->setActionRights(1, 'feedmuncher', 'comments');
		$this->setActionRights(1, 'feedmuncher', 'delete_category');
		$this->setActionRights(1, 'feedmuncher', 'delete');
		$this->setActionRights(1, 'feedmuncher', 'edit_category');
		$this->setActionRights(1, 'feedmuncher', 'edit_comment');
		$this->setActionRights(1, 'feedmuncher', 'edit');
		$this->setActionRights(1, 'feedmuncher', 'index');
		$this->setActionRights(1, 'feedmuncher', 'mass_comment_action');
		$this->setActionRights(1, 'feedmuncher', 'settings');
		$this->setActionRights(1, 'feedmuncher', 'get_articles');

		// add extra's
		$blogID = $this->insertExtra('feedmuncher', 'block', 'Feedmuncher', null, null, 'N', 8000);
		$this->insertExtra('feedmuncher', 'widget', 'RecentComments', 'recent_comments', null, 'N', 8001);
		$this->insertExtra('feedmuncher', 'widget', 'Categories', 'categories', null, 'N', 8002);
		$this->insertExtra('feedmuncher', 'widget', 'Archive', 'archive', null, 'N', 8003);
		$this->insertExtra('feedmuncher', 'widget', 'RecentArticlesFull', 'recent_articles_full', null, 'N', 8004);
		$this->insertExtra('feedmuncher', 'widget', 'RecentArticlesList', 'recent_articles_list', null, 'N', 8005);


		// loop languages
		foreach($this->getLanguages() as $language)
		{
			// fetch current categoryId
			$currentCategoryId = $this->getCategory($language);

			// no category exists
			if($currentCategoryId == 0)
			{
				// add default category
				$defaultCategoryId = $this->addCategory($language, 'Default', 'default');

				// insert default category setting
				$this->setSetting('feedmuncher', 'default_category_'. $language, $defaultCategoryId, true);
			}

			// category exists
			else
			{
				// current default categoryId
				$currentDefaultCategoryId = $this->getSetting('feedmuncher', 'default_category_'. $language);

				// does not exist
				if(!$this->existsCategory($language, $currentDefaultCategoryId))
				{
					// insert default category setting
					$this->setSetting('feedmuncher', 'default_category_'. $language, $currentCategoryId, true);
				}
			}

			// feedburner URL
			$this->setSetting('feedmuncher', 'feedburner_url_'. $language, '');

			// RSS settings
			$this->setSetting('feedmuncher', 'rss_meta_'. $language, true);
			$this->setSetting('feedmuncher', 'rss_title_'. $language, 'RSS');
			$this->setSetting('feedmuncher', 'rss_description_'. $language, '');


			// check if a page for blog already exists in this language
			if(!(bool) $this->getDB()->getVar('SELECT COUNT(p.id)
												FROM pages AS p
												INNER JOIN pages_blocks AS b ON b.revision_id = p.revision_id
												WHERE b.extra_id = ? AND p.language = ?',
												array($blogID, $language)))
			{
				// insert page
				$this->insertPage(array('title' => 'Feedmuncher',
										'language' => $language),
									null,
									array('extra_id' => $blogID));
			}

		}


		// insert locale (nl)
		$this->insertLocale('nl', 'backend', 'feedmuncher', 'err', 'RSSDescription', 'feedmuncher RSS beschrijving is nog niet geconfigureerd. <a href="%1$s">Configureer</a>');
		$this->insertLocale('nl', 'backend', 'feedmuncher', 'lbl', 'Add', 'artikel toevoegen');
		$this->insertLocale('nl', 'backend', 'feedmuncher', 'lbl', 'Feed', 'feed');
		$this->insertLocale('nl', 'backend', 'feedmuncher', 'lbl', 'Feeds', 'feeds');
		$this->insertLocale('nl', 'backend', 'feedmuncher', 'lbl', 'NotPublished', 'niet gepubliceerd');
		$this->insertLocale('nl', 'backend', 'feedmuncher', 'lbl', 'PostInBlog', 'publiceer de artikelen in de blog module');
		$this->insertLocale('nl', 'backend', 'feedmuncher', 'lbl', 'PostInFeedmuncher', 'publiceer de artikelen in de feedmuncher module');
		$this->insertLocale('nl', 'backend', 'feedmuncher', 'msg', 'Added', 'Het artikel "%1$s" werd toegevoegd.');
		$this->insertLocale('nl', 'backend', 'feedmuncher', 'msg', 'CommentOnWithURL', 'Reactie op: <a href="%1$s">%2$s</a>');
		$this->insertLocale('nl', 'backend', 'feedmuncher', 'msg', 'ConfirmDelete', 'Ben je zeker dat je het artikel "%1$s" wil verwijderen?');
		$this->insertLocale('nl', 'backend', 'feedmuncher', 'msg', 'Deleted', 'De geselecteerde artikels werden verwijderd.');
		$this->insertLocale('nl', 'backend', 'feedmuncher', 'msg', 'DeletedSpam', 'Alle spamberichten werden verwijderd.');
		$this->insertLocale('nl', 'backend', 'feedmuncher', 'msg', 'DeleteAllSpam', 'Alle spam verwijderen:');
		$this->insertLocale('nl', 'backend', 'feedmuncher', 'msg', 'EditArticle', 'bewerk artikel "%1$s"');
		$this->insertLocale('nl', 'backend', 'feedmuncher', 'msg', 'EditCommentOn', 'bewerk reactie op "%1$s"');
		$this->insertLocale('nl', 'backend', 'feedmuncher', 'msg', 'Edited', 'Het artikel "%1$s" werd opgeslagen.');
		$this->insertLocale('nl', 'backend', 'feedmuncher', 'msg', 'EditedComment', 'De reactie werd opgeslagen.');
		$this->insertLocale('nl', 'backend', 'feedmuncher', 'msg', 'FollowAllCommentsInRSS', 'Volg alle reacties in een RSS feed: <a href="%1$s">%1$s</a>.');
		$this->insertLocale('nl', 'backend', 'feedmuncher', 'msg', 'HelpMeta', 'Toon de meta informatie van deze feedmuncherpost in de RSS feed (categorie, tags, ...)');
		$this->insertLocale('nl', 'backend', 'feedmuncher', 'msg', 'HelpPingServices', 'Laat verschillende feedmuncherservices weten wanneer je een nieuw bericht plaatst.');
		$this->insertLocale('nl', 'backend', 'feedmuncher', 'msg', 'HelpSummary', 'Maak voor lange artikels een inleiding of samenvatting. Die kan getoond worden op de homepage of het artikeloverzicht.');
		$this->insertLocale('nl', 'backend', 'feedmuncher', 'msg', 'HelpSpamFilter', 'Schakel de ingebouwde spam-filter (Akismet) in om spam-berichten in reacties te vermijden.');
		$this->insertLocale('nl', 'backend', 'feedmuncher', 'msg', 'MakeDefaultCategory', 'Maak van deze categorie de standaardcategorie (de huidige standaardcategorie is %1$s).');
		$this->insertLocale('nl', 'backend', 'feedmuncher', 'msg', 'NoItems', 'Er zijn nog geen artikels. <a href="%1$s">Voeg een feed toe.</a>.');
		$this->insertLocale('nl', 'backend', 'feedmuncher', 'msg', 'NotifyByEmailOnNewComment', 'Verwittig via email als er een nieuwe reactie is.');
		$this->insertLocale('nl', 'backend', 'feedmuncher', 'msg', 'NotifyByEmailOnNewCommentToModerate', 'Verwittig via email als er een nieuwe reactie te modereren is.');
		$this->insertLocale('nl', 'backend', 'feedmuncher', 'msg', 'NumItemsInRecentArticlesFull', 'Aantal items in recente artikels (volledig) widget');
		$this->insertLocale('nl', 'backend', 'feedmuncher', 'msg', 'NumItemsInRecentArticlesList', 'Aantal items in recente artikels (lijst) widget');
		$this->insertLocale('nl', 'backend', 'core', 'lbl', 'AutoPublish', 'publiceer artikelen automatisch');
		$this->insertLocale('nl', 'backend', 'core', 'lbl', 'Feedmuncher', 'feedmuncher');
		$this->insertLocale('nl', 'backend', 'core', 'lbl', 'FeedmuncherFeeds', 'feeds');

		// insert locale (en)
		$this->insertLocale('en', 'backend', 'feedmuncher', 'err', 'RSSDescription', 'Feedmuncher RSS description is not yet provided. <a href="%1$s">Configure</a>');
		$this->insertLocale('en', 'backend', 'feedmuncher', 'lbl', 'Add', 'add article');
		$this->insertLocale('en', 'backend', 'feedmuncher', 'lbl', 'Feed', 'feed');
		$this->insertLocale('en', 'backend', 'feedmuncher', 'lbl', 'Feeds', 'feeds');
		$this->insertLocale('en', 'backend', 'feedmuncher', 'lbl', 'NotPublished', 'not published');
		$this->insertLocale('en', 'backend', 'feedmuncher', 'lbl', 'PostInBlog', 'publish the articles in the blog module');
		$this->insertLocale('en', 'backend', 'feedmuncher', 'lbl', 'PostInFeedmuncher', 'publish the articles in the feedmuncher module');
		$this->insertLocale('en', 'backend', 'feedmuncher', 'msg', 'Added', 'The article "%1$s" was added.');
		$this->insertLocale('en', 'backend', 'feedmuncher', 'msg', 'CommentOnWithURL', 'Comment on: <a href="%1$s">%2$s</a>');
		$this->insertLocale('en', 'backend', 'feedmuncher', 'msg', 'ConfirmDelete', 'Are your sure you want to delete the article "%1$s"?');
		$this->insertLocale('en', 'backend', 'feedmuncher', 'msg', 'Deleted', 'The selected articles were deleted.');
		$this->insertLocale('en', 'backend', 'feedmuncher', 'msg', 'DeletedSpam', 'All spam-comments were deleted.');
		$this->insertLocale('en', 'backend', 'feedmuncher', 'msg', 'DeleteAllSpam', 'Delete all spam:');
		$this->insertLocale('en', 'backend', 'feedmuncher', 'msg', 'EditArticle', 'edit article "%1$s"');
		$this->insertLocale('en', 'backend', 'feedmuncher', 'msg', 'EditCommentOn', 'edit comment on "%1$s"');
		$this->insertLocale('en', 'backend', 'feedmuncher', 'msg', 'Edited', 'The article "%1$s" was saved.');
		$this->insertLocale('en', 'backend', 'feedmuncher', 'msg', 'EditedComment', 'The comment was saved.');
		$this->insertLocale('en', 'backend', 'feedmuncher', 'msg', 'FollowAllCommentsInRSS', 'Follow all comments in a RSS feed: <a href="%1$s">%1$s</a>.');
		$this->insertLocale('en', 'backend', 'feedmuncher', 'msg', 'HelpMeta', 'Show the meta information for this feedmuncherpost in the RSS feed (category, tags, ...)');
		$this->insertLocale('en', 'backend', 'feedmuncher', 'msg', 'HelpPingServices', 'Let various feedmuncherservices know when you\'ve posted a new article.');
		$this->insertLocale('en', 'backend', 'feedmuncher', 'msg', 'HelpSummary', 'Write an introduction or summary for long articles. It will be shown on the homepage or the article overview.');
		$this->insertLocale('en', 'backend', 'feedmuncher', 'msg', 'HelpSpamFilter', 'Enable the built-in spamfilter (Akismet) to help avoid spam comments.');
		$this->insertLocale('en', 'backend', 'feedmuncher', 'msg', 'NoItems', 'There are no articles yet. <a href="%1$s">Add a feed</a>.');
		$this->insertLocale('en', 'backend', 'feedmuncher', 'msg', 'NotifyByEmailOnNewComment', 'Notify by email when there is a new comment.');
		$this->insertLocale('en', 'backend', 'feedmuncher', 'msg', 'NotifyByEmailOnNewCommentToModerate', 'Notify by email when there is a new comment to moderate.');
		$this->insertLocale('en', 'backend', 'feedmuncher', 'msg', 'NumItemsInRecentArticlesFull', 'Number of articles in the recent articles (full) widget');
		$this->insertLocale('en', 'backend', 'feedmuncher', 'msg', 'NumItemsInRecentArticlesList', 'Number of articles in the recent articles (list) widget');
		$this->insertLocale('en', 'backend', 'feedmuncher', 'msg', 'MakeDefaultCategory', 'Make default category (current default category is: %1$s).');
		$this->insertLocale('en', 'backend', 'core', 'lbl', 'AutoPublish', 'publish articles automatic');
		$this->insertLocale('en', 'backend', 'core', 'lbl', 'Feedmuncher', 'feedmuncher');
		$this->insertLocale('en', 'backend', 'core', 'lbl', 'FeedmuncherFeeds', 'feeds');
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
