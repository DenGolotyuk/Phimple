Form = {
	bind: function( form )
	{
		$(form).submit(function() {
			var action = $(this).attr('action') ? $(this).attr('action') : document.location.href;
			var form = $(this);
			var submit = $('button[type=submit]', form).text();
			
			$('button[type=submit]', form).width( $('button[type=submit]', form).width() ).attr('disabled', true).html('&bull;&sdot;&sdot;');
			Form.wait( $('button[type=submit]', form) );
			
			$('.error', form).hide();
			
			$.post(action, $(form).serialize(), function(r) {
				
				$('button[type=submit]', form).text(submit).removeAttr('disabled');
				
				if ( r )
				{
					if ( r.error )
					{
						var e = $('.error', form);
						e.html(r.error).width(form.width() - parseInt(e.css('padding-left'))*2).show();
					}
				}
				
			}, 'json');
			return false;
		});
		
		$('input', form).focus(function() {
			$('.error', form).hide();
		});
	},
	
	wait: function( btn )
	{
		if ( !btn.attr('disabled') ) return;
		
		if ( btn.attr('stage') == '2' ) btn.html('&sdot;&sdot;&bull;').attr('stage', '3');
		else if ( btn.attr('stage') == '3' ) btn.html('&bull;&sdot;&sdot;').attr('stage', '1');
		else btn.html('&sdot;&bull;&sdot;').attr('stage', '2')
		
		setTimeout(function() {Form.wait(btn);}, 150);
	}
}

Zepto(function(){
  $('form').each(function() {
	 Form.bind(this); 
  });
})