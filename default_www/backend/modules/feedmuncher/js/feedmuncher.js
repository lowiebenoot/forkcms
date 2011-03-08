if(!jsBackend) { var jsBackend = new Object(); }


/**
 * Interaction for the feedmuncher module
 *
 * @author	Tijs Verkoyen <tijs@sumocoders.be>
 */
jsBackend.feedmuncher =
{
	// init, something like a constructor
	init: function()
	{
		jsBackend.feedmuncher.controls.init();
		jsBackend.feedmuncher.category.init();
		jsBackend.feedmuncher.add.init();

		// do meta
		if($('#title').length > 0) $('#title').doMeta();
	},


	// end
	eoo: true
}


jsBackend.feedmuncher.add =
{
	// init, something like a constructor
	init: function()
	{
		if($('input[name=target]').length != 0)
		{
			// hide or show the dropdownmenus
			jsBackend.feedmuncher.add.changeDropdownMenu();
			
			$('input[name=target]').click(function()
			{
				jsBackend.feedmuncher.add.changeDropdownMenu();
			});
		}
	},
	
	changeDropdownMenu: function()
	{
		// posting in feedmuncher?
		if($('input[name=target]:checked').val() == 'feedmuncher')
		{
			// show the feedmuncher categories
			$('#category').show();
			
			// hide the blog categories
			$('#categoryBlog').hide();
		}
		
		// posting in blog
		else
		{
			// show the blog categories
			$('#categoryBlog').show();
			
			// hide the feedmuncher categories
			$('#category').hide();
		}
	},


	// end
	eoo: true
}


jsBackend.feedmuncher.category =
{
	// init, something like a constructor
	init: function()
	{
		if($('.datagrid td.name').length > 0 && jsBackend.current.action == 'categories')
		{
			// buil ajax-url
			var url = '/backend/ajax.php?module='+ jsBackend.current.module +'&action=edit_category&language='+ jsBackend.current.language;

			// bind
			$('.datagrid td.name').inlineTextEdit({ saveUrl: url, tooltip: '{$msgClickToEdit}' });
		}
	},


	// end
	eoo: true
}


jsBackend.feedmuncher.controls =
{
	// init, something like a constructor
	init: function()
	{
		$('#saveAsDraft').click(function(evt)
		{
			$('form').append('<input type="hidden" name="status" value="draft" />');
			$('form').submit();
		});
		
		$('.publishButton').click(function(evt)
		{
			// get the article id
			var articleId = $(this).attr('id').substr(8);
			
			// get the table row from the datagrid (from the pushed button)
			var tableRow = $(this).parent().parent();
			tableRow.hide('slow', function() { tableRow.remove(); });

			// make ajax call
			$.ajax(
			{
				cache: false, type: 'POST', dataType: 'json', 
				url: '/backend/ajax.php?module=' + jsBackend.current.module + '&action=publish_article&language=' + jsBackend.current.language,
				data: 'articleId=' + articleId,
				success: function(data, textStatus)
				{ 
					// not a succes so revert the changes
					if(data.code == 200)
					{ 
						// show message
						jsBackend.messages.add('success', data.message);
						
						// hide row and remove
						tableRow.hide('slow', function() { tableRow.remove(); });
					}
					
					else
					{
						jsBackend.messages.add('error', data.message);
					}
				}
			});
			
			return false;
		});
	},


	// end
	eoo: true
}


$(document).ready(function() { jsBackend.feedmuncher.init(); });