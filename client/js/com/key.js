Key = {
	ctrl: [],
	
	onCtrl: function(key, cb)
	{
		Key.ctrl[key] = cb;
	}
}

function handleKeys(e)
{
	var evtobj = window.event ? event : e
	
	if ( evtobj.ctrlKey )
	{
		for ( var i in Key.ctrl )
			if ( i == e.keyCode ) Key.ctrl[i]();
	}
}

$(document).on('keypress', handleKeys);