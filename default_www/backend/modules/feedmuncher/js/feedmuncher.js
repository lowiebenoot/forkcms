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

		// do meta
		if($('#title').length > 0) $('#title').doMeta();
	},


	// end
	eoo: true
}


jsBackend.feedmuncher.controls =
{
	currentCategory: null,		
	
	// init, something like a constructor
	init: function()
	{
		$('#saveAsDraft').click(function(evt)
		{
			$('form').append('<input type="hidden" name="status" value="draft" />');
			$('form').submit();
		});
		
		$('#filter #feedmuncherCategory, #filter #blogCategory').change(function(evt)
		{
			// add the tab to the form action
			var action = $('#filter').attr('action') + ($(evt.currentTarget).attr('id') == 'feedmuncherCategory' ? '#tabFeedmuncher' : '#tabBlog');
			$('#filter').attr('action', action);
			
			// submit the form
			$('#filter').submit();
		});
		
		if($('#addCategoryDialog').length > 0) {
			$('#addCategoryDialog').dialog(
				{
					autoOpen: false,
					draggable: false,
					resizable: false,
					modal: true,
					buttons:
					{
						'{$lblOK|ucfirst}': function()
						{
							// hide errors
							$('#categoryTitleError').hide();
							
							// get the target (feedmuncher or blog)
							if(jsBackend.current.action == 'edit_article') var target =  $('#target').val();
							else var target = $('input[name=target]:checked').val();
							
							$.ajax(
							{
								url: '/backend/ajax.php?module='+ jsBackend.current.module +'&action=add_category&language={$LANGUAGE}',
								data: 'value=' + $('#categoryTitle').val() + '&target=' + target,
								success: function(json, textStatus)
								{
									if(json.code != 200)
									{
										// show error if needed
										if(jsBackend.debug) alert(textStatus);

										// show message
										$('#categoryTitleError').show();
									}
									else
									{
										// add and set selected
										if(jsBackend.current.action == 'edit_article') $('#categoryId').append('<option value="'+ json.data.id +'">'+ json.data.title +'</option>');
										else
										{
											if($('input[name=target]:checked').val() == 'feedmuncher') $('#category').append('<option value="'+ json.data.id +'">'+ json.data.title +'</option>');
											else $('#categoryBlog').append('<option value="'+ json.data.id +'">'+ json.data.title +'</option>');
										}
										
										// reset value
										jsBackend.feedmuncher.controls.currentCategory = json.data.id;
										
										// close dialog
										$('#addCategoryDialog').dialog('close');
									}
								}
							});
						},
						
						'{$lblCancel|ucfirst}': function()
						{
							// close the dialog
							$(this).dialog('close');
						}
					},
					close: function(event, ui) 
					{
						// reset value to previous selected item
						if(jsBackend.current.action == 'edit_article') $('#categoryId').val(jsBackend.feedmuncher.controls.currentCategory);
						else
						{
							if($('input[name=target]:checked').val() == 'feedmuncher') $('#category').val(jsBackend.feedmuncher.controls.currentCategory);
							else $('#categoryBlog').val(jsBackend.feedmuncher.controls.currentCategory);
						}
					}
				});

			// bind change
			$('#categoryId, #category, #categoryBlog').change(function(evt)
			{
				// new category?
				if($(this).val() == 'new_category')
				{
					// prevent default
					evt.preventDefault();
					
					// open dialog
					$('#addCategoryDialog').dialog('open');
				}
				
				// reset current category
				else 
				{
					jsBackend.feedmuncher.controls.currentCategory = $('#categoryId').val();
					
					if(jsBackend.current.action == 'edit_article') jsBackend.feedmuncher.controls.currentCategory = $('#categoryId').val();
					else
					{
						if($('input[name=target]:checked').val() == 'feedmuncher') jsBackend.feedmuncher.controls.currentCategory = $('#category').val();
						else jsBackend.feedmuncher.controls.currentCategory = $('#categoryBlog').val();
					}
				}
			});
		}
		
		jsBackend.feedmuncher.controls.currentCategory = $('#categoryId').val();
		
		if($('input[name=target]').length != 0)
		{
			// hide or show the dropdownmenus and checkbox
			jsBackend.feedmuncher.controls.changeControls();
			
			$('input[name=target]').click(function()
			{
				jsBackend.feedmuncher.controls.changeControls();
			});
		}
	},
	
	
	changeControls: function()
	{
		// posting in feedmuncher?
		if($('input[name=target]:checked').val() == 'feedmuncher')
		{
			// show the feedmuncher categories
			$('#category').show();
			
			// hide the blog categories
			$('#categoryBlog').hide();
			
			// show the 'link to original' checkbox
			$('#linkToOriginal').parent().show();
		}
		
		// posting in blog
		else
		{
			// show the blog categories
			$('#categoryBlog').show();
			
			// hide the feedmuncher categories
			$('#category').hide();
			
			// hide the 'link to original' checkbox
			$('#linkToOriginal').parent().hide();
		}
	},


	// end
	eoo: true
}


$(document).ready(function() { jsBackend.feedmuncher.init(); });