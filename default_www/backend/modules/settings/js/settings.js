if(!jsBackend) { var jsBackend = new Object(); }


/**
 * Interaction for the settings index-action
 *
 * @author	Tijs Verkoyen <tijs@sumocoders.be>
 */
jsBackend.settings =
{
	/**
	 * Kind of constructor
	 */
	init: function()
	{
		$('#facebookAdminIds').multipleTextbox(
		{ 
			emptyMessage: '{$msgNoAdminIds}', 
			addLabel: '{$lblAdd|ucfirst}', 
			removeLabel: '{$lblDelete|ucfirst}',
			canAddNew: true
		}); 
		
		if($('#testEmailConnection').length > 0) $('#testEmailConnection').bind('click', jsBackend.settings.testEmailConnection);
		
		// service message inline edit
		jsBackend.settings.serviceMessageInlineEdit();
		
		// url shortener on/off
		if($('input[name=shorten]').length != 0)
		{
			// hide or show the dropdownmenus
			jsBackend.settings.add.changeDropdownMenu();
			
			$('input[name=target]').click(function()
			{
				jsBackend.feedmuncher.add.changeDropdownMenu();
			});
		}
	},

	
	testEmailConnection: function(evt) 
	{
		// prevent default
		evt.preventDefault();
		
		// show spinner
		$('#testEmailConnectionSpinner').show();
		$('#testEmailConnectionError').hide();
		$('#testEmailConnectionSuccess').hide();
		
		// make the call
		$.ajax(
		{
			url: '/backend/ajax.php?module=settings&action=test_email_connection&language=' + jsBackend.current.language,
			data: $('#settingsEmail').serialize(),
			success: function(data, textStatus)
			{
				// hide spinner
				$('#testEmailConnectionSpinner').hide();

				// show success
				if(data.code == 200) $('#testEmailConnectionSuccess').show();
				else $('#testEmailConnectionsError').show();
			},
			error: function(XMLHttpRequest, textStatus, errorThrown)
			{
				// hide spinner
				$('#testEmailConnectionSpinner').hide();
				
				// show error
				$('#testEmailConnectionError').show();
			}
		});		
	},
	
	
	serviceMessageInlineEdit: function()
	{
		if($('#shareableModules .datagridHolder td.serviceMessage').length > 0)
		{
			// buil ajax-url
			var url = '/backend/ajax.php?module=settings&action=save_service_message&language='+ jsBackend.current.language;

			
			// bind
			$('#shareableModules .datagridHolder td.serviceMessage').inlineTextEdit( { saveUrl: url, tooltip: '{$msgClickToEdit}' });
		}
	},
	

	eoo: true
}


$(document).ready(jsBackend.settings.init);