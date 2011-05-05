<?php

/**
 * This test-email-action will test the mail-connection
 *
 * @package		backend
 * @subpackage	settings
 *
 * @author		Lowie Benoot <lowie@netlash.com>
 * @since		2.1
 */
class BackendSettingsAjaxSaveServiceMessage extends BackendBaseAJAXAction
{
	/**
	 * Execute the action
	 *
	 * @return	void
	 */
	public function execute()
	{
		// call parent, this will probably add some general CSS/JS or other required files
		parent::execute();

		// get the values
		$message = SpoonFilter::getPostValue('value', null, null, null);
		$moduleId = SpoonFilter::getPostValue('id', BackendSettingsModel::getShareableModulesIds(), null, 'int');

		// validate module
		if($moduleId == 0) $this->output(self::ERROR, null, BL::getError('Error'));

		// save message
		BackendSettingsModel::updateShareMessageForModule($moduleId, $message);

		// output OK
		$this->output(self::OK);
	}
}

?>