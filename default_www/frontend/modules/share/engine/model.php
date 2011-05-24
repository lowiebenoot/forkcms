<?php

/**
 * In this file we store all generic functions that we will be using in the share module
 *
 * @package		frontend
 * @subpackage	share
 *
 * @author		Lowie Benoot <lowie@netlash.com>
 * @since		2.1
 */
class FrontendShareModel
{
	/**
	 * Increase the amount of clicks of a share setting.
	 *
	 * @return	void
	 * @param	int $id		The id of the share setting.
	 */
	public static function increaseNumClicks($id)
	{
		// increase num clicks
        FrontendModel::getDB(true)->execute('UPDATE share_settings AS i
                                                SET i.num_clicks = i.num_clicks+1
                                                WHERE i.id = ?',
                                                (int) $id);
	}


	/**
	 * Share setting exists?
	 *
	 * @return	bool
	 * @param	int $id		The id of the share setting.
	 */
	public static function settingExists($id)
	{
		return (bool) FrontendModel::getDB()->getVar('SELECT i.id
														FROM share_settings AS i
														WHERE i.id = ?',
														(int) $id);
	}
}

?>