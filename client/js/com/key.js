Key = {
	ctrl: [],
	esc: null,
	keys: [],
	
	onCtrl: function(key, cb)
	{
		Key.ctrl[key] = cb;
	},
	
	onEsc: function(cb)
	{
		Key.esc = cb;
	},
	
	on: function(key, cb)
	{
		Key.keys[key] = cb;
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
	else if ( e.keyCode == 27)
	{
		Key.esc();
	}
	else if ( e.keyCode )
	{
		for ( var i in Key.keys )
			if ( i == e.keyCode ) Key.keys[i]();
	}
}

$('body').on('keyup', handleKeys);