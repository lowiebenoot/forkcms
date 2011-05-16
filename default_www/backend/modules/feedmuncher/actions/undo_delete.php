<?php

/**
 * This is the undo-delete-action, it will restore a deleted feed
 *
 * @package		backend
 * @subpackage	feedmuncher
 *
 * @author		Lowie Benoot <lowiebenoot@netlash.com>
 * @since		2.1
 */
class BackendFeedmuncherUndoDelete extends BackendBaseAction
{
	/**
	 * Execute the action
	 *
	 * @return	void
	 */
	public function execute()
	{
		// get parameters
		$url = $this->getParameter('url', 'string');

		// an url is given?
		if($url !== null)
		{
			// call parent, this will probably add some general CSS/JS or other required files
			parent::execute();

			// undelete feed
			$id = BackendFeedmuncherModel::undoDelete($url);

			// undelete succeeded?
			if($id)
			{
				// get user
				$feed = BackendFeedmuncherModel::get($id);

				// item was deleted, so redirect
				$this->redirect(BackendModel::createURLForAction('edit') . '&id=' . $id . '&report=restored&var=' . $feed['name'] . '&highlight=row-' . $id);
			}

			// invalid user
			else $this->redirect(BackendModel::createURLForAction('index') . '&error=non-existing');
		}

		// no user found, throw an exceptions, because somebody is fucking with our URL
		else $this->redirect(BackendModel::createURLForAction('index') . '&error=non-existing');
	}
}

?>