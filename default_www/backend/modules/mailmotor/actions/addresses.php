<?php

/**
 * BackendMailmotorAddresses
 * This page will display the overview of addresses
 *
 * @package		backend
 * @subpackage	mailmotor
 *
 * @author		Dave Lens <dave@netlash.com>
 * @since		2.0
 */
class BackendMailmotorAddresses extends BackendBaseActionIndex
{
	// maximum number of items
	const PAGING_LIMIT = 10;


	/**
	 * Filter variables
	 *
	 * @var	array
	 */
	private $filter;


	/**
	 * The passed group record
	 *
	 * @var	array
	 */
	private $group;


	/**
	 * Builds the query for this datagrid
	 *
	 * @return	array		An array with two arguments containing the query and its parameters.
	 */
	private function buildQuery()
	{
		// start query, as you can see this query is built in the wrong place, because of the filter it is a special case
		// where we allow the query to be in the actionfile itself
		$query = 'SELECT ma.email, ma.source, UNIX_TIMESTAMP(ma.created_on) AS created_on
					FROM mailmotor_addresses AS ma
					LEFT OUTER JOIN mailmotor_addresses_groups AS mag ON mag.email = ma.email
					WHERE 1';


		// init parameters
		$parameters = array();

		// add name
		if($this->filter['email'] !== null)
		{
			$query .= ' AND ma.email REGEXP ?';
			$parameters[] = $this->filter['email'];
		}

		// group was set
		if(!empty($this->group))
		{
			$query .= ' AND mag.group_id = ? AND mag.status = ?';
			$parameters[] = $this->group['id'];
			$parameters[] = 'subscribed';
		}

		// group
		$query .= ' GROUP BY email';

		// return
		return array($query, $parameters);
	}


	/**
	 * Sets the headers so we may download the CSV file in question
	 *
	 * @return	array
	 * @param	string $path	The full path to the CSV file you wish to download.
	 */
	private function downloadCSV($path)
	{
		// check if the file exists
		if(!SpoonFile::exists($path)) throw new SpoonFileException('The file ' . $path . ' doesn\'t exist.');

		// fetch the filename from the path string
		$explodedFilename = explode('/', $path);
		$filename = end($explodedFilename);

		// set headers for download
		$headers = array();
		$headers[] = 'Content-type: application/csv; charset=utf-8';
		$headers[] = 'Content-Disposition: attachment; filename="' . $filename . '"';
		$headers[] = 'Pragma: no-cache';

		// overwrite the headers
		SpoonHTTP::setHeaders($headers);

		// get the file contents
		$content = readfile($path);

		// output the file contents
		echo $content;

		// exit here
		exit;
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

		// set the group
		$this->setGroup();

		// set the filter
		$this->setFilter();

		// load datagrid
		$this->loadDataGrid();

		// load the filter
		$this->loadForm();

		// parse page
		$this->parse();

		// display the page
		$this->display();
	}


	/**
	 * Loads the datagrid with the e-mail addresses
	 *
	 * @return	void
	 */
	private function loadDatagrid()
	{
		// fetch query and parameters
		list($query, $parameters) = $this->buildQuery();

		// create datagrid
		$this->datagrid = new BackendDataGridDB($query, $parameters);

		// overrule default URL
		$this->datagrid->setURL(BackendModel::createURLForAction(null, null, null, array('offset' => '[offset]', 'order' => '[order]', 'sort' => '[sort]', 'email' => $this->filter['email']), false));

		// add the group to the URL if one is set
		if(!empty($this->group)) $this->datagrid->setURL('&group_id=' . $this->group['id'], true);

		// set headers values
		$headers['created_on'] = ucfirst(BL::lbl('Created'));

		// set headers
		$this->datagrid->setHeaderLabels($headers);

		// sorting columns
		$this->datagrid->setSortingColumns(array('email', 'source', 'created_on'), 'email');

		// add the multicheckbox column
		$this->datagrid->addColumn('checkbox', '<span class="checkboxHolder block"><input type="checkbox" name="toggleChecks" value="toggleChecks" />', '<input type="checkbox" name="emails[]" value="[email]" class="inputCheckbox" /></span>');
		$this->datagrid->setColumnsSequence('checkbox');

		// add mass action dropdown
		$ddmMassAction = new SpoonFormDropdown('action', array('export' => BL::lbl('Export'), 'delete' => BL::lbl('Delete')), 'delete');
		$this->datagrid->setMassAction($ddmMassAction);

		// set column functions
		$this->datagrid->setColumnFunction(array('BackendDatagridFunctions', 'getTimeAgo'), array('[created_on]'), 'created_on', true);

		// add edit column
		$editURL = BackendModel::createURLForAction('edit_address') . '&amp;email=[email]';
		if(!empty($this->group)) $editURL .= '&amp;group_id=' . $this->group['id'];
		$this->datagrid->addColumn('edit', null, BL::lbl('Edit'), $editURL, BL::lbl('Edit'));

		// set paging limit
		$this->datagrid->setPagingLimit(self::PAGING_LIMIT);
	}


	/**
	 * Load the form
	 *
	 * @return	void
	 */
	private function loadForm()
	{
		// create form
		$this->frm = new BackendForm('filter', null, 'get');

		// add fields
		$this->frm->addText('email', $this->filter['email']);
		$this->frm->addHidden('group_id', $this->group['id']);

		// manually parse fields
		$this->frm->parse($this->tpl);

		// check if the filter form was set
		if($this->frm->isSubmitted()) $this->tpl->assign('oPost', true);
	}


	/**
	 * Parse all datagrids
	 *
	 * @return	void
	 */
	private function parse()
	{
		// CSV parameter (this is set when an import partially fails)
		$csv = $this->getParameter('csv');
		$download = $this->getParameter('download', 'bool', false);

		// a failed import just happened
		if(!empty($csv))
		{
			// assign the CSV URL to the template
			$this->tpl->assign('csvURL', BackendModel::createURLForAction('addresses', 'mailmotor') . '&csv=' . $csv . '&download=1');

			// we should download the file
			if($download)
			{
				$this->downloadCSV(BACKEND_CACHE_PATH . '/mailmotor/' . $csv);
			}
		}

		// parse the datagrid
		$this->tpl->assign('datagrid', ($this->datagrid->getNumResults() != 0) ? $this->datagrid->getContent() : false);

		// parse paging & sorting
		$this->tpl->assign('offset', (int) $this->datagrid->getOffset());
		$this->tpl->assign('order', (string) $this->datagrid->getOrder());
		$this->tpl->assign('sort', (string) $this->datagrid->getSort());

		// parse filter
		$this->tpl->assign($this->filter);
	}


	/**
	 * Sets the filter based on the $_GET array.
	 *
	 * @return	void
	 */
	private function setFilter()
	{
		// set filter values
		$this->filter['email'] = $this->getParameter('email');
	}


	/**
	 * Sets the group record
	 *
	 * @return	void
	 */
	private function setGroup()
	{
		// set the passed group ID
		$id = SpoonFilter::getGetValue('group_id', null, 0, 'int');

		// group was set
		if(!empty($id))
		{
			// get group record
			$this->group = BackendMailmotorModel::getGroup($id);

			// assign the group record
			$this->tpl->assign('group', $this->group);
		}
	}
}

?>