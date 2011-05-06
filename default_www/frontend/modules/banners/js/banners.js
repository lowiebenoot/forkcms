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
		$('a.bannerWidgetURL').each(function()
		{
			// get element
			$this = $(this);
			var test = $(this);
			
			// get original url
			var originalUrl = $this.data('url');

			// get tracker url
			var trackerUrl = $this.attr('href');
 			
			// change url to the original url
			$this.attr('href', originalUrl);
			
			// change the href onclick so it redirects to the tracker page
			$this.click(function()
			{
				// change href
				$(this).attr('href', trackerUrl);
			});
		});
	},
	
	// end
	eoo: true
}


$(document).ready(function() { jsFrontend.banners.init(); });