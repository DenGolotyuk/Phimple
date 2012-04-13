App = {
	context: {},
	
	init: function(context)
	{
		this.initAjax();
		
		App.context = context;
		eval(' if (typeof ' + context.action + '_action != "undefined" && typeof ' + context.action + '_action.execute != "undefined") ' + context.action + '_action.execute();');
	},
	
	initAjax: function()
	{
		$(document).on('ajaxSuccess', function(e, xhr, options, data) {
			if ( data )
			{
				if ( data.redirect )
					document.location = data.redirect;
				else if ( data.exception )
					alert(data.exception);
			}
		});
	},
	
	include: function(name, cb)
	{
		var sign = '';
		$('script').each(function() {
			var src = $(this).attr('src');
			var m = src.match(/js\.js\?(.+)/);
			if ( m ) sign = m[1];
		});
		
		include('/js:' + name + '.js?' + sign, cb);
	}
}