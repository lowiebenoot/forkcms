<?php

/**
 * This is the import-action, it will import records from a CSV file
 *
 * @package		backend
 * @subpackage	mailmotor
 *
 * @author		Dave Lens <dave@netlash.com>
 * @since		2.0
 */
class BackendMailmotorImportAddresses extends BackendBaseActionEdit
{
	/**
	 * The passed group ID
	 *
	 * @var	int
	 */
	private $groupId;


	/**
	 * Generates and downloads the example CSV file
	 *
	 * @return	void
	 */
	private function downloadExampleFile()
	{
		// Should we download the example file or not?
		$downloadExample = SpoonFilter::getGetValue('example', array(0, 1), 0, 'bool');

		// stop here if no download parameter was given
		if(!$downloadExample) return false;

		// build the csv
		$csv = array();
		$csv[] = array('email' => BackendModel::getModuleSetting('mailmotor', 'from_email'), 'name' => BackendModel::getModuleSetting('mailmotor', 'from_name'));

		// download the file
		SpoonFileCSV::arrayToFile(BACKEND_CACHE_PATH . '/mailmotor/example.csv', $csv, null, null, ',', '"', true);
	}


	/**
	 * Execute the action
	 *
	 * @return	void
	 */
	public function execute()
	{
		// call parent, this will probably add some general CSS/JS or other required files
		parent::execute();

		// store the passed group ID
		$this->groupId = SpoonFilter::getGetValue('group_id', null, 0, 'int');

		// download the example file
		$this->downloadExampleFile();

		// load the form
		$this->loadForm();

		// validate the form
		$this->validateForm();

		// parse the datagrid
		$this->parse();

		// display the page
		$this->display();
	}


	/**
	 * Reformats a subscriber record with custom fields to the necessary format used in the import.
	 *
	 * @return	array
	 * @param	array $subscriber	The subscriber record as it comes out of the CSV.
	 */
	private function formatSubscriberCSVRow($subscriber)
	{
		// build record to insert
		$item['EmailAddress'] = $subscriber['email'];
		$item['Name'] = isset($subscriber['name']) ? $subscriber['name'] : null;
		$item['CustomFields'] = array();

		// unset the email (for the custom fields)
		unset($subscriber['email']);

		// check if there's something left in our record
		if(!empty($subscriber))
		{
			// loop the fields in the records
			foreach($subscriber as $name => $value)
			{
				// add this to the custom fields stack
				$item['CustomFields'][] = array('Key' => $name, 'Value' => $value);
			}
		}

		// return the record
		return $item;
	}


	/**
	 * Load the form
	 *
	 * @return	void
	 */
	private function loadForm()
	{
		// create form
		$this->frm = new BackendForm('import');

		// create elements
		$this->frm->addFile('csv');

		// dropdown for languages
		$this->frm->addDropdown('languages', BL::getWorkingLanguages(), BL::getWorkingLanguage());

		// fetch groups
		$groups = BackendMailmotorModel::getGroupsForCheckboxes();

		// if no groups are found, redirect to overview
		if(empty($groups)) $this->redirect(BackendModel::createURLForAction('addresses') . '&error=no-groups');

		// add radiobuttons for groups
		$this->frm->addRadiobutton('groups', $groups, (empty($this->groupId) ? null : $this->groupId));

		// show the form
		$this->tpl->assign('import', true);
	}


	/**
	 * Processes the subscriber import. Returns an array with failed subscribers.
	 *
	 * @return	array			A list with failed subscribers.
	 * @param	array $csv		The uploaded CSV file.
	 * @param	int $groupID	The group ID for which we're importing.
	 */
	private function processImport($csv, $groupID)
	{
		// the CM list ID for the given group ID
		$listID = BackendMailmotorCMHelper::getCampaignMonitorID('list', $groupID);

		// reserve variables
		$subscribers = array();

		/*
			IMPORTANT NOTE: CM only allows a maximum amount of 100 subscribers for each import. So we have to batch
		*/
		foreach($csv as $key => $record)
		{
			// no e-mail address set means stop here
			if(empty($record['email'])) continue;

			// build record to insert
			$subscribers[$key] = $this->formatSubscriberCSVRow($record);
		}

		// divide the subscribers into batches of 100
		$batches = array_chunk($subscribers, 100);
		$failed = array();
		$feedback = array();

		// loop the batches
		foreach($batches as $key => $batch)
		{
			// import every 100 subscribers
			$feedback[$key] = BackendMailmotorCMHelper::getCM()->importSubscribers($batch, $listID);

			// if the batch did not contain failed imports, we continue looping
			if(empty($feedback[$key])) continue;

			// merge the feedback results with the full failed set
			$failed = array_merge($failed, $feedback[$key]);
		}

		// now we have to loop all uploaded CSV rows in order to provide a .csv with all failed records.
		foreach($csv as $row)
		{
			// the subscriber didn't fail the import, so we proceed to insert him in our database
			if(!in_array($row['email'], $failed))
			{
				// build subscriber record
				$subscriber = array();
				$subscriber['email'] = $row['email'];
				$subscriber['source'] = 'import';
				$subscriber['created_on'] = BackendModel::getUTCDate();
				$subscriber['groups'] = $groupID;

				// unset the email (for the custom fields)
				unset($row['email']);

				// save the address in our database, with the assigned custom fields
				BackendMailmotorModel::saveAddress($subscriber, $groupID, $row);

				// continue looping
				continue;
			}

			// subscriber failed in import, so add his record to the fail-csv
			$failedSubscribersCSV[] = $row;
		}

		// return the failed subscribers
		return $failedSubscribersCSV;
	}


	/**
	 * Validate the form
	 *
	 * @return	void
	 */
	private function validateForm()
	{
		// is the form submitted?
		if($this->frm->isSubmitted())
		{
			// cleanup the submitted fields, ignore fields that were added by hackers
			$this->frm->cleanupFields();

			// shorten fields
			$fileCSV = $this->frm->getField('csv');
			$chkGroups = $this->frm->getField('groups');

			// validate fields
			$fileCSV->isFilled(BL::err('CSVIsRequired'));

			// convert the CSV file to an array
			$csv = $fileCSV->isFilled() ? SpoonFileCSV::fileToArray($fileCSV->getTempFileName()) : null;

			// check if the csv is valid
			if($csv === false || empty($csv) || !isset($csv[0])) $fileCSV->addError(BL::err('InvalidCSV'));

			// there was a csv file found
			if(!empty($csv))
			{
				// fetch the columns of the first row
				$columns = array_keys($csv[0]);

				// loop the columns
				foreach($csv as $row)
				{
					// fetch the row columns
					$rowColumns = array_keys($row);

					// check if the arrays match
					if($rowColumns != $columns)
					{
						// add an error to the CSV files
						$fileCSV->addError(BL::err('InvalidCSV'));

						// exit loop
						break;
					}
				}
			}

			// get values
			$values = $this->frm->getValues();

			// check if at least one recipient group is chosen
			if(empty($values['groups'])) $chkGroups->addError(BL::err('ChooseAtLeastOneGroup'));

			// no errors?
			if($this->frm->isCorrect())
			{
				// convert the CSV file to an array, and fetch the group's CM ID
				$csv = SpoonFileCSV::fileToArray($fileCSV->getTempFileName());

				// process our import, and get the failed subscribers
				$failedSubscribers = $this->processImport($csv, $values['groups']);

				// show a detailed report
				$this->tpl->assign('import', false);

				// write a CSV file to the cache
				$csvFile = 'import-report-' . SpoonFilter::urlise(BackendModel::getUTCDate()) . '.csv';
				SpoonFileCSV::arrayToFile(BACKEND_CACHE_PATH . '/mailmotor/' . $csvFile, $failedSubscribers);

				// no failed subscribers found
				if(empty($failedSubscribers))
				{
					// redirect to success message
					$this->redirect(BackendModel::createURLForAction('addresses') . '&report=imported-addresses&var[]=' . count($csv) . '&var[]=' . count($values['groups']));
				}

				else
				{
					// redirect to failed message with an additional parameter to display a download link to the report-csv form cache.
					$this->redirect(BackendModel::createURLForAction('addresses') . '&error=imported-addresses&var[]=' . count($csv) . '&var[]=' . count($values['groups']) . '&var[]=' . count($failedSubscribers) . '&csv=' . $csvFile);
				}
			}
		}
	}
}

?>
