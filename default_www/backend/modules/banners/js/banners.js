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
		// change the tracker url 
		$('span #generatedUrl').html(encodeURIComponent($('#url').val()));
		
		// change tracker url on keyup
		$('#url').keyup(function()
		{
			$('span #generatedUrl').html(encodeURIComponent($('#url').val()));
		});

		if(jsBackend.current.action == 'add_group')
		{
			// initial hide/show from rows
			jsBackend.banners.controls.filterBySize();
			
			// input select on change function
			$('#size').change(function() 
			{
				// filter the datagrid by the selected size
				jsBackend.banners.controls.filterBySize();
				
				// uncheck the checked checkboxes
				jsBackend.banners.controls.uncheckChecked();
			});
		}
		
		if($('#showPermanently').length > 0)
		{
			// initial enable/disable 
			jsBackend.banners.controls.enableOrDisableDates();
			
			// input select on change function
			$('#showPermanently').change(function() 
			{
				// filter the datagrid by the selected size
				jsBackend.banners.controls.enableOrDisableDates();
			});
		}
	},
	
	
	// filter the datagrid by the selected size
	enableOrDisableDates: function()
	{
		if(utils.form.isChecked($('#showPermanently')))$('#startDate, #endDate, #startTime, #endTime').attr('disabled', 'disabled').addClass('disabled');
		else $('#startDate, #endDate, #startTime, #endTime').attr('disabled', '').removeClass('disabled');
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
	
	
	// uncheck checked checkboxes
	uncheckChecked: function()
	{
		$('table.datagrid input[type=checkbox]:checked').each(function()
		{
			// uncheck checkbox
			$(this).removeAttr('checked');
			
			// remove the 'selected' class from the parent row
			$(this).parent().parent().parent().removeClass('selected');
		});
	},

	
	// end
	eoo: true
}


$(document).ready(function() { jsBackend.banners.init(); });