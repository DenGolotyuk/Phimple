App = {
	context: {},
	
	init: function(context)
	{
		App.context = context;
		
		eval(' if (typeof ' + context.action + '_action != "undefined" && typeof ' + context.action + '_action.execute != "undefined") ' + context.action + '_action.execute();');
	}
}