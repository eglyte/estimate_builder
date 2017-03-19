$(function() {

	// Get the form.
	var form = $('#ajax-contact');

	// Get the messages div.
	var formMessages = $('.form-messages');
	// Get number of locations
	var locationsCount = $('.bodyLocation .location').length;
	// Get form offset top
	var offsetX = Math.round($(form).offset().top - 20);

	// Set up an event listener for the contact form.
	$(form).submit(function(e) {
		// Stop the browser from submitting the form.
		e.preventDefault();

		// Serialize the form data.
		var formData = $(form).serialize();

		// Submit the form using AJAX.
		$.ajax({
			type: 'POST',
			url: $(form).attr('action'),
			data: formData
		})
		.done(function(response) {
			// Make sure that the formMessages div has the 'alert-success' class.
			$(formMessages).removeClass('alert-danger');
			$(formMessages).addClass('alert-success');
			$(form).find('#message').removeClass();
			$(form).find('#result input').val('199.00');
			if (locationsCount > 1) {
	            $('.bodyLocation .location:not(:first)').remove();
	        }
	        $('html, body').stop().animate({scrollTop: offsetX}, 2000);

			// Set the message text.
			$(formMessages).text(response);

			// Clear the form.
			$(form).each(function(){
			    this.reset();			    
			});
			//console.log(formData);
			
		})
		.fail(function(data) {
			// Make sure that the formMessages div has the 'alert-danger' class.
			$(formMessages).removeClass('alert-success');
			$(formMessages).addClass('alert-danger');

			// Set the message text.
			if (data.responseText !== '') {
				$(formMessages).text(data.responseText);
			} else {
				$(formMessages).text('Oops! An error occured and your message could not be sent.');
			}
		});

	});

});
