<?php

/**
 * In this file we store all generic functions that we will be using in the banners module
 *
 * @package		frontend
 * @subpackage	banners
 *
 * @author		Lowie Benoot <lowiebenoot@netlash.com>
 * @since		2.1
 */
class FrontendBannersModel
{
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
	 * Get an item
	 *
	 * @return	array
	 * @param	int $id		The id of the group.
	 */
	public static function getRandomBannerForGroup($id)
	{
		// get db
		$db = FrontendModel::getDB(true);

		// get a random banner from the group
		$banner = $db->getRecord('SELECT b.id, b.file, b.url, s.width, s.height
											FROM banners_groups_members AS m
											INNER JOIN banners AS b ON b.id = m.banner_id
											INNER JOIN banners_groups AS g ON g.id = m.group_id
											INNER JOIN banners_standards AS s on s.id = g.standard_id
											WHERE m.group_id = ?
											AND b.date_till >= NOW()
											ORDER BY RAND() LIMIT 1',
											(int) $id);
		// add a view for the banner
		if($banner != null) $db->execute('UPDATE banners AS b
											SET b.num_views = b.num_views+1
											WHERE b.id = ?',
											(int) $banner['id']);

		// return the banner
		return $banner;
	}


	/**
	 * Increase the number of clicks from a banner
	 *
	 * @return	void
	 * @param	int $id		The id of the banner.
	 */
	public static function increaseNumClicks($id)
	{
		// increase num clicks
		FrontendModel::getDB(true)->execute('UPDATE banners AS b
											SET b.num_clicks = b.num_clicks+1
											WHERE b.id = ?',
											(int) $id);
	}
}

?>