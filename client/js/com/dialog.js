Dialog = {
	scrolling: false,
	
	load: function(url, params, cb) {
		if ( !params ) params = {};
		Dialog.show();
		
		$.post(url, params, function(r) {
			Dialog.setContent(r);
			if ( typeof cb != 'undefined' ) cb();
		});
	},
	
	show: function()
	{
		if ( $('#dialog').length == 0 )
		{
			$('body').append('<div id="dialog"></div>');
		}
		
		$('#dialog').animate({
			top: $('body').attr('scrollTop') + 'px'
		}, 250, 'ease-out');
	},
	
	setContent: function(html)
	{
		$('#dialog').html(html);
	},
	
	close: function()
	{
		$('#dialog').animate({
			top: '-500px'
		}, 250, 'ease-in');
	}
}