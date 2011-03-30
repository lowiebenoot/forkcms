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
			jsBackend.settings.showHideShorteners();
			
			$('input[name=shorten]').change(function()
			{
				jsBackend.settings.showHideShorteners();
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
	
	
	// hide or show the url shortener options
	showHideShorteners: function()
	{
		// show the shortener services or hide them, depending from the shorten radiobutton
		if($('input[name=shorten]:checked').length != 0) $('div#shorteners').show('slideUp');
		else $('div#shorteners').hide('slideDown');
	},
	

	eoo: true
}


$(document).ready(jsBackend.settings.init);