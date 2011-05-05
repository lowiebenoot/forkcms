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
	// @todo try to avoid these sequence thingies. An extra can have a sequence starting from 1 because you have to choose a module first when linking an extra to a page
	// @todo [bis] If you want to put the banners first and then the groups you can add 1000 to the sequence of the banners for instance. (Think this through and also look at the deletion/adding/editing of extra's - you have an id saved in the data array ;-) .)
	const PAGES_EXTRAS_SEQUENCE_BANNERS = 9011;
	const PAGES_EXTRAS_SEQUENCE_GROUPS = 9021;
	// @todo I would split the select columns into 2 lines to avoid vertical scrolling on smaller screens
	const QRY_DATAGRID_BROWSE_BANNERS = 'SELECT i.id, i.name, UNIX_TIMESTAMP(i.date_from) as date_from, UNIX_TIMESTAMP(i.date_till) as date_till, i.num_clicks, i.num_views, standard_id
										FROM banners AS i
										WHERE i.language = ?';
	// @todo mind your indenting, make sure the rules under the select statement are equally or further indented (also for the query above)
	const QRY_DATAGRID_BROWSE_BANNERS_BY_STANDARD = 'SELECT i.id, i.name, UNIX_TIMESTAMP(i.date_from) as date_from, UNIX_TIMESTAMP(i.date_till) as date_till, i.num_clicks, i.num_views, standard_id
										FROM banners AS i
										WHERE i.standard_id = ? AND i.language = ?';
	const QRY_DATAGRID_BROWSE_BANNERS_GROUPS = 'SELECT i.id, i.name, i.standard_id, bs.name AS size, bs.width, bs.height
												FROM banners_groups AS i
												INNER JOIN banners_standards AS bs ON bs.id = i.standard_id
												WHERE i.language = ?';

	/**
	 * Deletes one or more items
	 *
	 * @return	void
	 * @param 	mixed $ids		The ids to delete.
	 */
	public static function delete($ids)
	{
		// make sure $ids is an array
		$ids = (array) $ids;

		// create an array with the page sequences
		$pageSequences = array();

		// loop ids
		foreach($ids as &$id)
		{
			// cast to integers
			$id = (int) $id;

			// add to the pagesequences array
			$extraSequences[] = self::PAGES_EXTRAS_SEQUENCE_BANNERS . $id;
		}

		// create an array with an equal amount of questionmarks as ids provided
		$idPlaceHolders = array_fill(0, count($ids), '?');

		// get db
		$db = BackendModel::getDB(true);

		// delete records
		$db->delete('banners', 'id IN (' . implode(',', $ids) . ')', $ids);
		$db->delete('banners_groups_members', 'banner_id IN (' . implode(', ', $idPlaceHolders) . ')', $ids);
		$db->delete('pages_extras', 'module = ? AND type = ? AND sequence IN (' . implode(', ', $idPlaceHolders) . ')', array_merge(array('banners', 'widget'), $extraSequences));
	}


	/**
	 * Deletes one or more items
	 *
	 * @return	void
	 * @param 	mixed $ids		The ids to delete.
	 */
	public static function deleteGroup($ids)
	{
		// make sure $ids is an array
		$ids = (array) $ids;

		// loop ids
		foreach($ids as &$id)
		{
			// cast to integers
			$id = (int) $id;

			// add to the pagesequences array
			$extraSequences[] = self::PAGES_EXTRAS_SEQUENCE_GROUPS . $id;
		}

		// create an array with an equal amount of questionmarks as ids provided
		$idPlaceHolders = array_fill(0, count($ids), '?');

		// get db
		$db = BackendModel::getDB(true);

		// delete records
		$db->delete('banners_groups', 'id IN (' . implode(', ', $idPlaceHolders) . ')', $ids);
		$db->delete('banners_groups_members', 'group_id IN (' . implode(',', $idPlaceHolders) . ')', $ids);
		$db->delete('pages_extras', 'module = ? AND type = ? AND sequence IN (' . implode(', ', $idPlaceHolders) . ')', array_merge(array('banners', 'widget'), $extraSequences));
	}


	/**
	 * Checks if a banner exists
	 *
	 * @return	bool
	 * @param	int $id		The id of the banner to check for existence.
	 */
	public static function exists($id)
	{
		return (bool) BackendModel::getDB()->getVar('SELECT COUNT(id)
													FROM banners AS i
													WHERE i.id = ?',
													(int) $id);
	}


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
	 * Gets a banner by id
	 *
	 * @return	array
	 * @param	int $id		The id of the banner to get.
	 */
	public static function getBanner($id)
	{
		// return the group ID
		return (array) BackendModel::getDB()->getRecord('SELECT i.*, UNIX_TIMESTAMP(i.date_from) AS date_from, UNIX_TIMESTAMP(i.date_till) AS date_till
														FROM banners AS i
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
	 * Returns the default group IDs
	 *
	 * @return	array
	 * @param	int $id		The id of the standard.
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
	 * Gets the 'members' of a group
	 *
	 * @return	array
	 * @param	int $id		The id of the group.
	 */
	public static function getGroupMembers($id)
	{
		// return the group ID
		return (array) BackendModel::getDB()->getColumn('SELECT i.banner_id
														FROM banners_groups_members AS i
														WHERE i.group_id = ?',
														(int) $id);
	}


	/**
	 * Gets the groups where a banner is member of
	 *
	 * @return	array
	 * @param	int $id		The id of the banner.
	 */
	public static function getGroupsByBanner($id)
	{
		// return the groups
		return (array) BackendModel::getDB()->getRecords('SELECT g.*
														FROM banners_groups AS g
														INNER JOIN banners_groups_members as m ON m.group_id = g.id
														WHERE m.banner_id = ?',
														(int) $id);
	}


	/**
	 * Returns the banner standards (sizes)
	 *
	 * @return	array
	 * @param	int $id		the id of the standard.
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
	 * @param	bool[optional] $getEmpty		Should we also get the empty sizes? (no banners with that size).
	 */
	public static function getStandards($getEmpty = true)
	{
		// get empty?
		if($getEmpty)
		{
			// get standards
			$aStandards = (array) BackendModel::getDB()->getRecords('SELECT i.id, i.name, i.width, i.height
																	FROM banners_standards AS i');
		}

		// don't get the empty standards
		else
		{
			// get standards, but not the empty ones
			$aStandards = (array) BackendModel::getDB()->getRecords('SELECT i.id, i.name, i.width, i.height
																	FROM banners_standards AS i
																	INNER JOIN banners AS b ON i.id = b.standard_id');
		}

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
	 * @return	int
	 * @param	array $item		The data to insert.
	 */
	public static function insertBanner(array $item)
	{
		// get db
		$db = BackendModel::getDB(true);

		// insert in db
		$id = $db->insert('banners', $item);

		// build array for page extra
		$extra['module'] = 'banners';
		$extra['type'] = 'widget';
		$extra['label'] = 'BannerExtraLabel';
		$extra['action'] = 'index';
		$extra['data'] = serialize(array('label_variables' => array($item['name']), 'id' => $id, 'language' => BL::getWorkingLanguage(), 'source' => 'banner', 'edit_url' => BackendModel::createURLForAction('edit') . '&id=' . $id));
		$extra['hidden'] = 'N';
		$extra['sequence'] = self::PAGES_EXTRAS_SEQUENCE_BANNERS . $id;

		// insert extra
		$db->insert('pages_extras', $extra);

		// return banner id
		return $id;
	}


	/**
	 * Inserts a banner into the database
	 *
	 * @return	void
	 * @param	int $groupId			The id of the group.
	 * @param	array $bannerIDs		The IDs of the banners.
	 * @param	int $standardId			The id of the banner standard.
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
	 * Inserts a banner group into the database
	 *
	 * @return	int
	 * @param	array $item		The data to insert.
	 */
	public static function insertGroup(array $item)
	{
		// get db
		$db = BackendModel::getDB(true);

		// insert item in db
		$id = $db->insert('banners_groups', $item);

		// build array for page extra
		$extra['module'] = 'banners';
		$extra['type'] = 'widget';
		$extra['label'] = 'GroupExtraLabel';
		$extra['action'] = 'index';
		$extra['data'] = serialize(array('label_variables' => array($item['name']), 'id' => $id, 'language' => BL::getWorkingLanguage(), 'source' => 'group', 'edit_url' => BackendModel::createURLForAction('edit_group') . '&id=' . $id));
		$extra['hidden'] = 'N';
		$extra['sequence'] = self::PAGES_EXTRAS_SEQUENCE_GROUPS . $id;

		// insert extra
		$db->insert('pages_extras', $extra);

		// return the group id
		return $id;
	}


	/**
	 * Checks if a banners is the only member of a group
	 *
	 * @return	bool
	 * @param	int $id		The id of the banner.
	 */
	public static function isOnlyMemberOfAGroup($id)
	{
		// get the number of members from the groups, where this banner is member of
		$counts =  (array) BackendModel::getDB()->getColumn('SELECT COUNT(m.id) FROM banners_groups_members AS m
													INNER JOIN banners_groups_members AS m2 ON m.group_id = m2.group_id
													WHERE m2.banner_id = ?
													GROUP BY m.group_id',
													(int) $id);

		// if the minimum = 1, the banner is the only member of a group
		return empty($counts) ? false : min($counts) == 1;
	}


	/**
	 * sets (insert/delete) banners group members
	 *
	 * @return	void
	 * @param	int $id			The id of the group.
	 * @param	array $banners	The members.
	 */
	public static function setGroupMembers($id, array $banners)
	{
		// get db instance
		$db = BackendModel::getDB(true);

		// get the current members
		$members = self::getGroupMembers($id);

		// get the banners to delete (unchecked banners)
		$membersToDelete = array_diff($members, $banners);

		// get the members to insert
		$membersToInsert = array_diff($banners, $members);

		// delete the 'member rights' of the members to delete
		foreach($membersToDelete as $m) $db->delete('banners_groups_members', 'banner_id = ? AND group_id = ?', array($m, $id));

		// insert the 'member rights' of the members to insert
		foreach($membersToInsert as $m) $db->insert('banners_groups_members', array('banner_id' => $m, 'group_id' => $id));
	}


	/**
	 * Updates a banner
	 *
	 * @return	int
	 * @param	int $id			The id of the banner.
	 * @param	array $item		The item.
	 */
	public static function updateBanner($id, array $item)
	{

		// get db
		$db = BackendModel::getDB(true);

		// build extra data
		$extra['data'] = serialize(array('label_variables' => array($item['name']), 'id' => $id, 'language' => BL::getWorkingLanguage(), 'source' => 'banner', 'edit_url' => BackendModel::createURLForAction('edit') . '&id=' . $id));

		// update extra
		$db->update('pages_extras', $extra, 'module = ? AND type = ? AND sequence = ?', array('banners', 'widget', self::PAGES_EXTRAS_SEQUENCE_BANNERS . $id));

		// update banner and return
		return $db->update('banners', $item, 'id = ?', (int) $id);
	}


	/**
	 * Updates a banner group
	 *
	 * @return	int
	 * @param	int $id			The id of the group.
	 * @param	array $item		The item.
	 */
	public static function updateGroup($id, array $item)
	{
		// get db
		$db = BackendModel::getDB(true);

		// build extra data
		$extra['data'] = serialize(array('label_variables' => array($item['name']), 'id' => $id, 'language' => BL::getWorkingLanguage(), 'source' => 'group', 'edit_url' => BackendModel::createURLForAction('edit_group') . '&id=' . $id));

		// update extra
		$db->update('pages_extras', $extra, 'module = ? AND type = ? AND sequence = ?', array('banners', 'widget', self::PAGES_EXTRAS_SEQUENCE_GROUPS . $id));

		// insert in db and return
		return $db->update('banners_groups', $item, 'id = ?', (int) $id);
	}
}
