(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	$(document).ready(function(){
		$( document ).on( 'click', '#username_change_submit', function( e ){
			 e.preventDefault();
			 
			 let data = {
					action: "aum_update_username",
					nonce: aum_options.ajax_nonce,
					new_user_name: $('#aum_new_user_name').val(),
					current_user_name: $('#aum_current_user_name').val(),
					bp_is_my_profile: $('#bp_is_my_profile').val(),
				};
			/**
			 * Do AJAX
			 */
			$.ajax({
				url: aum_options.ajaxurl,
				data: data,
				type: "post",
				dataType: "json",
				success: function ( response ) {
					$('form.aum-standard-form p.aum-error').remove();
					$('form.aum-standard-form p.aum-success').remove();
					if( response.success == true ) {
						$("form.aum-standard-form #aum_new_user_input_field").after('<p class="aum-success">' + response.data.success_message	 + '</p>');
						if(  response.data.redirect_url !== '' ) {
							var redirect_url = response.data.redirect_url;
							setTimeout(function() {
								window.location.href = redirect_url; // Replace with your desired URL
							}, 5000); // 5000 milliseconds = 5 seconds
						}
						
					} else {
						$("form.aum-standard-form #aum_new_user_input_field").after('<p class="aum-error">' + response.data.error_message	 + '</p>');
					}
				},
			});			
		});
		
		$( document ).on( 'keyup', '#aum_new_user_name', function( e ){
			console.log( $(this).val() );
			let username 			= $( this ).val();
			let min_length 			= aum_options.min_username_length;
			let max_length 			= aum_options.max_username_length;
			let allowed_characters 	= aum_options.allowed_characters;
			let allowedCharacters 	= new RegExp("^[a-zA-Z0-9" + allowed_characters + "]+$");
			let message = "";
			let isValid = true;
			// Check minimum length
			if (username.length < min_length) {
				message += '<p class="aum-error">'+ aum_options.min_username_error+'</p>';
				isValid = false;
			}

			// Check maximum length
			if (username.length > max_length) {
				message += '<p class="aum-error">'+ aum_options.max_username_error+'</p>';
				isValid = false;
			}

			// Check allowed characters
			if (!allowedCharacters.test(username)) {
				message += '<p class="aum-error">'+ aum_options.allowed_characters_error+'</p>';
				isValid = false;
			}
			
			// Display message
			$('form.aum-standard-form p.aum-error').remove();
			$('form.aum-standard-form p.aum-success').remove();
			if (! isValid ) {
				$("form.aum-standard-form #aum_new_user_input_field").after(message);
				$('#username_change_submit').attr('disabled','disabled');
			} else {
				$('form.aum-standard-form p.aum-error').remove();
				$('form.aum-standard-form p.aum-success').remove();
				$('#username_change_submit').removeAttr('disabled');
				
				if ( username.length >= 2 ) { // Start autocomplete after 2 characters
					$.ajax({
						url: aum_options.ajaxurl,
						type: "post",
						dataType: "json",
						data: {
							action: "check_username_availability",
							nonce: aum_options.ajax_nonce,
							username: username,
						},
						success: function( response ) {
							$('form.aum-standard-form p.aum-success').remove();
							if( response.data.available	== false ) {
								var suggestions = response.data.suggestions;							
								var suggestionList = '';
								if (suggestions.length > 0) {
									suggestions.forEach(function(item) {
										suggestionList += '<li style="padding: 5px; cursor: pointer;">' + item + '</li>';
									});
									$('#aum-autocomplete-suggestions').html(suggestionList).show();
								} 
							} else if( response.data.available	== true ) {
								$("form.aum-standard-form #aum_new_user_input_field").after('<p class="aum-success">' + response.data.message	 + '</p>');
								$('#aum-autocomplete-suggestions').hide();
							}
						}
					});
				} else {
					$('#aum-autocomplete-suggestions').hide();
				}
				
			}
		});

		// Select suggestion on click
		$(document).on('click', '#aum-autocomplete-suggestions li', function() {
			$('#aum_new_user_name').val($(this).text());
			$('#aum-autocomplete-suggestions').hide();
		});
	});

})( jQuery );
