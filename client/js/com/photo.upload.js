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
		var m = App.context.iserver.match(/i([0-9]+)/);
		var file = m[1] + ':' + f;
		
		return file;
	}
}