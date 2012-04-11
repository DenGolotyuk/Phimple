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
			if ( data && data.redirect )
				document.location = data.redirect;
		});
	}
}