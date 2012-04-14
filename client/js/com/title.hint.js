TitleHint = {
	init: function()
	{
		$('textarea, input').each(function() {
			if ( $(this).attr('title') )
			{
				$(this).focus( function() {
					if ( $(this).val() == $(this).attr('title') )
						$(this).val('');
				} );

				$(this).blur( function() {
					if ( !$(this).val() )
						$(this).val( $(this).attr('title') );
				} ).trigger('blur');
			}
		});
	}
}

Zepto(TitleHint.init);