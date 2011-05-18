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
		return (bool) FrontendModel::getDB()->getVar('SELECT COUNT(id)
														FROM banners AS i
														WHERE i.id = ?
														AND (i.date_till >= NOW() OR i.date_till IS NULL)
														AND (i.date_from <= NOW() OR i.date_from IS NULL)',
														(int) $id);
	}


	/**
	 * Get an item
	 *
	 * @return	array
	 * @param	int $id		The id of the group.
	 */
	public static function getBanner($id)
	{
		// get db
		$db = FrontendModel::getDB();

		// get a random banner from the group
		$banner = (array) $db->getRecord('SELECT b.id, b.file, b.url, s.width, s.height
											FROM banners AS b
											INNER JOIN banners_standards AS s ON s.id = b.standard_id
											WHERE b.id = ?
											AND (b.date_till >= NOW() OR b.date_till IS NULL)
											AND (b.date_from <= NOW() OR b.date_from IS NULL)',
											(int) $id);

		// add a view for the banner
		if(!empty($banner)) self::increaseNumViews($id);

		// return the banner
		return $banner;
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
		$db = FrontendModel::getDB();

		// get a random banner from the group
		$banner = (array) $db->getRecord('SELECT b.id, b.file, b.url, s.width, s.height
											FROM banners_groups_members AS m
											INNER JOIN banners AS b ON b.id = m.banner_id
											INNER JOIN banners_groups AS g ON g.id = m.group_id
											INNER JOIN banners_standards AS s on s.id = g.standard_id
											WHERE m.group_id = ?
											AND (b.date_till >= NOW() OR b.date_till IS NULL)
											AND (b.date_from <= NOW() OR b.date_from IS NULL)
											ORDER BY RAND() LIMIT 1',
											(int) $id);

		// add a view for the banner
		if(!empty($banner)) self::increaseNumViews((int) $banner['id']);

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


	/**
	 * Increase the number of views from a banner
	 *
	 * @return	void
	 * @param	int $id		The id of the banner.
	 */
	public static function increaseNumViews($id)
	{
		// increase num clicks
		FrontendModel::getDB(true)->execute('UPDATE banners AS b
												SET b.num_views = b.num_views+1
												WHERE b.id = ?',
												(int) $id);
	}
}

?>