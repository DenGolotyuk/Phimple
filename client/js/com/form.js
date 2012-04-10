Form = {
	bind: function( form )
	{
		$(form).submit(function() {
			var action = $(this).attr('action') ? $(this).attr('action') : document.location.href;
			var form = $(this);
			var submit = $('button[type=submit]', form).text();
			
			$('button[type=submit]', form).text('...').attr('disabled', true);
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
	}

}

Zepto(function(){
  $('form').each(function() {
	 Form.bind(this); 
  });
})