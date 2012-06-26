PhotoUpload = {
	show: function( cb, url )
	{
		var url = url ? url : '/upload/photo';
		
		Dialog.load(url, {}, function() {
			App.include('uploader', function() {
				new AjaxUpload('upload-button', {
					action: 'http://' + App.context.iserver + '/u.php?cb=' + cb
				});

			});
		});
	},
	
	getPath: function(f)
	{
		var m = App.context.iserver.match(/i.*?([0-9]+)/);
		var file = m[1] + ':' + f;
		
		return file;
	},
	
	getServerFromPhoto: function(p)
	{
		var m = p.match(/(http:\/\/i.*?[0-9]+\.[^/]+?\/).+/);
		return m ? m[1] : null;
	}
}