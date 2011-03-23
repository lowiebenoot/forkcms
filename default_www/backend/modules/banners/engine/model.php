<?php

/**
 * In this file we store all generic functions that we will be using in the banners module
 *
 * @package		backend
 * @subpackage	banners
 *
 * @author		Lowie Benoot <lowiebenoot@netlash.com>
 * @since		2.0
 */
class BackendBannersModel
{
	const QRY_DATAGRID_BROWSE_BANNERS = 'SELECT i.id, i.name, UNIX_TIMESTAMP(i.date_from) as date_from, UNIX_TIMESTAMP(i.date_till) as date_till, i.num_clicks, i.num_views, standard_id
										FROM banners AS i';
	const QRY_DATAGRID_BROWSE_BANNERS_BY_STANDARD = 'SELECT i.id, i.name, UNIX_TIMESTAMP(i.date_from) as date_from, UNIX_TIMESTAMP(i.date_till) as date_till, i.num_clicks, i.num_views, standard_id
										FROM banners AS i
										WHERE i.standard_id = ?';
	const QRY_DATAGRID_BROWSE_BANNERS_GROUPS = 'SELECT i.id, i.name, i.standard_id, bs.name AS standard_name, bs.width, bs.height
												FROM banners_groups AS i
												INNER JOIN banners_standards AS bs ON bs.id = i.standard_id';

	/**
	 * Checks if a group exists
	 *
	 * @return	bool
	 * @param	int $id		The id of the group to check for existence.
	 */
	public static function existsGroup($id)
	{
		return (bool) BackendModel::getDB()->getVar('SELECT COUNT(id)
													FROM banners_groups AS i
													WHERE i.id = ?',
													(int) $id);
	}


	/**
	 * Returns the default group IDs
	 *
	 * @return	array
	 */
	public static function getBanners()
	{
		// return the group ID
		return (array) BackendModel::getDB()->getColumn('SELECT i.id
														FROM banners AS i');
	}


	/**
	 * Gets a group by id
	 *
	 * @return	array
	 * @param	int $id		The id of the group to get.
	 */
	public static function getGroup($id)
	{
		// return the group ID
		return (array) BackendModel::getDB()->getRecord('SELECT i.*
														FROM banners_groups AS i
														WHERE i.id = ?',
														(int) $id);
	}


	/**
	 * Returns the default group IDs
	 *
	 * @return	array
	 */
	public static function getBannersIDsByStandard($id)
	{
		// return the group ID
		return (array) BackendModel::getDB()->getColumn('SELECT i.id
														FROM banners AS i
														WHERE i.standard_id = ?',
														(int) $id);
	}


	/**
	 * Returns the banner standards (sizes)
	 *
	 * @return	array
	 * @param	int $id		the id of the standard
	 */
	public static function getStandard($id)
	{
		// return the group ID
		return (array) BackendModel::getDB()->getRecord('SELECT i.*
														FROM banners_standards AS i
														WHERE i.id = ?',
														(int) $id);
	}


	/**
	 * Returns the banner standards (sizes)
	 *
	 * @return	array
	 */
	public static function getStandards()
	{
		// return the group ID
		$aStandards = (array) BackendModel::getDB()->getRecords('SELECT i.id, i.name, i.width, i.height
																FROM banners_standards AS i');

		// make new array
		$standards = array();

		// loop standards
		foreach($aStandards as $aStandard)
		{
			// create pairs for dropdown, id as value, name - widthXheight as label
			$standards[$aStandard['id']] = $aStandard['name'] . ' - ' . $aStandard['width'] . 'x' . $aStandard['height'];
		}

		return $standards;
	}


	/**
	 * Inserts a banner into the database
	 *
	 * @return	void
	 * @param	int $groupId			The id of the group.
	 * @param	array $bannerIDs		The IDs of the banners
	 * @param	int $standardId			The id of the banner standard
	 */
	public static function insertBannersInGroup($groupId, $bannerIDs, $standardId)
	{
		// redefine params
		$groupId = (int) $groupId;
		$standardId = (int) $standardId;
		$bannersIDs = (array) $bannerIDs;

		// get db instance
		$db = BackendModel::getDB(true);

		// get the allowed banners for this standard
		$allowedBanners = BackendBannersModel::getBannersIDsByStandard($standardId);

		// loop banners
		foreach($bannersIDs as $bannerID)
		{
			// make banner member of group, if allowed
			if(in_array($bannerID, $allowedBanners)) $db->insert('banners_groups_members', array('group_id' => $groupId, 'banner_id' => (int) $bannerID));
		}
	}


	/**
	 * Inserts a banner into the database
	 *
	 * @return	int
	 * @param	array $item		The data to insert.
	 */
	public static function insertBanner(array $item)
	{
		// insert in db and return
		return BackendModel::getDB(true)->insert('banners', $item);
	}


	/**
	 * Inserts a banner group into the database
	 *
	 * @return	int
	 * @param	array $item		The data to insert.
	 */
	public static function insertGroup(array $item)
	{
		// insert in db and return
		return BackendModel::getDB(true)->insert('banners_groups', $item);
	}
}
