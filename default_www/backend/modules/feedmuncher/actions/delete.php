<?php

/**
 * This action will delete a feed
 *
 * @package		backend
 * @subpackage	feedmuncher
 *
 * @author		Lowie Benoot <lowiebenoot@netlash.com>
 * @since		2.1
 */
class BackendFeedmuncherDelete extends BackendBaseActionDelete
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
		if($this->id !== null && BackendFeedmuncherModel::exists($this->id))
		{
			// call parent, this will probably add some general CSS/JS or other required files
			parent::execute();

			// get data
			$this->record = (array) BackendFeedmuncherModel::get($this->id);

			// delete item
			BackendFeedmuncherModel::delete($this->id);

			// item was deleted, so redirect
			$this->redirect(BackendModel::createURLForAction('index') . '&report=deletedFeed&var=' . urlencode($this->record['name']));
		}

		// something went wrong
		else $this->redirect(BackendModel::createURLForAction('index') . '&error=non-existing');
	}
}

?>