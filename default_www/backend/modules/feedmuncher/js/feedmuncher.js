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
		jsBackend.feedmuncher.loading.init();

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
		// save as draft
		$('#saveAsDraft').click(function(evt)
		{
			$('form').append('<input type="hidden" name="status" value="draft" />');
			$('form').submit();
		});
		
		// articles filter by category
		$('#filter #feedmuncherCategory, #filter #blogCategory').change(function(evt)
		{
			// add the tab to the form action
			var action = $('#filter').attr('action') + ($(evt.currentTarget).attr('id') == 'feedmuncherCategory' ? '#tabFeedmuncher' : '#tabBlog');
			$('#filter').attr('action', action);
			
			// submit the form
			$('#filter').submit();
		});
		
		// add category via ajax
		if($('#addCategoryDialog').length > 0) {
			var target;
			
			// get the target (feedmuncher or blog)
			if($('input[name=target]').length > 0)
			{
				if(jsBackend.current.action == 'edit_article') jsBackend.feedmuncher.target =  $('#target').val();
				else jsBackend.feedmuncher.target = $('input[name=target]:checked').val();
			}
			
			else jsBackend.feedmuncher.target = 'feedmuncher';
			
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
						
						$.ajax(
						{
							url: '/backend/ajax.php?module='+ jsBackend.current.module +'&action=add_category&language={$LANGUAGE}',
							data: 'value=' + $('#categoryTitle').val() + '&target=' + jsBackend.feedmuncher.target,
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
										if(jsBackend.feedmuncher.target == 'feedmuncher') $('#category').append('<option value="'+ json.data.id +'">'+ json.data.title +'</option>');
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
						if(jsBackend.feedmuncher.target == 'feedmuncher') $('#category').val(jsBackend.feedmuncher.controls.currentCategory);
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
						if(jsBackend.feedmuncher.target == 'feedmuncher') jsBackend.feedmuncher.controls.currentCategory = $('#category').val();
						else jsBackend.feedmuncher.controls.currentCategory = $('#categoryBlog').val();
					}
				}
			});
		}
		
		jsBackend.feedmuncher.controls.currentCategory = $('#categoryId').val();
		
		if($('input[name=target]').length != 0)
		{
			// initial hide or show the dropdownmenus and checkbox
			jsBackend.feedmuncher.controls.changeTargetControls();
			
			// add on click action
			$('input[name=target]').click(function()
			{
				// hide or show the dropdownmenus and checkbox
				jsBackend.feedmuncher.controls.changeTargetControls();
			});
		}
		
		if($('#type').length != 0)
		{
			// initial hide or show the dropdownmenus and checkbox
			jsBackend.feedmuncher.controls.changeTypeControls();
			
			// add on change action
			$('#type').change(function()
			{
				// hide or show the dropdownmenus and checkbox
				jsBackend.feedmuncher.controls.changeTypeControls();
			});
		}
		
		if($('#reoccurrence').length != 0)
		{
			// initial hide or show the day dropdownmenu and it's labels
			jsBackend.feedmuncher.controls.changeReoccurrenceControls();
			
			// add on change action
			$('#reoccurrence').change(function()
			{
				jsBackend.feedmuncher.controls.changeReoccurrenceControls();
			});
		}
		
		if($('#aggregateFeed').length != 0)
		{
			// initial hide or show the day dropdownmenu and it's labels
			jsBackend.feedmuncher.controls.changeAggregateFeedControls();
			
			// add on change action
			$('#aggregateFeed').click(function()
			{
				jsBackend.feedmuncher.controls.changeAggregateFeedControls();
			});
		}
	},
	
	
	changeTargetControls: function()
	{
		jsBackend.feedmuncher.target = $('input[name=target]:checked').val();
		
		// posting in feedmuncher?
		if(jsBackend.feedmuncher.target == 'feedmuncher')
		{
			// show the feedmuncher categories
			$('#category').show();
			
			// hide the blog categories
			$('#categoryBlog').hide();
			
			// show the 'link to original' checkbox
			if($('#type').val() == 'feed') $('#linkToOriginal').parent().show();
		}
		
		// posting in blog
		else
		{
			// show the blog categories
			$('#categoryBlog').show();
			
			// hide the feedmuncher categories
			$('#category').hide();
			
			// hide the 'link to original' checkbox
			if($('#type').val() == 'feed') $('#linkToOriginal').parent().hide();
		}
	},
	
	
	changeTypeControls: function()
	{
		// feed type is feed
		if($('#type').val() == 'feed')
		{
			// hide or show some elements
			$('#website, #url').parent().show();
			if($('input[name=target]:checked').val() == 'feedmuncher') $('#linkToOriginal').parent().show();
			$('#aggregateFeed').parent().show();
			if(!utils.form.isChecked($('#aggregateFeed'))) $('#reoccurrenceWrapper').hide();
			$('#username').parent().hide();
		}
		
		// feed type is delicious or twitter
		else
		{
			// hide or show some elements
			$('#website, #url, #linkToOriginal').parent().hide();
			$('#username').parent().show();
			$('#aggregateFeed').parent().hide();
			$('#reoccurrenceWrapper').show();
		}
	},
	
	
	changeReoccurrenceControls: function()
	{
		// reoccurrence is daily
		if($('#reoccurrence').val() == 'daily')
		{
			// hide day dropdown and its label
			$('#day').hide();
			$('label[for=day]').hide();
		}
		
		// reoccurrence is weekly
		else
		{
			// show day dropdown and its label
			$('#day').show();
			$('label[for=day]').show();
		}
	},
	
	changeAggregateFeedControls: function()
	{
		if(utils.form.isChecked($('#aggregateFeed')))
		{
			$('#reoccurrenceWrapper').show();
			$('#linkToOriginal').parent().hide();
		}
		
		else 
		{
			$('#reoccurrenceWrapper').hide();
			$('#linkToOriginal').parent().show();
		}
	},


	// end
	eoo: true
}

jsBackend.feedmuncher.loading =
{
	page: 'index',
	identifier: '',
	interval: '',

	init: function()
	{
		if($('#longLoader').length > 0)
		{
			// loading bar stuff
			$('#longLoader').show();

			// get the page to get data for
			var identifier = $('#identifier').html();

			// save data
			jsBackend.feedmuncher.loading.identifier = identifier;

			// check status every 5 seconds
			jsBackend.feedmuncher.loading.interval = setInterval("jsBackend.feedmuncher.loading.checkStatus()", 3000);
		}
	},

	checkStatus: function()
	{
		// get data
		var identifier = jsBackend.feedmuncher.loading.identifier;

		// make the call to check the status
		$.ajax(
		{
			cache: false,
			type: 'POST',
			timeout: 3000,
			dataType: 'json',
			url: '/backend/ajax.php?module=' + jsBackend.current.module + '&action=check_status&language=' + jsBackend.current.language,
			data: 'identifier=' + identifier,
			success: function(data, textStatus)
			{
				if(data.code == 200)
				{
					// get redirect url
					var url = document.location.protocol +'//'+ document.location.host;
					url += $('#redirect').html();

					// redirect
					if(data.data.status == 'done') window.location = url;
				}
				else
				{
					// clear interval
					clearInterval(jsBackend.feedmuncher.loading.interval);

					// loading bar stuff
					$('#longLoader').show();

					// show box
					$('#statusError').show();
					$('#loading').hide();

					// show message
					jsBackend.messages.add('error', textStatus);

					// alert the user
					if(jsBackend.debug) alert(textStatus);
				}

				// alert the user
				if(data.code != 200 && jsBackend.debug) { alert(data.message); }
			},
			error: function(XMLHttpRequest, textStatus, errorThrown)
			{
				// clear interval
				clearInterval(jsBackend.feedmuncher.loading.interval);

				// show box and hide loading bar
				$('#statusError').show();
				$('#loading').hide();
				$('#longLoader').hide();

				// show message
				jsBackend.messages.add('error', textStatus);

				// alert the user
				if(jsBackend.debug) alert(textStatus);
			}
		});
	},


	// end
	eoo: true
}


$(document).ready(function() { jsBackend.feedmuncher.init(); });