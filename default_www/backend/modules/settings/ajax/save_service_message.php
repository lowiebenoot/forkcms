<?php

/**
 * This test-email-action will test the mail-connection
 *
 * @package		backend
 * @subpackage	settings
 *
 * @author		Tijs Verkoyen <tijs@sumocoders.be>
 * @since		2.0
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

		// validate @TODO: correct errormessage
		if($moduleId == 0) $this->output(self::ERROR, null, BL::getError('InvalidModule'));

		// save message
		BackendSettingsModel::updateShareMessageForModule($moduleId, $message);

		// output OK
		$this->output(self::OK);
	}
}

?>