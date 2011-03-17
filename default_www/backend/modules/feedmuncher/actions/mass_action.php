<?php

/**
 * This action is used to delete ore publish one or more feedmuncher (not published) articles.
 *
 * @package		backend
 * @subpackage	feedmuncher
 *
 * @author		Lowie Benoot <lowiebenoot@netlash.com>
 * @since		2.1
 */
class BackendFeedmuncherMassAction extends BackendBaseAction
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

		// action to execute
		$action = SpoonFilter::getGetValue('action', array('publish', 'delete'), 'publish');

		// no id's provided
		if(!isset($_GET['id'])) $this->redirect(BackendModel::createURLForAction('articles'));

		// at least one id
		else
		{
			// redefine id's
			$aIds = (array) $_GET['id'];

			// delete item(s)
			if($action == 'delete')
			{
				// delete articles
				BackendFeedmuncherModel::deleteArticle($aIds);

				// redirect
				$this->redirect(BackendModel::createURLForAction('articles') . '&report=deletedArticles#tabNotPublished');
			}

			// publish items
			elseif($action == 'publish')
			{
				// publish articles
				BackendFeedmuncherModel::publishArticles($aIds);

				// redirect
				$this->redirect(BackendModel::createURLForAction('articles') . '&report=publishedArticles#tabNotPublished');
			}
		}
	}
}

?>