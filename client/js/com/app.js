App = {
	context: {},
	
	init: function(context)
	{
		App.context = context;
		
		eval(' if (' + context.action + '_action && ' + context.action + '_action.execute) ' + context.action + '_action.execute();');
	}
}