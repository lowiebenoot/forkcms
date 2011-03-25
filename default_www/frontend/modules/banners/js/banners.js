if(!jsFrontend) { var jsFrontend = new Object(); }


/**
 * Interaction for the banners module
 *
 * @author	Lowie Benoot <lowiebenoot@netlash.com>
 */
jsFrontend.banners =
{
	// init, something like a constructor
	init: function()
	{
		jsFrontend.banners.controls.init();
	},


	// end
	eoo: true
}


jsFrontend.banners.controls =
{
	// init, something like a constructor
	init: function()
	{
		$('.bannerWidgetURL').click(function()
		{
			// get the id of the banner
			var bannerId = $(this).attr('data-id');
			
			// split url to build the ajax-url
			var chunks = document.location.pathname.split('/');
			
			// get language from chuncks
			var language = chunks[1];
			
			// increase the num clicks via ajax
			$.ajax({
				url:'/frontend/ajax.php?module=banners&action=increase_clicks&language=' + language,
				data: {id: bannerId}
			});
		});
	},
	
	// end
	eoo: true
}


$(document).ready(function() { jsFrontend.banners.init(); });