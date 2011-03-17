<?php

/**
 * This action will delete a post
 *
 * @package		backend
 * @subpackage	feedmuncher
 *
 * @author		Dave Lens <dave@netlash.com>
 * @author		Davy Hellemans <davy@netlash.com>
 * @author		Lowie Benoot <lowiebenoot@netlash.com>
 * @since		2.1
 */
class BackendFeedmuncherDeleteArticle extends BackendBaseActionDelete
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

		// does the item exist
		if($this->id !== null && BackendFeedmuncherModel::existsArticle($this->id))
		{
			// call parent, this will probably add some general CSS/JS or other required files
			parent::execute();

			// get data
			$this->record = (array) BackendFeedmuncherModel::getArticle($this->id);

			// delete item
			BackendFeedmuncherModel::deleteArticle($this->id);

			// delete search indexes
			if(method_exists('BackendSearchModel', 'removeIndex')) BackendSearchModel::removeIndex('feedmuncher', $this->id);

			// item was deleted, so redirect
			$this->redirect(BackendModel::createURLForAction('articles') . '&report=deleted&var=' . urlencode($this->record['title']));
		}

		// something went wrong
		else $this->redirect(BackendModel::createURLForAction('articles') . '&error=non-existing');
	}
}

?>