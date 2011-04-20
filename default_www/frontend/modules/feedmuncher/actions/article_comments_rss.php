<?php

/**
 * This is the RSS-feed for comments on a certain article.
 *
 * @package		frontend
 * @subpackage	feedmuncher
 *
 * @author		Lowie Benoot <lowiebenoot@netlash.com>
 * @since		2.1
 */
class FrontendFeedmuncherArticleCommentsRSS extends FrontendBaseBlock
{
	/**
	 * The record
	 *
	 * @var array
	 */
	private $record;


	/**
	 * The comments
	 *
	 * @var	array
	 */
	private $items;


	/**
	 * Execute the extra
	 *
	 * @return	void
	 */
	public function execute()
	{
		// call the parent
		parent::execute();

		// load the data
		$this->getData();

		// parse
		$this->parse();
	}


	/**
	 * Load the data, don't forget to validate the incoming data
	 *
	 * @return	void
	 */
	private function getData()
	{
		// validate incoming parameters
		if($this->URL->getParameter(1) === null) $this->redirect(FrontendNavigation::getURL(404));

		// get record
		$this->record = FrontendFeedmuncherModel::get($this->URL->getParameter(1));

		// anything found?
		if(empty($this->record)) $this->redirect(FrontendNavigation::getURL(404));

		// get articles
		$this->items = FrontendFeedmuncherModel::getComments($this->record['id']);
	}


	/**
	 * Parse the data into the template
	 *
	 * @return	void
	 */
	private function parse()
	{
		// get vars
		$title = vsprintf(FL::msg('CommentsOn'), array($this->record['title']));
		$link = SITE_URL . FrontendNavigation::getURLForBlock('feedmuncher', 'article_comments_rss') . '/' . $this->record['url'];
		$detailLink = SITE_URL . FrontendNavigation::getURLForBlock('feedmuncher', 'detail');
		$description = null;

		// create new rss instance
		$rss = new FrontendRSS($title, $link, $description);

		// loop articles
		foreach($this->items as $item)
		{
			// init vars
			$title = $item['author'] . ' ' . FL::lbl('On') . ' ' . $this->record['title'];
			$link = $detailLink . '/' . $this->record['url'] . '/#comment-' . $item['id'];
			$description = $item['text'];

			// create new instance
			$rssItem = new FrontendRSSItem($title, $link, $description);

			// set item properties
			$rssItem->setPublicationDate($item['created_on']);
			$rssItem->setAuthor($item['author']);

			// add item
			$rss->addItem($rssItem);
		}

		// output
		$rss->parse();
	}
}

?>