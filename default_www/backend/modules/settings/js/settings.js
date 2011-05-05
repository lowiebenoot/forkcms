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
		if($('#shareableModules td.share_message').length > 0)
		{
			// buil ajax-url
			var url = '/backend/ajax.php?module=settings&action=save_service_message&language='+ jsBackend.current.language;
			
			// bind
			$('#shareableModules td.share_message').inlineTextEdit( { saveUrl: url, tooltip: '{$msgClickToEdit}', allowEmpty: true });
		}
	},
	

	eoo: true
}


$(document).ready(jsBackend.settings.init);