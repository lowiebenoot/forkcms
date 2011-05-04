<?php

/**
 * This action will delete a banner
 *
 * @package		backend
 * @subpackage	banners
 *
 * @author		Lowie Benoot <lowie@netlash.com>
 * @since		2.1
 */
class BackendBannersDelete extends BackendBaseActionDelete
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
		if($this->id !== null && BackendBannersModel::exists($this->id))
		{
			// call parent, this will probably add some general CSS/JS or other required files
			parent::execute();

			// get data
			$this->record = (array) BackendBannersModel::getBanner($this->id);

			// the banner is not the only member of a group?
			if(!BackendBannersModel::isOnlyMemberOfAGroup($this->id))
			{
				// delete item
				BackendBannersModel::delete($this->id);
			}

			// item was deleted, so redirect
			$this->redirect(BackendModel::createURLForAction('index') . '&report=deletedBanner&var=' . urlencode($this->record['name']));
		}

		// something went wrong
		else $this->redirect(BackendModel::createURLForAction('index') . '&error=non-existing');
	}
}

?>