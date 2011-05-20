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
		$id = $this->getParameter('id', 'int');

		// an id is given?
		if($id != 0)
		{
			// call parent, this will probably add some general CSS/JS or other required files
			parent::execute();

			// undo delete
			if(BackendFeedmuncherModel::undoDelete($id))
			{
				// get user
				$feed = BackendFeedmuncherModel::get($id);

				// item was restored, so redirect to the edit
				$this->redirect(BackendModel::createURLForAction('edit') . '&id=' . $id . '&report=restored&var=' . $feed['name'] . '&highlight=row-' . $id);
			}
		}

		// no feed found, redirect because somebody is fucking with our URL
		$this->redirect(BackendModel::createURLForAction('index') . '&error=non-existing');
	}
}

?>