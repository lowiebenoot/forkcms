<?php

/**
 * This is the configuration-object for the banners module
 *
 * @package		backend
 * @subpackage	banners
 *
 * @author		Lowie Benoot <lowiebenoot@netlash.com>
 * @since		2.0
 */
final class BackendBannersConfig extends BackendBaseConfig
{
	/**
	 * The default action
	 *
	 * @var	string
	 */
	protected $defaultAction = 'index';


	/**
	 * The disabled actions
	 *
	 * @var	array
	 */
	protected $disabledActions = array();
}

?>