<?php

/**
 * This class represents a share-object
 *
 * @package		backend
 * @subpackage	core
 *
 * @author		Lowie Benoot <lowie@netlash.com>
 * @since		2.1
 */
class BackendShare
{
	/**
	 * The data, when existing share settings are loaded
	 *
	 * @var	array
	 */
	private $data;


	/**
	 * The form instance
	 *
	 * @var	BackendForm
	 */
	private $frm;


	/**
	 * The module
	 *
	 * @var string
	 */
	private $module;


	/**
	 * The other_id, the id of an external item (example: blogarticle id)
	 *
	 * @var	int
	 */
	private $otherId;


	/**
	 * The type, it is used to separate the ids (example: blog article ids - blog category ids)
	 *
	 * @var	string
	 */
	private $type;


	/**
	 * Default constructor
	 *
	 * @return	void
	 * @param	BackendForm $form			An instance of Backendform, the elements will be parsed in here.
	 * @param	string $module				The module.
	 * @param	string $type				The type, it is used to separate the ids (example: blog article ids - blog category ids).
	 * @param	int[optional] $shareId		The shareId to load.
	 */
	public function __construct(BackendForm $form, $module, $type, $otherId = null)
	{
		// set form instance
		$this->frm = $form;

		// set module
		$this->module = $module;

		// set type
		$this->type = $type;

		// load existing share settings
		if($otherId != null)
		{
			// redefine
			$this->otherId = (int) $otherId;
			$this->module = (string) $module;

			// get data
			$this->data = (array) BackendModel::getDB()->getRecords('SELECT *
																	FROM share_settings AS s
																	WHERE s.other_id = ? AND s.module = ? AND s.item_type = ?',
																	array($this->otherId, $this->module, $this->type));
		}

		// load the form
		$this->loadForm();
	}


	/**
	 * Add all element into the form
	 *
	 * @return	void
	 */
	private function loadForm()
	{
		// get db
		$db = BackendModel::getDB();

		// get the services
		$services = (array) $db->getRecords('SELECT i.id AS value, i.name AS label FROM share_services AS i');

		// get the services that should be checked (from settings)
		if($this->otherId != null && $this->module != null) $checked = (array) $db->getColumn('SELECT i.service_id
																								FROM share_settings AS i
																								WHERE i.module = ? AND i.other_id = ?',
																								array($this->module, $this->otherId));

		// get the settings from the general settings page
		else $checked = BackendModel::getModuleSetting('share', 'services');

		// add multi checkbox
		$this->frm->addMultiCheckbox('services', $services, $checked);

		// get the message if data is not empty, if empty, get it from the general settings
		if(!empty($this->data))  $message = $this->data[0]['message'];

		// get default message for this module
		else $message = (string) $db->getVar('SELECT i.message
												FROM share_modules as i
												WHERE i.module = ?',
												$this->module);

		// add textfield for the message
		$this->frm->addText('shareMessage', $message);
	}


	/**
	 * Saves the share object
	 *
	 * @return	int
	 * @param	int $otherId				The id of the item to share.
	 * @param	bool[optional] $update		Should we update the record or insert a new one.
	 */
	public function save($otherId, $update = false)
	{
		// redefine
		$otherId = (int) $otherId;
		$update = (bool) $update;

		// build share item
		$services = $this->frm->getField('services')->getValue();
		$message = $this->frm->getField('shareMessage')->getValue();

		// get db
		$db = BackendModel::getDB(true);

		// get current settings
		if($update)
		{
			$currentSettingsIdsAndServices = (array) $db->getPairs('SELECT i.id, i.service_id
																	FROM share_settings AS i
																	WHERE i.other_id = ? AND i.module = ?',
																	array($otherId, $this->module));

			// define an array for the current services
			$currentServices = array();

			// loop currentSettings and add services to the array
			foreach($currentSettingsIdsAndServices as $service) $currentServices[] = $service;

			// get the services where from the settings should be deleted
			$servicesToDelete = array_diff($currentServices, $services);

			// delete the settings
			if(!empty($servicesToDelete)) $db->delete('share_settings', 'service_id IN (' . implode(',', $servicesToDelete) . ') AND other_id = ? AND module= ?', array($otherId, $this->module));
		}

		// loop services
		foreach($services as $service)
		{
			// build and insert a share setting for each service
			$item['module'] = $this->module;
			$item['item_type'] = $this->type;
			$item['other_id'] = $otherId;
			$item['service_id'] = (int) $service;
			$item['message'] = $message;
			if(!$update) $item['num_clicks'] = 0;
			$item['active'] = 'Y';

			// update share settings
			if($update)
			{
				// search for existing share_setting
				$id = (int) array_search($service, $currentSettingsIdsAndServices);

				// existing item found?
				if($id != 0)
				{
					// update the existing record
					$db->update('share_settings', $item, 'id = ?', $id);
				}

				// no item found
				else
				{
					// add num_clicks
					$item['num_clicks'] = 0;

					// insert
					$db->insert('share_settings', $item);
				}
			}

			// insert share settings
			else
			{
				// insert
				$db->insert('share_settings', $item);
			}
		}
	}


	/**
	 * Validates the form
	 *
	 * @return	void
	 */
	public function validate()
	{
		// get services
		$services = $this->frm->getField('services')->getValue();

		// if services are checked, the message field has to be filled in
		if(!empty($services)) $this->frm->getField('shareMessage')->isFilled(BL::err('ShareMessageIsRequired'));
	}
}

?>