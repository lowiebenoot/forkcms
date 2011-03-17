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
	const QRY_DATAGRID_BROWSE_BANNERS = 'SELECT i.id, i.name, i.date_from, i.date_till, i.file, i.standard_id, bs.name AS standard_name, bs.width, bs.height, i.num_clicks, i.num_views
										FROM banners AS i
										INNER JOIN banners_standards AS bs ON bs.id = i.standard_id';
	const QRY_DATAGRID_BROWSE_BANNERS_GROUPS = 'SELECT i.id, i.name, i.standard_id, bs.name AS standard_name, bs.width, bs.height
												FROM banners_groups AS i
												INNER JOIN banners_standards AS bs ON bs.id = i.standard_id';

	/**
	 * Returns the default group IDs
	 *
	 * @return	array
	 */
	public static function getBanners()
	{
		// return the group ID
		return (array) BackendModel::getDB()->getColumn('SELECT i.id
															FROM banners AS i
															WHERE i.id != ?',
															array(2));
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
}
