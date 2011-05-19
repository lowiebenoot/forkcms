<?php

/**
 * This edit-action will check the status using Ajax
 *
 * @package		backend
 * @subpackage	feedmuncher
 *
 * @author		Lowie Benoot <lowie@netlash.com>
 * @since		2.0
 */
class BackendFeedmuncherAjaxCheckStatus extends BackendBaseAJAXAction
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

		// get parameters
		$identifier = trim(SpoonFilter::getPostValue('identifier', null, ''));

		// validate
		if($identifier == '') $this->output(self::BAD_REQUEST, null, 'No identifier provided.');

		// init vars
		$filename = BACKEND_CACHE_PATH . '/feedmuncher/' . $identifier . '.txt';

		// does the temporary file still exits?
		$status = SpoonFile::getContent($filename);

		// no file - create one
		if($status === false)
		{
			// create file with initial counter
			SpoonFile::setContent($filename, 'missing1');

			// return status
			$this->output(self::OK, array('status' => false), 'Temporary file was missing. We created one.');
		}

		// busy status
		if(strpos($status, 'busy') !== false)
		{
			// get counter
			$counter = (int) substr($status, 4) + 1;

			// file's been busy for more than hundred cycles - just stop here
			if($counter > 100)
			{
				// remove file
				SpoonFile::delete($filename);

				// return status
				$this->output(self::ERROR, array('status' => 'timeout'), 'Error while retrieving data - the script took too long to retrieve data.');
			}

			// change file content to increase counter
			SpoonFile::setContent($filename, 'busy' . $counter);

			// return status
			$this->output(self::OK, array('status' => 'busy'), 'Data is being retrieved. (' . $counter . ')');
		}

		// done status
		if($status == 'done')
		{
			// remove file
			SpoonFile::delete($filename);

			// return status
			$this->output(self::OK, array('status' => 'done'), 'Data retrieved.');
		}

		// missing status
		if(strpos($status, 'missing') !== false)
		{
			// get counter
			$counter = (int) substr($status, 7) + 1;

			// file's been missing for more than ten cycles - just stop here
			if($counter > 10)
			{
				// remove file
				SpoonFile::delete($filename);

				// return status
				$this->output(self::ERROR, array('status' => 'missing'), 'Error while retrieving data - file was never created.');
			}

			// change file content to increase counter
			SpoonFile::setContent($filename, 'missing' . $counter);

			// return status
			$this->output(self::OK, array('status' => 'busy'), 'Temporary file was still in status missing. (' . $counter . ')');
		}

		/* FALLBACK - SOMETHING WENT WRONG */
		// remove file
		SpoonFile::delete($filename);

		// return status
		$this->output(self::ERROR, array('status' => 'error'), 'Error while retrieving data.');
	}
}

?>