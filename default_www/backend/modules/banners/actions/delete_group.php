<?php

/**
 * This action will delete a banner group
 *
 * @package		backend
 * @subpackage	banners
 *
 * @author		Lowie Benoot <lowie@netlash.com>
 * @since		2.1
 */
class BackendBannersDeleteGroup extends BackendBaseActionDelete
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
		if($this->id !== null && BackendBannersModel::existsGroup($this->id))
		{
			// call parent, this will probably add some general CSS/JS or other required files
			parent::execute();

			// get data
			$this->record = (array) BackendBannersModel::getGroup($this->id);

			// delete item
			BackendBannersModel::deleteGroup($this->id);

			// item was deleted, so redirect
			$this->redirect(BackendModel::createURLForAction('groups') . '&report=deletedGroup&var=' . urlencode($this->record['name']));
		}

		// something went wrong
		else $this->redirect(BackendModel::createURLForAction('groups') . '&error=non-existing');
	}
}

?>