if(!jsBackend) { var jsBackend = new Object(); }


/**
 * Interaction for the banners module
 *
 * @author	Lowie Benoot <lowiebenoot@netlash.com>
 */
jsBackend.banners =
{
	// init, something like a constructor
	init: function()
	{
		jsBackend.banners.controls.init();

		// do meta
		if($('#title').length > 0) $('#title').doMeta();
	},


	// end
	eoo: true
}

jsBackend.banners.controls =
{
	// init, something like a constructor
	init: function()
	{
		if(jsBackend.current.action == 'add_group' || jsBackend.current.action == 'edit_group')
		{
			// initial hide/show from rows
			jsBackend.banners.controls.filterBySize();
			
			// input select on change function
			$('#size').change(function() 
			{
				// filter the datagrid by the selected size
				jsBackend.banners.controls.filterBySize();
			});
		}
	},
	
	
	// filter the datagrid by the selected size
	filterBySize: function()
	{
		// get the value of the select input (size)
		var sizeId = $('#size').val();
		
		// only show the rows that belong to the selected standard. Hide the others (accept the columnheaders row).
		$('table.datagrid tr[data-standard="' + sizeId + '"]').show();
		$('table.datagrid tr').not('[data-standard="' + sizeId + '"]').not('tr:first').hide();
		
		// redo odd-even
		jsBackend.banners.controls.redoOddEven();
	},
	
	
	// redo odd-even
	redoOddEven: function()
	{
		// get the table
		var table = $('table.datagrid');
		
		// remove the odd and even class
		table.find('tr:visible').removeClass('odd').removeClass('even');
		
		// add even or odd
		table.find('tr:visible:even').addClass('even');
		table.find('tr:visible:odd').addClass('odd');
	},

	
	// end
	eoo: true
}


$(document).ready(function() { jsBackend.banners.init(); });