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
													WHERE i.id = ?',
													(int) $id); // @todo indentation
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
		$db = FrontendModel::getDB(true); // @todo why true? you're nog writing to the database?

		// get a random banner from the group
		// @todo cast to an array otherwise the return could also be null and your return type doesn't match
		$banner = $db->getRecord('SELECT b.id, b.file, b.url, s.width, s.height
											FROM banners AS b
											INNER JOIN banners_standards AS s ON s.id = b.standard_id
											WHERE b.id = ?
											AND (b.date_till >= NOW() OR b.date_till IS NULL)', // @todo and what about the start date?
											(int) $id); // @todo indentation

		// add a view for the banner
		if($banner != null) self::increaseNumViews($id); // @todo cleaner: !empty($banner) cause you're sure it's an array (if you do the cast)

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
		$db = FrontendModel::getDB(true); // @todo why true? you're nog writing to the database?

		// get a random banner from the group
		// @todo cast to an array otherwise the return could also be null and your return type doesn't match
		$banner = $db->getRecord('SELECT b.id, b.file, b.url, s.width, s.height
											FROM banners_groups_members AS m
											INNER JOIN banners AS b ON b.id = m.banner_id
											INNER JOIN banners_groups AS g ON g.id = m.group_id
											INNER JOIN banners_standards AS s on s.id = g.standard_id
											WHERE m.group_id = ?
											AND (b.date_till >= NOW() OR b.date_till IS NULL) // @todo and what about the start date?
											ORDER BY RAND() LIMIT 1',
											(int) $id); // @todo indentation

		// add a view for the banner
		if($banner != null) self::increaseNumViews((int) $banner['id']); // @todo cleaner: !empty($banner) cause you're sure it's an array (if you do the cast)

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
											(int) $id); // @todo indentation
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
											(int) $id); // @todo indentation
	}
}

?>