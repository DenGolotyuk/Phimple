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
	},
	
	bookmark: function(url, title)
	{
		if (window.sidebar)
		{
			window.sidebar.addPanel(title, url, "");
		}
		else if (window.opera && window.print)
		{
			var elem = document.createElement('a');
			elem.setAttribute('href',url);
			elem.setAttribute('title',title);
			elem.setAttribute('rel','sidebar');
			elem.click();
		} 
		else if (document.all)
		{
			window.external.AddFavorite(url, title);
		}
		else
		{
			alert('Нажмите ctrl+d, чтобы добавить эту страницу в закладки');
		}
	},
	
	homepage: function()
	{
		if (document.all)
		{
			document.body.style.behavior='url(#default#homepage)';
			document.body.setHomePage( document.location.href );
		}
		else if (window.sidebar)
		{
			if( window.netscape )
			{
				try
				{  
					netscape.security.PrivilegeManager.enablePrivilege("UniversalXPConnect");  
				}  
				catch(e)  
				{  
					alert('Установите домашнюю страницу в настройках');
				}
			} 
			
			var prefs = Components.classes['@mozilla.org/preferences-service;1'].getService(Components. interfaces.nsIPrefBranch);
			prefs.setCharPref('browser.startup.homepage', document.location.href);
		}
		else
		{
			alert('Установите домашнюю страницу в настройках');
		}
	}
}