Form = {
	submit: function( form )
	{
		$('input, textarea', form).each(function() {
			if ( $(this).attr('title') == $(this).val() ) $(this).val('');
		});

		var action = form.attr('action') ? form.attr('action') : document.location.href;

		Form.wait( $('button[type=submit]', form) );

		$('.error', form).hide();

		$.post(action, $(form).serialize(), function(r) {

			Form.stop_wait($('button[type=submit]', form));

			var error = false;
			if ( r )
			{
				if ( r.error )
				{
					var error = true;
					var e = $('.error', form);
					e.html(r.error).width(form.width() - parseInt(e.css('padding-left'))*2).fadeIn();
				}
			}
			else if ( $('.success', form).length > 0 )
			{
				$('.success', form).fadeIn(250);
			}

			var callback = form.attr('callback');
			
			if ( callback && !error )
			{
				var cb = eval(callback);
				cb(r, form);
			}

		}, 'json');
	},
	
	wait: function( btn, waiting )
	{
		if ( !waiting )
		{
			btn.width( btn.width() ).attr('disabled', true).attr('old_text', btn.text()).html('&bull;&sdot;&sdot;');
			setTimeout(function() {Form.wait(btn, true);}, 150);
		}
		else
		{
			if ( !btn.attr('disabled') ) return;

			if ( btn.attr('stage') == '2' ) btn.html('&sdot;&sdot;&bull;').attr('stage', '3');
			else if ( btn.attr('stage') == '3' ) btn.html('&bull;&sdot;&sdot;').attr('stage', '1');
			else btn.html('&sdot;&bull;&sdot;').attr('stage', '2')
			
			setTimeout(function() {Form.wait(btn, true);}, 150);
		}
	},
	
	stop_wait: function( btn )
	{
		btn.text(btn.attr('old_text')).removeAttr('disabled');
	}
}

Zepto(function(){
	
	$('form').live('submit', function() {
		if ( $(this).hasClass('no-ajax') ) return;
		
		Form.submit($(this));
		return false;
	} );
	
	$('input, textarea').live('focus', function() { $('.error').hide(); });
	$('input, textarea').live('click', function() { $('.error').hide(); });
	$('input, textarea').live('change', function() { $('.error').hide(); });
})